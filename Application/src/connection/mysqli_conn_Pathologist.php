<?php
$servername = "proxysql";
$username = "pathologist";
$password = "pathologist";
$dbname = "MadTestLab";
$port = 6033;

$conn = new mysqli($servername, $username, $password, $dbname, $port);
mysqli_set_charset($conn,"utf8");

$conn->query("SET block_encryption_mode = 'aes-256-cbc'");
?>