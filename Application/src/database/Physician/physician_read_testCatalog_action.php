<?php
session_start();

require_once '../../connection/mysqli_conn_Physician.php';

require '../../Page/Account/auth.php';
// check_labstaff_type('Physician');

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

// Return JSON response
header('Content-Type: application/json');
echo json_encode($tests);
?>