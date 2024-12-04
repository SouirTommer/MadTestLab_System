<?php
session_start();
$allowed_origins = [
    'http://localhost:5173',
    'http://localhost:3000'
];
if (isset($_SERVER['HTTP_ORIGIN']) && in_array($_SERVER['HTTP_ORIGIN'], $allowed_origins)) {
    header('Access-Control-Allow-Origin: ' . $_SERVER['HTTP_ORIGIN']);
} else {
    header('Access-Control-Allow-Origin: ' . $allowed_origins[0]); // Default to the first allowed origin
}
header('Access-Control-Allow-Methods: GET, POST, OPTIONS'); // Allow specific HTTP methods
header('Access-Control-Allow-Headers: Content-Type, Accept'); // Allow specific headers
header(header: 'Access-Control-Allow-Credentials: true'); // Allow credentials (cookies) to be sent
header(header: 'Content-Type: application/json'); // Set the content type to JSON

require_once '../../connection/mysqli_conn_Pathologist.php';
require '../../Page/Account/auth.php';

check_labstaff_type('Pathologist');
check_labstaff_type('Pathologist');
if (!isset($_COOKIE['accountId'])) {
    echo json_encode(['status' => 'error', 'message' => 'Not authenticated']);
    exit();
}
$accountId = $_COOKIE['accountId'];

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
    WHERE Orders.OrderStatus = 'Paid'
";
$ordersResult = $conn->query($ordersQuery);

if ($ordersResult === false) {
    error_log('Error executing orders query: ' . $conn->error);
    echo json_encode(['status' => 'error', 'message' => 'Error fetching orders']);
    exit;
}

$orders = [];
if ($ordersResult->num_rows > 0) {
    while ($row = $ordersResult->fetch_assoc()) {
        $orders[] = $row;
    }
} else {
    error_log('No orders found with status Paid');
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

if ($insurancesResult === false) {
    error_log('Error executing insurances query: ' . $conn->error);
    echo json_encode(['status' => 'error', 'message' => 'Error fetching insurances']);
    exit;
}

$insurances = [];
if ($insurancesResult->num_rows > 0) {
    while ($row = $insurancesResult->fetch_assoc()) {
        $insurances[] = $row;
    }
} else {
    error_log('No insurances found');
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
