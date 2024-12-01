<?php
session_start();

require_once '../../connection/mysqli_conn.php';

require '../../Page/Account/auth.php';
check_role(['Secretary']);

$response = ['status' => 'error', 'message' => 'An error occurred.'];

// Check if form data is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $patientID = $_POST['patient'];
    $physicianID = $_POST['physician'];
    $appointmentDateTime = $_POST['datetime'];
    $secretaryID = $_SESSION['accountId']; // Assuming secretary ID is stored in session

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
header('Location: ../../Page/secretary_appointment.php');

// Return JSON response
// header('Content-Type: application/json');
// echo json_encode($response);
?>