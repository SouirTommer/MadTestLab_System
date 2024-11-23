<?php
session_start();

require_once '../../connection/mysqli_conn.php';

require '../../Page/Account/auth.php';
check_role(['Secretary']);

$response = [];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['appointmentID']) && isset($_POST['datetime']) && isset($_POST['status']) && isset($_POST['physician'])) {
        $appointmentID = $_POST['appointmentID'];
        $datetime = $_POST['datetime'];
        $status = $_POST['status'];
        $physician = $_POST['physician'];

        $query = "UPDATE Appointments SET AppointmentDateTime = ?, AppointmentsStatus = ?, LabStaffID = ? WHERE AppointmentID = ?";
        $stmt = $conn->prepare($query);
        if ($stmt === false) {
            $response['status'] = 'error';
            $response['message'] = 'Prepare failed: ' . $conn->error;
        } else {
            $stmt->bind_param('ssii', $datetime, $status, $physician, $appointmentID);
            if ($stmt->execute()) {
                $response['status'] = 'success';
                $response['message'] = 'Appointment updated successfully';
                $response['appointment'] = [
                    'AppointmentID' => $appointmentID,
                    'AppointmentDateTime' => $datetime,
                    'AppointmentsStatus' => $status,
                    'LabStaffID' => $physician
                ];
            } else {
                $response['status'] = 'error';
                $response['message'] = 'Execute failed: ' . $stmt->error;
            }
            $stmt->close();
        }
    } else {
        $response['status'] = 'error';
        $response['message'] = 'Missing form data.';
    }
} else {
    $response['status'] = 'error';
    $response['message'] = 'Invalid request method.';
}

$conn->close();

// Return JSON response
header('Content-Type: application/json');
echo json_encode($response);
?>