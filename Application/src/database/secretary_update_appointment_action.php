<?php
require_once '../connection/mysqli_conn.php';
session_start();

// 檢查使用者是否已登入
if (!isset($_SESSION['username'])) {
    header("Location: ../login.php");
    exit();
}

$response = [];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['appointmentID']) && isset($_POST['datetime']) && isset($_POST['status'])) {
        $appointmentID = $_POST['appointmentID'];
        $datetime = $_POST['datetime'];
        $status = $_POST['status'];

        $query = "UPDATE Appointments SET AppointmentDateTime = ?, AppointmentsStatus = ? WHERE AppointmentID = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param('ssi', $datetime, $status, $appointmentID);

        if ($stmt->execute()) {
            $response['status'] = 'success';
            $response['message'] = 'Appointment updated successfully';
            $response['appointment'] = [
                'AppointmentID' => $appointmentID,
                'AppointmentDateTime' => $datetime,
                'AppointmentsStatus' => $status
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

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Appointment</title>
</head>
<body>
    <?php if ($response['status'] == 'success'): ?>
        <h2><?php echo $response['message']; ?></h2>
        <pre><?php echo json_encode($response['appointment'], JSON_PRETTY_PRINT); ?></pre>
    <?php else: ?>
        <h2><?php echo $response['message']; ?></h2>
    <?php endif; ?>
    <button onclick="window.location.href='secretary_appointment_action.php'">Go Back</button>
</body>
</html>