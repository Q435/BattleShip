<?php

    session_start();

    include("include/util.inc.php");

    if (isset($_SESSION['game_id'])) {
        unset($_SESSION['game_id']);
    }
    //echo $_SESSION['game_id'];

    $result = $PDO->query("SELECT * FROM $GAME_TABLE WHERE player1_id = {$_SESSION['user_id']} OR player2_id = {$_SESSION['user_id']}")->fetch();
    if ($result) {
        $_SESSION['game_id'] = $result['Id'];
    }

?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Game_id</title>
</head>
<body>

<h1><?=  $_SESSION['game_id'] ?></h1>
<p><?= $result ?></p>
</body>
</html>

