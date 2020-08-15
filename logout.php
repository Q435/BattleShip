<?php
    session_start();

    include("include/util.inc.php");

    sqlLogout($_SESSION['user_id']);
    session_unset();
    session_destroy();
    header("Location: index.php");
?>
