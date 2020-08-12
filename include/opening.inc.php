<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>BattleShip - TIEI 2020</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="css/common.css" >
    <script src="js/simpleajax.js"></script>
    <script src="js/battleship.js"></script>
</head>
<body>
<div class="header">
    <p class="top_left"><a href="index.php">BattleShip TIEI-2020</a></p>
    <span class="top_right">
    <?php
        if (isset( $_SESSION["user_id"])) {
    ?>

        Welcome <?= $_SESSION["nickname"] ?> | <a href='logout.php' title='click to sign-out'>Log Out</a>

    <?php
        } else {
    ?>
        <a href='register_form.php' title='click to register a account'>Register</a> |
        <a href='login_form.php' title='click to login'>Log In</a>

    <?php
        }
    ?>
    </span>
</div>
<div class="main">

<?php
