<?php
    session_start();
    include("include/util.inc.php");

    $username = trim($_POST["username"]);
    $nickname = trim($_POST["nickname"]);
    $password = trim($_POST["password"]);

    if (empty($username)) {
        header("Location:register_form.php?code=1");
        die();
    } elseif (!preg_match('/^(?=.*\d)(?=.*[a-zA-Z])[a-zA-Z0-9]{5,30}$/', $username)) {
        header("Location:register_form.php?code=2");
        die();
    } elseif (checkUsername($username)) {
        header("Location:register_form.php?code=3");
        die();
    }

    if (empty($nickname)) {
        header("Location:register_form.php?code=4");
        die();
    } elseif (!preg_match('/^[A-Za-z0-9_]{5,30}$/', $nickname)) {
        header("Location:register_form.php?code=5");
        die();
    } elseif (checkNickname($nickname)) {
        header("Location:register_form.php?code=6");
        die();
    }

    if (empty($password)) {
        header("Location:register_form.php?code=7");
        die();
    } elseif (!preg_match('/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,20}$/', $password)) {
        header("Location:register_form.php?code=8");
        die();
    }

    $password = password_hash($password, PASSWORD_BCRYPT);
    $res = sqlINSERT($USER_TABLE, array("username" => $username, "nickname" => $nickname, "password" => $password));

    ///$sql = "INSERT INTO $USER_TABLE (username, nickname, password) VALUES ('$username', '$nickname', '$password')";
    //$res = sqlExecute($sql)->rowCount();
    if ($res) {
        header("Location:login_form.php?code=10");
    } else {
        header("Location:register_form.php?code=11");
    }

?>

