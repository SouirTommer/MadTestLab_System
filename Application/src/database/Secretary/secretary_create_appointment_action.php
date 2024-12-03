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
header('Access-Control-Allow-Credentials: true'); // Allow credentials (cookies) to be sent
header('Content-Type: application/json'); // Set the content type to JSON

require_once '../../connection/mysqli_conn_Secretary.php';
require '../../Page/Account/auth.php';
// check_role(['Secretary']);
if (!isset($_COOKIE['accountId'])) {
    echo json_encode(['status' => 'error', 'message' => 'Not authenticated']);
    exit();
}
$accountId = $_COOKIE['accountId'];

$response = ['status' => 'error', 'message' => 'An error occurred.'];

// Check if form data is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $patientID = $_POST['patient'];
    $physicianID = $_POST['physician'];
    $appointmentDateTime = $_POST['datetime'];
    $secretaryID = $accountId; // Assuming secretary ID is stored in session

    // Insert the new appointment into the database
    $insertQuery = "
        INSERT INTO Appointments (PatientID, LabStaffID, AppointmentDateTime, SecretaryID, AppointmentsStatus)
        VALUES (?, ?, ?, ?, 'Scheduled')
    ";
    $stmt = $conn->prepare($insertQuery);
    $stmt->bind_param('iisi', $patientID, $physicianID, $appointmentDateTime, $secretaryID);

    if ($stmt->execute()) {
        $response['status'] = 'success';
        $response['message'] = 'Appointment created successfully.';
        $response['appointment'] = [
            'AppointmentID' => $stmt->insert_id,
            'PatientID' => $patientID,
            'LabStaffID' => $physicianID,
            'AppointmentDateTime' => $appointmentDateTime,
            'SecretaryID' => $secretaryID,
            'AppointmentsStatus' => 'Scheduled'
        ];
    } else {
        $response['message'] = 'Failed to create appointment.';
    }

    $stmt->close();
} else {
    $response['message'] = 'Invalid request method.';
}

$conn->close();

// Redirect back to the secretary_appointment.php page
// header('Location: ../../Page/secretary_appointment.php');


echo json_encode($response);
