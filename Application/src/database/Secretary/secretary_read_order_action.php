<?php
session_start();

require_once '../../connection/mysqli_conn_Secretary.php';

require '../../Page/Account/auth.php';
check_role(['Secretary']);

// Fetch all orders
$ordersQuery = "
    SELECT 
        Orders.OrderID,
        Patients.FirstName AS PatientFirstName,
        Patients.LastName AS PatientLastName,
        LabStaffs.FirstName AS LabStaffFirstName,
        LabStaffs.LastName AS LabStaffLastName,
        Secretaries.FirstName AS SecretaryFirstName,
        Secretaries.LastName AS SecretaryLastName,
        TestsCatalog.TestName,
        Orders.OrderDateTime,
        Orders.OrderStatus
    FROM Orders
    JOIN Patients ON Orders.PatientID = Patients.PatientID
    JOIN LabStaffs ON Orders.LabStaffID = LabStaffs.LabStaffID
    JOIN Secretaries ON Orders.SecretaryID = Secretaries.SecretaryID
    JOIN TestsCatalog ON Orders.TestCode = TestsCatalog.TestCode

";
$ordersResult = $conn->query($ordersQuery);

$orders = [];
if ($ordersResult->num_rows > 0) {
    while ($row = $ordersResult->fetch_assoc()) {
        $orders[] = $row;
    }
}

// Fetch the first 6 insurances
$insurancesQuery = "SELECT InsuranceID, InsuranceName, InsuranceAmount FROM Insurances LIMIT 6";
$insurancesResult = $conn->query($insurancesQuery);

$insurances = [];
if ($insurancesResult->num_rows > 0) {
    while ($row = $insurancesResult->fetch_assoc()) {
        $insurances[] = $row;
    }
}

// Fetch all tests catalog
$testsCatalogQuery = "
    SELECT 
        TestCode,
        TestName,
        Description,
        Price,
        TestType
    FROM TestsCatalog
";
$testsCatalogResult = $conn->query($testsCatalogQuery);

$testsCatalog = [];
if ($testsCatalogResult->num_rows > 0) {
    while ($row = $testsCatalogResult->fetch_assoc()) {
        $testsCatalog[] = $row;
    }
}

$conn->close();

// Return JSON response
header('Content-Type: application/json');
echo json_encode([
    'orders' => $orders,
    'insurances' => $insurances,
    'testsCatalog' => $testsCatalog
]);
?>