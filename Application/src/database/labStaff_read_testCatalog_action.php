<?php
session_start();
require_once '../connection/mysqli_conn.php';

// Ensure the user is a LabStaff
if ($_SESSION['role'] !== 'LabStaff') {
    header("Location: ../login.php");
    exit();
}

// Fetch all tests from the TestsCatalog
$testsQuery = "SELECT TestCode, TestName, Description, Price, TestType FROM TestsCatalog";
$testsResult = $conn->query($testsQuery);

$tests = [];
if ($testsResult->num_rows > 0) {
    while ($row = $testsResult->fetch_assoc()) {
        $tests[] = $row;
    }
}

$conn->close();
include '../labStaff_testCatalog.php';
?>