<?php
session_start();

require_once '../../connection/mysqli_conn_Physician.php';

require '../../Page/Account/auth.php';
check_labstaff_type('Physician');

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

// Function to get the PatientID from the patient's name
function getPatientID($conn, $patientName) {
    $query = "SELECT PatientID FROM Patients WHERE CONCAT(FirstName, ' ', LastName) = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('s', $patientName);
    $stmt->execute();
    $stmt->bind_result($patientID);
    $stmt->fetch();
    $stmt->close();
    return $patientID;
}

// Function to get the SecretaryID from the secretary's name
function getSecretaryID($conn, $secretaryName) {
    $query = "SELECT SecretaryID FROM Secretaries WHERE CONCAT(FirstName, ' ', LastName) = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('s', $secretaryName);
    $stmt->execute();
    $stmt->bind_result($secretaryID);
    $stmt->fetch();
    $stmt->close();
    return $secretaryID;
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
$patientID = getPatientID($conn, $patientName);

// Fetch the SecretaryID based on the secretary's name
$secretaryID = getSecretaryID($conn, $secretaryName);

$query = "INSERT INTO Orders (PatientID, LabStaffID, SecretaryID, TestCode, OrderDateTime, OrderStatus) VALUES (?, ?, ?, ?, ?, ?)";
$stmt = $conn->prepare($query);
$stmt->bind_param('iiiiss', $patientID, $labStaffID, $secretaryID, $testCode, $orderDateTime, $orderStatus);

$response = [];

if ($stmt->execute()) {
    // Fetch the inserted order details
    $orderID = $stmt->insert_id;
    $orderQuery = "SELECT * FROM Orders WHERE OrderID = ?";
    $stmt = $conn->prepare($orderQuery);
    $stmt->bind_param('i', $orderID);
    $stmt->execute();
    $orderResult = $stmt->get_result();
    $orderData = $orderResult->fetch_assoc();

    // Update the appointment status to 'Completed'
    $updateAppointmentQuery = "UPDATE Appointments SET AppointmentsStatus = 'Completed' WHERE AppointmentID = ?";
    $updateStmt = $conn->prepare($updateAppointmentQuery);
    $updateStmt->bind_param('i', $appointmentID);
    $updateStmt->execute();
    $updateStmt->close();

    $response['status'] = 'success';
    $response['message'] = 'Order created and appointment status updated successfully';
    $response['order'] = $orderData;
} else {
    $response['status'] = 'error';
    $response['message'] = 'Error creating order: ' . $stmt->error;
}

$stmt->close();
$conn->close();

// Return JSON response
header('Content-Type: application/json');
echo json_encode($response);
?>