<?php

    session_start();
    include("include/util.inc.php");

    checkLogin();
    checkGame();

    if (!isset($_POST['type']) || !isset($_POST['event'])) {
        echo "Missing parameters";
        die();
    }

    $type = $_POST['type'];
    $event = $_POST['event'];

    if ($type === "game") {
        if ($event === "create") {
            $userId = $_SESSION['user_id'];
            $nickName = $_SESSION['nick_name'];
            $_SESSION['game_id'] = $userId;
            $sql = "INSERT IGNORE INTO $GAME_TABLE (player1_id, player1_name, player_num) VALUES ($userId, '$nickName', 1)";

            sqlExecute($sql);
            $result = array( "code" => 200);
            echo json_encode($result);
        }
    }

?>
