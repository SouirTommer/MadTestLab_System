<?php
$servername = "proxysql";
$username = "admin";
$password = "admin";
$dbname = "MadTestLab";
$port = 6033;

$conn = new mysqli($servername, $username, $password, $dbname, $port);
mysqli_set_charset($conn,"utf8");

?>