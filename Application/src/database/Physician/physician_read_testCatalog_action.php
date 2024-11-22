<?php
session_start();

require_once '../../connection/mysqli_conn.php';

require '../../Page/Account/auth.php';
check_labstaff_type('Physician');

// Fetch all tests from the TestsCatalog
$testsQuery = "SELECT TestCode, TestName, Description, Price, TestType FROM TestsCatalog";
$testsResult = $conn->query($testsQuery);

$tests = [];
if ($testsResult->num_rows > 0) {
    while ($row = $testsResult->fetch_assoc()) {
        $tests[] = $row;
    }
}

//json object
$testsJson = json_encode($tests);

$conn->close();
include '../../Page/physician_testCatalog.php';
?>