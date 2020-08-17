<?php

    session_start();
    include("include/util.inc.php");

    checkLogin();

    if (!isset($_POST['type']) || !isset($_POST['event'])) {
        echo "Missing parameters";
        die();
    }

    $userId = $_SESSION['user_id'];
    $type = $_POST['type'];
    $event = $_POST['event'];
    $result = array("code" => 300);

    if (!isset($_SESSION['game_id'])) {
        if (!checkGame() && $event != "create" && $event != "join") {
            header("Location:room.php");
            die();
        }
    }

    if ($type === "game") {
        if ($event === "create") {
            $_SESSION['player'] = "player1";
            if (!sqlINSERT($GAME_TABLE, array("player1_id" => $userId, "player1_name" => $_SESSION['nick_name'],
                "player_num" => 1, "player1_ready" => "none"))) {
                $result['code'] = 100;
            }
            if (!checkGame($userId)) {
                $result['code'] = 100;
            } else {
                $result['code'] = 200;
            }
            //$sql = "INSERT IGNORE INTO $GAME_TABLE (player1_id, player1_name, player_num, player1_ready) VALUES ($userId, '$nickName', 1, 'none')";
            //sqlExecute($sql);
            //$sql = "SELECT Id FROM $GAME_TABLE WHERE player1_id = $userId";
            //$gameId = sqlExecute($sql)->fetch();
            //$_SESSION['game_id'] = $gameId['Id'];
        } elseif ($event === "query") {
            $mode = $_POST['mode'];
            if ($mode === "opponent") {
                $player = $_SESSION['player'] . "_id";
                $res = sqlSELECT($GAME_TABLE, null, array("player_num" => 2, $player => $userId), true)->fetch();
                //$sql = "SELECT * FROM $GAME_TABLE WHERE player_num = 2 AND (player1_id = $userId OR player2_id = $userId)";
                //$sql_result = sqlExecute($sql)->fetch();
                if ($res) {
                    $result['code'] = 202;
                    $_SESSION['opponent_id'] = $res['player1_id'] === $userId ? $res['player2_id'] : $res['player1_id'];
                    if ($res['player1_ready'] === "ready" && $res['player2_ready'] === "ready") {
                        $result['code'] = 203;
                        if (empty($res['current_player'])) {
                            $current_player = "player" . rand(1,2);
                            if ($current_player === $_SESSION['player']) {
                                $result['current_player'] = "you";
                            } else {
                                $result['current_player'] = "opponent";
                            }
                            sqlUPDATE($GAME_TABLE, array("current_player" => $current_player), array("Id" => $res['Id']));
                            //$sql = "UPDATE $GAME_TABLE SET current_player = '$current_player' WHERE Id = {$sql_result['Id']}";
                            //sqlExecute($sql);
                        } else {
                            if ($res['current_player'] === $_SESSION['player']) {
                                $result['current_player'] = "you";
                            } else {
                                $result['current_player'] = "opponent";
                            }
                        }
                    }
                } else {
                    $result['code'] = 201;
                }
            } elseif ($mode === "battle") {
                $gameId = $_SESSION['game_id'];
                $player = $_SESSION['player'];
                $player_result = sqlSELECT($GAME_TABLE, null, array($player . "_id" => $userId), true)->fetch();
                //$sql = "SELECT * FROM $GAME_TABLE WHERE {$player}_id = $userId";
                //$player_result = sqlExecute($sql)->fetch();

                $result['current_player'] = $player_result['current_player'] === $player ? "you" : "opponent";
                $res = sqlSELECT($BOARD_TABLE, null, array("user_id" => $userId), true)->fetch();

                //$sql = "SELECT board FROM $BOARD_TABLE WHERE user_id = $userId";
                //$board_result = sqlExecute($sql)->fetch();
                $result['board'] = $res['board'];

                $hit_result = sqlSELECT($BOARD_TABLE, array("user_id"), array("game_id" => $gameId, "my_hit_num" => 5))->fetch();
                //$sql = "SELECT user_id FROM $BOARD_TABLE WHERE game_id = $gameId AND hit_num = 5";
                //$hit_result = sqlExecute($sql)->fetch();
                if ($hit_result) {
                    $opponentId = $_SESSION['opponent_id'];
                    $opponentBoard = sqlSELECT($BOARD_TABLE, array("board"), array("user_id" => $opponentId))->fetch();
                    sqlINSERT($RECORD_TABLE, array("user_id" => $userId, "opponent_id" => $opponentId,
                        "total_fire_num" => $res['total_fire'], "hit_fire_num" => $res['hit_fire'], "my_board" => $res['board'],
                        "opponent_board" => $opponentBoard));
                    $removeInfo = sqlSELECT($GAME_TABLE, array("Id", "player1_id", "player2_id"),
                        array("player1_ready" => "remove", "player2_ready" => "remove"))->fetch();
                    if ($removeInfo) {
                        sqlDELETE($BOARD_TABLE, array("user_id" => $removeInfo['player1_id']));
                        sqlDELETE($BOARD_TABLE, array("user_id" => $removeInfo['player2_id']));
                        sqlDELETE($GAME_TABLE, array("Id" => $removeInfo['Id']));
                    } else {
                        $player = $_SESSION['player'];
                        sqlUPDATE($GAME_TABLE, array($player . "_ready" => "remove"), array($player . "_id" => $userId));
                    }
                    //sqlDELETE($BOARD_TABLE, array("user_id" => $userId));

                    $result['code'] = 201;
                    if ($hit_result['user_id'] === $userId) {
                        $result['win'] = "you";
                    } else {
                        $result['win'] = "opponent";
                    }
                } else {
                    $result['code'] = 200;
                }
            }
        } elseif ($event === "update") {
            sqlUPDATE($GAME_TABLE, array($_SESSION['player'] . "_ready" => "ready"), array("Id" => $_SESSION['game_id']));
            //$sql = "UPDATE $GAME_TABLE SET {$_SESSION['player']}_ready = 'ready' WHERE Id = {$_SESSION['game_id']}";
            //sqlExecute($sql);
        } elseif ($event === "join") {
            if (isset($_POST['id'])) {
                if (sqlUPDATE($GAME_TABLE, array("player2_id" => $userId, "player2_name" => $_SESSION['nick_name'],
                    "player_num" => 2, "player2_ready" => "none"), array("Id" => $_POST['id']))) {
                    $_SESSION['game_id'] = $_POST['id'];
                    $_SESSION['player'] = "player2";
                    $result['code'] = 200;
                } else {
                    $result['code'] = 100;
                }
                /*
                $sql = "UPDATE $GAME_TABLE SET player2_id = $userId, player2_name = '{$_SESSION['nick_name']}', player_num = 2, player2_ready = 'none' WHERE Id = {$_POST['id']}";
                sqlExecute($sql);
                $_SESSION['game_id'] = $_POST['id'];
                $_SESSION['player'] = "player2";
                $result['code'] = 200;
                */
            }
        }
    }

    echo json_encode($result);

?>
