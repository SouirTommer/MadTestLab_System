<?php
require_once './connection/mysqli_conn.php';
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
echo "Connected successfully to the database!!";
?>
