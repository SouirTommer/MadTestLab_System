<?php
session_start();

require_once '../../connection/mysqli_conn.php';

require '../../Page/Account/auth.php';
check_labstaff_type('Pathologist');

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

// Fetch all insurances
$insurancesQuery = "
    SELECT 
        InsuranceID,
        InsuranceName,
        InsuranceAmount,
        InsuranceDetails,
        InsuranceStatus
    FROM Insurances
";
$insurancesResult = $conn->query($insurancesQuery);

$insurances = [];
if ($insurancesResult->num_rows > 0) {
    while ($row = $insurancesResult->fetch_assoc()) {
        $insurances[] = $row;
    }
}

$conn->close();

// Combine orders and insurances into one response
$response = [
    'orders' => $orders,
    'insurances' => $insurances
];

//json object
header('Content-Type: application/json');
echo json_encode($response);

?>