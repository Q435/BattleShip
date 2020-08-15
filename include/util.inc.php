<?php

include("pdo.inc.php");

$USER_TABLE = 'users';
$BOARD_TABLE = "boards";
$GAME_TABLE = "games_active";
$PDO = getPDO();

function register($username, $nickname, $password) {
    global $PDO, $USER_TABLE;
    $password = password_hash($password, PASSWORD_BCRYPT);
    $sql = $PDO->query("INSERT INTO $USER_TABLE (username, nickname, password) VALUES ('$username', '$nickname', '$password')");
    return $sql->rowCount() > 0;
}

function checkUsername($username) {
    global $PDO, $USER_TABLE;
    $sql = $PDO->query("SELECT COUNT(*) FROM $USER_TABLE WHERE username = '$username'");
    return $sql->fetchColumn() > 0;
}

function checkNickname($nickname) {
    global $PDO, $USER_TABLE;
    $sql = $PDO->query("SELECT COUNT(*) FROM $USER_TABLE WHERE nickname = '$nickname'");
    return $sql->fetchColumn() > 0;
}

function checkPassword($username, $password) {
    global $PDO, $USER_TABLE;
    $result = $PDO->query("SELECT * FROM $USER_TABLE WHERE username = '$username'")->fetch();

    if ( $result && password_verify($password, $result['password']) )
        return $result;
    return false;
}

function checkLogin() {
    if (!isset($_SESSION['user_id'])) {
        if (!isset($_GET['user_id'])) {
            header("Location:login_form.php");
            die();
        }
    }
}

function checkGame() {
    global $PDO, $GAME_TABLE;

    $result = $PDO->query("SELECT * FROM $GAME_TABLE WHERE player1_id = {$_SESSION['user_id']} OR player2_id = {$_SESSION['user_id']}")->fetch();
    if ($result) {
        $_SESSION['game_id'] = $result['Id'];
        header("Location:online.php");
        die();
    }

}

function getUserId($user) {
    return $user['Id'];
}

function getNickName($user) {
    return $user['nickname'];
}

function errorMsg($error_code) {
    $msg = "";
    switch ($error_code) {
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
    }
    return $msg;
}

function changePlayer() {
    global $GAME_TABLE;

    $user_id = $_SESSION['user_id'];
    $sql = "SELECT Id, current_player FROM $GAME_TABLE WHERE player1_id = $user_id OR player2_id = $user_id";
    $result = sqlExecute($sql);
    if ($result) {
        if ($result['current_player'] === "player1") {
            $sql = "UPDATE $GAME_TABLE SET current_player = 'player2' WHERE Id = {$result['Id']}";
        } else {
            $sql = "UPDATE $GAME_TABLE SET current_player = 'player1' WHERE Id = {$result['Id']}";
        }
        sqlExecute($sql);
    }
}

function sqlExecute($sql) {
    global $PDO;

    try {
        $result = $PDO->query($sql)->fetch();
    } catch (PDOException $e) {
        echo $e->getMessage() . "   " . $sql;
        die();
    }

    return $result;
}

function sqlFindAllFreeGame($user_id) {
    global $PDO, $GAME_TABLE;
    $sql = "SELECT Id, player1_name FROM $GAME_TABLE WHERE player_num = 1";

    try {
        $result = $PDO->query($sql)->fetchAll();
    } catch (PDOException $e) {
        echo $e->getMessage() . "   " . $sql;
        die();
    }

    return $result;
}

function sqlUpdateBoard($board, $id) {
    global $PDO, $BOARD_TABLE;

    $board = json_encode($board);
    $sql = "UPDATE $BOARD_TABLE SET board = '$board' WHERE Id = $id";

    try {
        $PDO->query($sql);
    } catch (PDOException $e) {
        echo $e->getMessage() . "   " . $sql;
        die();
    }

}

function sqlLogout($userId) {
    global $BOARD_TABLE, $GAME_TABLE;

    $sql = "DELETE FROM $BOARD_TABLE WHERE user_id = $userId";
    sqlExecute($sql);
    $sql = "DELETE FROM $GAME_TABLE WHERE player1_id = $userId OR player2_id = $userId";
    sqlExecute($sql);
}

function sqlUpdateHit($userId) {
    global $BOARD_TABLE;

    $sql = "SELECT Id, hit_num FROM $BOARD_TABLE WHERE user_id = $userId";
    $result = sqlExecute($sql);
    $hitNum = $result['hit_num'];
    $hitNum++;
    $sql = "UPDATE $BOARD_TABLE SET hit_num = $hitNum WHERE Id = {$result['Id']}";
    sqlExecute($sql);
}
