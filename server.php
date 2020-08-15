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
    $result = array();
    $result['code'] = 300;

    if ($type === "game") {
        if ($event === "create") {
            $nickName = $_SESSION['nick_name'];
            $_SESSION['player'] = "player1";
            $sql = "INSERT IGNORE INTO $GAME_TABLE (player1_id, player1_name, player_num, player1_ready) VALUES ($userId, '$nickName', 1, 'none')";
            sqlExecute($sql);
            $sql = "SELECT Id FROM $GAME_TABLE WHERE player1_id = $userId";
            $gameId = sqlExecute($sql)['Id'];
            $_SESSION['game_id'] = $gameId;
            $result['code'] = 200;
        } elseif ($event === "query") {
            $mode = $_POST['mode'];
            if ($mode === "opponent") {
                $sql = "SELECT * FROM $GAME_TABLE WHERE player_num = 2 AND (player1_id = $userId OR player2_id = $userId)";
                $sql_result = sqlExecute($sql);
                $result['code'] = 201;
                if ($sql_result) {
                    $result['code'] = 202;
                    $_SESSION['opponent_id'] = $sql_result['player1_id'] === $userId ? $sql_result['player2_id'] : $sql_result['player1_id'];
                    if ($sql_result['player1_ready'] === "ready" && $sql_result['player2_ready'] === "ready") {
                        $result['code'] = 203;
                        if (empty($sql_result['current_player'])) {
                            $current_player = "player" . rand(1,2);
                            if ($current_player === $_SESSION['player']) {
                                $result['current_player'] = "you";
                            } else {
                                $result['current_player'] = "opponent";
                            }
                            $sql = "UPDATE $GAME_TABLE SET current_player = '$current_player' WHERE Id = {$sql_result['Id']}";
                            sqlExecute($sql);
                        } else {
                            if ($sql_result['current_player'] === $_SESSION['player']) {
                                $result['current_player'] = "you";
                            } else {
                                $result['current_player'] = "opponent";
                            }
                        }
                    }
                }
            } elseif ($mode === "battle") {
                $gameId = $_SESSION['game_id'];
                $player = $_SESSION['player'];
                $sql = "SELECT * FROM $GAME_TABLE WHERE {$player}_id = $userId";
                $player_result = sqlExecute($sql);
                $result['code'] = 200;
                $result['current_player'] = $player_result['current_player'] === $player ? "you" : "opponent";
                $sql = "SELECT board FROM $BOARD_TABLE WHERE user_id = $userId";
                $board_result = sqlExecute($sql);
                $result['board'] = $board_result['board'];
                $sql = "SELECT user_id FROM $BOARD_TABLE WHERE game_id = $gameId AND hit_num = 5";
                $hit_result = sqlExecute($sql);
                if ($hit_result) {
                    $result['code'] = 201;
                    if ($hit_result['user_id'] === $userId) {
                        $result['win'] = "opponent";
                    } else {
                        $result['win'] = "you";
                    }
                }
            }
        } elseif ($event === "update") {
            $sql = "UPDATE $GAME_TABLE SET {$_SESSION['player']}_ready = 'ready' WHERE Id = {$_SESSION['game_id']}";
            sqlExecute($sql);
        } elseif ($event === "join") {
            if (isset($_POST['id'])) {
                $sql = "UPDATE $GAME_TABLE SET player2_id = $userId, player2_name = '{$_SESSION['nick_name']}', player_num = 2, player2_ready = 'none' WHERE Id = {$_POST['id']}";
                sqlExecute($sql);
                $_SESSION['game_id'] = $_POST['id'];
                $_SESSION['player'] = "player2";
                $result['code'] = 200;
            }
        }
    }

    echo json_encode($result);

?>
