<?php

define("VERTICAL", 2);
define("HORIZONTAL", 1);

$USER_TABLE = 'users';
$BOARD_TABLE = "boards";
$GAME_TABLE = "games_active";
$RECORD_TABLE = "record";
$PDO = getPDO();

function getPDO() {
    $host = '127.0.0.1:3306';
    $db   = 'seabattle_db';
    $user = 'seabattle_db';
    $pass = 'seabattle_db';
    $charset = 'utf8';

    $dsn = "mysql:host=$host;dbname=$db;charset=$charset";
    $options = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false
    ];
    try {
        return new PDO($dsn, $user, $pass, $options);
    } catch (\PDOException $e) {
        throw new \PDOException($e->getMessage(), (int)$e->getCode());
    }
}

function checkUsername($username) {
    global $USER_TABLE;
    $res = sqlSELECT($USER_TABLE, null, array("username" => $username), true)->fetchColumn();
    return $res > 0;
}

function checkNickname($nickname) {
    global $USER_TABLE;
    $res = sqlSELECT($USER_TABLE, null, array("nickname" => $nickname), true)->fetchColumn();
    return $res > 0;
}

function checkPassword($username, $password) {
    global $USER_TABLE;
    $res = sqlSELECT($USER_TABLE, array("Id", "nickname", "password"), array("username" => $username))->fetch();
    if ( $res && password_verify($password, $res['password']) ) {
        return $res;
    }
    return false;
}

function checkLogin() {
    if (!isset($_SESSION['user_id'])) {
        header("Location:login_form.php");
        die();
    }
}

function checkGame($userId) {
    global $GAME_TABLE;

    $res = sqlSELECT($GAME_TABLE, array("Id"), array("player1_id" => $userId, "player2_id" => $userId), true, "OR")->fetch();
    if ($res) {
        $_SESSION['game_id'] = $res['Id'];
        return true;
    }
    return false;
    //$result = $PDO->query("SELECT * FROM $GAME_TABLE WHERE player1_id = {$_SESSION['user_id']} OR player2_id = {$_SESSION['user_id']}")->fetch();
}

function getUserId($user) {
    return $user['Id'];
}

function getNickName($user) {
    return $user['nickname'];
}

function getMsg($code) {
    $msg = "";
    switch ($code) {
        case 1:
            $msg = "username can not be empty";
            break;
        case 2:
            $msg = "username must contain numbers and letters and length between 5 and 30";
            break;
        case 3:
            $msg = "username already exists";
            break;
        case 4:
            $msg = "nickname can not be empty";
            break;
        case 5:
            $msg = "nickname contain numbers or letters and length between 5 and 30";
            break;
        case 6:
            $msg = "nickname already exists";
            break;
        case 7:
            $msg = "password can not be empty";
            break;
        case 8:
            $msg = "password too weak (must contain numbers, uppercase and lowercase letters and length between 8 and 30)";
            break;
        case 9:
            $msg = "Incorrect username or password";
            break;
        case 10:
            $msg = "You have successfully registered an account!";
            break;
        case 11:
            $msg = "Something went wrong. Account registration failed, please try again.";
            break;
    }
    return $msg;
}

function getNickNameById($userId) {
    global $USER_TABLE;
    return sqlSELECT($USER_TABLE, array("nickname"), array("Id" => $userId))->fetch();
    //$sql = "SELECT nickname FROM $USER_TABLE WHERE Id = $user_id";
    //return sqlExecute($sql)->fetch();
}

function getUserGameInfo($user_id) {
    global $USER_TABLE;
    return sqlSELECT($USER_TABLE, array("total_game", "win_game", "lose_game"), array("Id" => $user_id))->fetch();
}

function getAllRecord($user_id) {
    global $RECORD_TABLE;
    return sqlSELECT($RECORD_TABLE, null, array("user_id" => $user_id), true)->fetchAll();
}

function changePlayer($userId, $player) {
    global $GAME_TABLE;

    $res = sqlSELECT($GAME_TABLE, array("Id", "current_player"), array("$player" . "_id" => $userId))->fetch();

    //$user_id = $_SESSION['user_id'];
    //$sql = "SELECT Id, current_player FROM $GAME_TABLE WHERE player1_id = $user_id OR player2_id = $user_id";
    //$result = sqlExecute($sql);
    if ($res) {
        if ($res['current_player'] === "player1") {
            sqlUPDATE($GAME_TABLE, array("current_player" => "player2"), array("Id" => $res['Id']));
            //$sql = "UPDATE $GAME_TABLE SET current_player = 'player2' WHERE Id = {$result['Id']}";
        } else {
            sqlUPDATE($GAME_TABLE, array("current_player" => "player1"), array("Id" => $res['Id']));
            //$sql = "UPDATE $GAME_TABLE SET current_player = 'player1' WHERE Id = {$result['Id']}";
        }
        //sqlExecute($sql);
    }
}

function sqlQuery($sql) {
    global $PDO;
    try {
        $result = $PDO->query($sql);
    } catch (PDOException $e) {
        echo $e->getMessage() . "  |  " . $sql;
        die();
    }
    return $result;
}

function sqlExecute($sql) {
    global $PDO;
    try {
        $result = $PDO->exec($sql);
    } catch (PDOException $e) {
        echo $e->getMessage() . "  |  " . $sql;
        die();
    }
    return $result;
}

function findAllFreeGame() {
    global $GAME_TABLE;
    return sqlSELECT($GAME_TABLE, array("Id", "player1_name"), array("player_num" => 1))->fetchAll();

    /*
    $sql = "SELECT Id, player1_name FROM $GAME_TABLE WHERE player_num = 1";

    try {
        $result = $PDO->query($sql)->fetchAll();
    } catch (PDOException $e) {
        echo $e->getMessage() . "   " . $sql;
        die();
    }

    return $result;
    */
}

