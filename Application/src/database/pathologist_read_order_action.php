<?php
session_start();
require_once '../connection/mysqli_conn.php';

// Ensure the user is a pathologist
if ($_SESSION['role'] !== 'LabStaff' || $_SESSION['labStaffType'] !== 'Pathologist') {
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
        TestsCatalog.TestName
    FROM Orders
    JOIN Patients ON Orders.PatientID = Patients.PatientID
    JOIN LabStaffs ON Orders.LabStaffID = LabStaffs.LabStaffID
    JOIN Secretaries ON Orders.SecretaryID = Secretaries.SecretaryID
    JOIN TestsCatalog ON Orders.TestCode = TestsCatalog.TestCode
    WHERE Orders.OrderStatus = 'Pending'
";
$ordersResult = $conn->query($ordersQuery);

$orders = [];
if ($ordersResult->num_rows > 0) {
    while ($row = $ordersResult->fetch_assoc()) {
        $orders[] = $row;
    }
}

$conn->close();

//json object
$ordersJson = json_encode($orders);

include '../pathologist_order.php';
?>