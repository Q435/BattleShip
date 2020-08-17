<?php

    session_start();

    include("include/util.inc.php");


    $res = "false";
    if (sqlINSERT($USER_TABLE, array("username" => "123", "nickname" => "nick"))) {
        $res = "true";
    }

?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Game_id</title>
</head>
<body>

<h1><?php print_r($res) ?></h1>
</body>
</html>

