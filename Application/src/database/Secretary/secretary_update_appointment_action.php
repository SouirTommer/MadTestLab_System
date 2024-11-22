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
            $response['message'] = 'Error: ' . $stmt->error;
        }

        $stmt->close();
    } else {
        $response['status'] = 'error';
        $response['message'] = 'Missing form data.';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Update Appointment</title>
</head>
<body>
    <?php if (!empty($response)): ?>
        <div>
            <p><?php echo $response['message']; ?></p>
            <pre><?php echo json_encode($response, JSON_PRETTY_PRINT); ?></pre>
        </div>
    <?php endif; ?>
    <form action="secretary_read_appointment_action.php" method="get">
        <button type="submit">Back</button>
    </form>
</body>
</html>