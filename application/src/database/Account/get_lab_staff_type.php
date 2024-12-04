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

require_once '../../connection/mysqli_conn_Physician.php';
require '../../Page/Account/auth.php';
check_labstaff_type('Physician');

$accountId = $_COOKIE['accountId'];


function getLabStaffID($conn, $accountId) {
    $labStaffID = null; // Initialize the variable

    $query = "SELECT LabStaffID FROM LabStaffs WHERE AccountID = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('i', $accountId);
    $stmt->execute();
    $stmt->bind_result($labStaffID);
    $stmt->fetch();
    $stmt->close();
    return $labStaffID;
}

function getLabStaffType($conn, $labStaffID) {
    $labStaffType = null; // Initialize the variable

    $query = "SELECT LabStaffType FROM LabStaffs WHERE LabStaffID = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('i', $labStaffID);
    $stmt->execute();
    $stmt->bind_result($labStaffType);
    $stmt->fetch();
    $stmt->close();
    return $labStaffType;
}

$labStaffID = getLabStaffID($conn, $accountId);
$result = getLabStaffType($conn, $labStaffID);

if ($result === null) {
    echo json_encode(['status' => 'error', 'message' => 'Lab staff type not found']);
} else {
    echo json_encode(['status' => 'success', 'labStaffType' => $result]);
}

$conn->close();
?>