<?php
session_start();
require_once '../connection/mysqli_conn.php';

// Ensure the user is a physician
if ($_SESSION['role'] !== 'LabStaff') {
    echo json_encode(['status' => 'error', 'message' => 'Unauthorized']);
    exit();
}

// Function to get the LabStaffID from the AccountID
function getLabStaffID($conn, $accountId) {
    $query = "SELECT LabStaffID FROM LabStaffs WHERE AccountID = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('i', $accountId);
    $stmt->execute();
    $stmt->bind_result($labStaffID);
    $stmt->fetch();
    $stmt->close();
    return $labStaffID;
}

// Get the LabStaffID from the session
$accountId = $_SESSION['accountId'];
$labStaffID = getLabStaffID($conn, $accountId);

// Get the form data
$appointmentID = $_POST['appointmentID'];
$patientName = $_POST['patient'];
$secretaryName = $_POST['secretary'];
$testCode = $_POST['testCode'];
$orderDateTime = $_POST['orderDateTime'];
$orderStatus = $_POST['orderStatus'];

// Fetch the PatientID based on the patient's name
$patientQuery = "SELECT PatientID FROM Patients WHERE CONCAT(FirstName, ' ', LastName) = ?";
$stmt = $conn->prepare($patientQuery);
$stmt->bind_param('s', $patientName);
$stmt->execute();
$stmt->bind_result($patientID);
$stmt->fetch();
$stmt->close();

if (!$patientID) {
    echo json_encode(['status' => 'error', 'message' => 'Patient not found']);
    exit();
}

// Fetch the SecretaryID based on the secretary's name
$secretaryQuery = "SELECT SecretaryID FROM Secretaries WHERE CONCAT(FirstName, ' ', LastName) = ?";
$stmt = $conn->prepare($secretaryQuery);
$stmt->bind_param('s', $secretaryName);
$stmt->execute();
$stmt->bind_result($secretaryID);
$stmt->fetch();
$stmt->close();

if (!$secretaryID) {
    echo json_encode(['status' => 'error', 'message' => 'Secretary not found']);
    exit();
}

// Insert the order into the database
$query = "INSERT INTO Orders (PatientID, LabStaffID, SecretaryID, TestCode, OrderDateTime, OrderStatus) VALUES (?, ?, ?, ?, ?, ?)";
$stmt = $conn->prepare($query);
$stmt->bind_param('iiiiss', $patientID, $labStaffID, $secretaryID, $testCode, $orderDateTime, $orderStatus);

if ($stmt->execute()) {
    // Fetch the inserted order details
    $orderID = $stmt->insert_id;
    $orderQuery = "SELECT * FROM Orders WHERE OrderID = ?";
    $stmt = $conn->prepare($orderQuery);
    $stmt->bind_param('i', $orderID);
    $stmt->execute();
    $orderResult = $stmt->get_result();
    $orderData = $orderResult->fetch_assoc();

    echo json_encode($orderData);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Error creating order: ' . $stmt->error]);
}


$stmt->close();
$conn->close();
?>