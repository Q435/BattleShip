<?php

include("pdo.inc.php");

$USER_TABLE = 'users';
$PDO = getPDO();

function register($username, $nickname, $password) {
    global $PDO, $USER_TABLE;
    $password = password_hash($password, PASSWORD_BCRYPT);
    $sth = $PDO->query("INSERT INTO $USER_TABLE (username, nickname, password) VALUES ('$username', '$nickname', '$password')");
    return $sth->rowCount() > 0;
}

function checkUsername($username) {
    global $PDO, $USER_TABLE;
    $sth = $PDO->query("SELECT COUNT(*) FROM $USER_TABLE WHERE username = '$username'");
    return $sth->fetchColumn() > 0;
}

function checkNickname($nickname) {
    global $PDO, $USER_TABLE;
    $sth = $PDO->query("SELECT COUNT(*) FROM $USER_TABLE WHERE nickname = '$nickname'");
    return $sth->fetchColumn() > 0;
}

function checkPassword($username, $password) {
    global $PDO, $USER_TABLE;
    $result = $PDO->query("SELECT * FROM $USER_TABLE WHERE username = '$username'")->fetch();

    if ( $result && password_verify($password, $result['password']) )
        return $result;
    return false;
}

function getUserId($user) {
    return $user['Id'];
}

function getNickName($user) {
    return $user['nickname'];
}

function errorMsg($error_code) {
    $msg = "";
    switch ($error_code) {
        case 1:
            $msg = "username can not be empty";
            break;
        case 2:
            $msg = "username must contain numbers and letters and length between 5 and 30";
            break;
        case 3:
            $msg = "username already exists";
            break;
        case 4:
            $msg = "nickname can not be empty";
            break;
        case 5:
            $msg = "nickname contain numbers or letters and length between 5 and 30";
            break;
        case 6:
            $msg = "nickname already exists";
            break;
        case 7:
            $msg = "password can not be empty";
            break;
        case 8:
            $msg = "password too weak (must contain numbers, uppercase and lowercase letters and length between 8 and 30)";
            break;
        case 9:
            $msg = "Incorrect username or password";
            break;
    }
    return $msg;
}
