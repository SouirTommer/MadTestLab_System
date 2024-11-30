<?php
session_start();
require_once '../connection/mysqli_conn_Physician.php';

// Ensure the user is a physician
if ($_SESSION['role'] !== 'LabStaff') {
    echo json_encode(['status' => 'error', 'message' => 'Unauthorized']);
    exit();
}

// Get the form data
$appointmentID = $_POST['appointmentID'];
$appointmentStatus = $_POST['appointmentStatus'];

// Update the appointment status in the database
$query = "UPDATE Appointments SET AppointmentStatus = ? WHERE AppointmentID = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param('si', $appointmentStatus, $appointmentID);

if ($stmt->execute()) {
    $_SESSION['message'] = 'Appointment status updated successfully!';
} else {
    $_SESSION['message'] = 'Error updating appointment status: ' . $stmt->error;
}

$stmt->close();
$conn->close();

header("Location: physician_appointment.php");
exit();
?>