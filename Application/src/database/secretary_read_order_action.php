<?php
session_start();
require_once '../connection/mysqli_conn_Secretary.php';

// Ensure the user is a secretary
if ($_SESSION['role'] !== 'Secretary') {
    header("Location: ../login.php");
    exit();
}

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
        Orders.OrderDateTime,
        Orders.OrderStatus,
        TestsCatalog.TestName,
        TestsCatalog.Price AS TestPrice
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

// Convert the PHP arrays to JSON objects
$ordersJson = json_encode($orders);
$insurancesJson = json_encode($insurances);

$conn->close();

include '../secretary_order.php';
?>