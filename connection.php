<?php
session_start();
error_reporting(0); //。。加这个就不显示提示
$servername = "127.0.0.1";
$username = "root";
$password = "root";
//$server_db_name = "app_phptest2017";

$con = mysql_connect($servername,$username,$password);

if (!$con)
{
    die('Could not connect: ' . mysql_error());
}

mysql_query("set names 'utf8'");

mysql_select_db("battleship", $con);

$sql = "CREATE TABLE IF NOT EXISTS `mymessage` (  
  messageID int NOT NULL AUTO_INCREMENT,
  username char(30) NOT NULL,
  message char(30) NOT NULL,  
  PRIMARY KEY (messageID) 
) ENGINE=InnoDB DEFAULT CHARSET=gbk; "  ;

mysql_query($sql);

?>
