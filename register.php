<?php
    session_start();
    include("include/util.inc.php");

    $username = trim($_POST["username"]);
    $nickname = trim($_POST["nickname"]);
    $password = trim($_POST["password"]);

    if (empty($username)) {
        header("Location:register_form.php?error_code=1");
        die();
    } elseif (!preg_match('/^(?=.*\d)(?=.*[a-zA-Z])[a-zA-Z0-9]{5,30}$/', $username)) {
        header("Location:register_form.php?error_code=2");
        die();
    } elseif (checkUsername($username)) {
        header("Location:register_form.php?error_code=");
        die();
    }

    if (empty($nickname)) {
        header("Location:register_form.php?error_code=4");
        die();
    } elseif (!preg_match('/^[A-Za-z0-9_]{5,30}$/', $nickname)) {
        header("Location:register_form.php?error_code=5");
        die();
    } elseif (checkNickname($nickname)) {
        header("Location:register_form.php?error_code=6");
        die();
    }

    if (empty($password)) {
        header("Location:register_form.php?error_code=7");
        die();
    } elseif (!preg_match('/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,20}$/', $password)) {
        header("Location:register_form.php?error_code=8");
        die();
    }

    register($username, $nickname, $password);

    include("include/opening.inc.php")
?>

    <h2>You have successfully registered an account!</h2>

<?php
include("include/closing.html");
?>
