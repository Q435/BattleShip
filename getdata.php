<?php
    include ("include/pdo.inc.php");
    $pdo = getPDO();

    header('content-type:application/json;charset=utf8');

    //$result = mysql_query("select message,username from mymessage");

    $results = $pdo->query("SELECT message, username FROM mymessage")->fetchAll();

    echo json_encode($results);

?>