function sqlUpdateBoard($board, $id) {
    global $BOARD_TABLE;
    $board = json_encode($board);
    sqlUPDATE($BOARD_TABLE, array("board" => $board), array("Id" => $id));

    /*
    $sql = "UPDATE $BOARD_TABLE SET board = '$board' WHERE Id = $id";

    try {
        $PDO->query($sql);
    } catch (PDOException $e) {
        echo $e->getMessage() . "   " . $sql;
        die();
    }
    */
}

function sqlLogout($userId) {
    global $BOARD_TABLE, $GAME_TABLE;

    sqlDELETE($BOARD_TABLE, array("user_id" => $userId));
    sqlDELETE($GAME_TABLE, array("player1_id" => $userId, "player2_id" => $userId), "OR");

    //$sql = "DELETE FROM $BOARD_TABLE WHERE user_id = $userId";
    //sqlExecute($sql);
    //$sql = "DELETE FROM $GAME_TABLE WHERE player1_id = $userId OR player2_id = $userId";
    //sqlExecute($sql);
}

function sqlUpdateHit($userId, $opponentId) {
    global $BOARD_TABLE;

    /*
    $res = sqlSELECT($BOARD_TABLE, array("my_hi_num"), array("user_id" => $userId))->fetch();
    //$sql = "SELECT my_hit_num FROM $BOARD_TABLE WHERE user_id = $userId";
    //$result = sqlExecute($sql);
    $myHitNum = $res['my_hit_num'];
    $myHitNum++;
    */
    sqlUPDATE($BOARD_TABLE, array("my_hit_num" => "my_hit_num + 1"), array("user_id" => $userId));
    sqlUPDATE($BOARD_TABLE, array("opponent_hit_num" => "opponent_hit_num + 1"), array("user_id" => $opponentId));
    //$sql = "UPDATE $BOARD_TABLE SET my_hit_num = $myHitNum WHERE user_id = $userId";
    //sqlExecute($sql);

    //$sql = "UPDATE $BOARD_TABLE SET opponent_hit_num = $myHitNum WHERE user_id = $opponentId";
    //sqlExecute($sql);

}

function getTableFields($array) {
    if (gettype($array) != "array") {
        return false;
    }
    return "`" . join("`, `", array_keys($array)) . "`";
}

function getKeyAndVal($array, $ch) {
    if (gettype($array) != "array") {
        return false;
    }

    $str = array();
    foreach ($array as $k => $v) {
       array_push($str, "`$k` = '$v'");
    }

    return join(" $ch ", array_values($str));
}

function getValues($array, $ch) {
    if (gettype($array) != "array") {
        return false;
    }
    return "$ch" . join("$ch, $ch", array_values($array)) . "$ch";
}

function sqlINSERT($table, $params) {
    if (empty($table) || empty($params) || !is_array($params)) {
        return false;
    }

    $fields = getTableFields($params) or die("getTableFields error");
    $values = getValues($params, "'") or die("getValues error");
    $sql = "INSERT INTO `{$table}` ( $fields ) VALUES ( $values )";

    $cnt = sqlExecute($sql);

    if ($cnt === 0) {
        return false;
    }

    return true;
}

function sqlSELECT($table, $params, $req, $option = false, $ch = "AND") {
    if (empty($table)) {
        return false;
    }

    if ($option) {
        $sql = "SELECT * FROM `{$table}`";
    } else {
        if (empty($params) || !is_array($params)) {
            return false;
        }
        $values = getValues($params, "`") or die("getValues error");
        $sql = "SELECT $values FROM `{$table}`";
    }

    if ($req != null && is_array($req)) {
        $where = getKeyAndVal($req, $ch) or die("getKeyAndVal error");
        $sql .= " WHERE ( $where )";
    }

    return sqlQuery($sql);
}

function sqlDELETE($table, $req, $ch = "AND") {
    if (empty($table) || empty($req) || !is_array($req)) {
        return false;
    }

    $where = getKeyAndVal($req, $ch) or die("getKeyAndVal error");
    $sql = "DELETE FROM `{$table}` WHERE ( $where )";

    $cnt = sqlExecute($sql);

    if ($cnt === 0) {
        return false;
    }

    return true;
}

function sqlUPDATE($table, $params, $req) {
    if (empty($table) || empty($params) || !is_array($params)) {
        return false;
    }

    //$fields = getTableFields($params);
    //$values = getValues($params);

    $info = getKeyAndVal($params, ",") or die("getKeyAndVal error");

    $sql = "UPDATE `{$table}` SET ( $info )";

    if ($req != null) {
        $where = getKeyAndVal($req, "AND") or die("getKeyAndVal error");
        $sql .= " WHERE ( $where )";
    }

    $cnt = sqlExecute($sql);

    if ($cnt === 0) {
        return false;
    }

    return true;
}

function makeTr($i, $date) {
    $opponent_name = getNickNameById($date['opponent_id']);
    $accuracy = round($date['hit_num'] / $date['total_num'] * 100, 2) . '%';

    $result = '<tr>';
    $result .= '<td>' . $i . '</td>';
    $result .= '<td>' . $opponent_name . '</td>';
    $result .= '<td>' . $date['total_fire_num'] . '</td>';
    $result .= '<td>' . $date['hit_fire_num'] . '</td>';
    $result .= '<td>' . $accuracy . '</td>';
    $result .= '<td>' . $date['date'] . '</td>';
    $result .= '<td>' . "board" . '</td>';
    $result .= '</tr>';

    return $result;
}
