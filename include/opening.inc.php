<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>BattleShip - TIEI 2020</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <?php
        if (isset($css_files)) {
            foreach ($css_files as $file) {
                echo "<link rel=\"stylesheet\" type=\"text/css\" href=\"css/$file.css\">";
            }
        }
    ?>

    <?php
        if (isset($js_files)) {
            foreach ($js_files as $file) {
                echo "<script src=\"js/$file.js\"></script>";
            }
        }
    ?>

</head>
<body>
<div class="header">
    <span class="top_left"><a href="index.php">BattleShip TIEI-2020</a></span>
    <span class="top_right">
    <?php
        if (isset( $_SESSION["user_id"])) {
    ?>
        Welcome <?= $_SESSION["nick_name"] ?> | <a href='logout.php' title='click to sign-out'>Log Out</a>
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
