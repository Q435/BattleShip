<?php

    include ("include/pdo.inc.php");
    $pdo = getPDO();

    $message = $_POST[message];
    $username = $_POST[username];

    $country = $pdo->query("INSERT INTO mymessage (message, username) VALUES ('$message','$username')")->fetch();

    echo "OK";

?>
