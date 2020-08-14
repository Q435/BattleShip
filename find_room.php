<?php

    session_start();
    include("include/util.inc.php");

    checkLogin();
    checkGame();

    $user_id = $_SESSION['user_id'];
    //$game_id = 1;

    $all_free_game = sqlFindAllFreeGame($user_id);

    echo json_encode($all_free_game);

?>
