<?php
    session_start();

    include("include/util.inc.php");

    if (isset($_SESSION["user_id"])) {
        header("Location:index.php");
        die();
    }

    $username = $_POST["username"];
    $password = $_POST["password"];

    if (empty($username)) {
        header("Location:login_form.php?error_code=1");
        die();
    } elseif (!preg_match('/^(?=.*\d)(?=.*[a-zA-Z])[a-zA-Z0-9]{5,30}$/', $username)) {
        header("Location:login_form.php?error_code=2");
        die();
    } elseif (!checkUsername($username)) {
        header("Location:login_form.php?error_code=9");
        die();
    }

    if (empty($password)) {
        header("Location:login_form.php?error_code=7");
        die();
    }

    $user = checkPassword($username, $password);

    if ($user) {
        $_SESSION["user_id"] = getUserId($user);
        $_SESSION["nick_name"] = getNickName($user);
        header("Location:index.php");
        die();
    }

    header("Location:login_form.php?error_code=9");
?>

