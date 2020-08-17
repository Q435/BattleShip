<?php

    session_start();
    include("include/util.inc.php");

    checkLogin();
    $userId = $_SESSION['user_id'];

    checkGame($userId);
    $all_free_game = findAllFreeGame();

    echo json_encode($all_free_game);

?>
