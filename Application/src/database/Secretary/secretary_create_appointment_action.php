<?php

require_once '../../connection/mysqli_conn.php';
session_start();


require '../../Page/Account/auth.php';
check_role(['Secretary']);

$response = [];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['patient']) && isset($_POST['physician']) && isset($_POST['datetime']) && isset($_SESSION['accountId'])) {
        $patientID = $_POST['patient'];
        $physicianID = $_POST['physician'];
        $datetime = $_POST['datetime'];
        $accountId = $_SESSION['accountId']; // Use accountId from session

        // Fetch SecretaryID based on accountId
        $secretaryQuery = "SELECT SecretaryID FROM Secretaries WHERE AccountID = ?";
        $stmt = $conn->prepare($secretaryQuery);
        $stmt->bind_param('i', $accountId);
        $stmt->execute();
        $stmt->bind_result($secretaryID);
        $stmt->fetch();
        $stmt->close();

        if ($secretaryID) {
            $query = "INSERT INTO Appointments (PatientID, SecretaryID, LabStaffID, AppointmentDateTime, AppointmentsStatus) VALUES (?, ?, ?, ?, 'Scheduled')";
            $stmt = $conn->prepare($query);
            $stmt->bind_param('iiis', $patientID, $secretaryID, $physicianID, $datetime);

            if ($stmt->execute()) {
                $response['status'] = 'success';
                $response['message'] = 'Appointment created successfully';
                $response['appointment'] = [
                    'PatientID' => $patientID,
                    'SecretaryID' => $secretaryID,
                    'LabStaffID' => $physicianID,
                    'AppointmentDateTime' => $datetime,
                    'AppointmentsStatus' => 'Scheduled'
                ];
            } else {
                $response['status'] = 'error';
                $response['message'] = 'Error: ' . $stmt->error;
            }

            $stmt->close();
        } else {
            $response['status'] = 'error';
            $response['message'] = 'Secretary not found.';
        }
    } else {
        $response['status'] = 'error';
        $response['message'] = 'Missing form data or session data.';
        $response['post_data'] = $_POST;
        $response['session_data'] = $_SESSION;
    }
}

// Fetch patients and physicians for the form
$patients = [];
$physicians = [];

$patientsQuery = "SELECT PatientID, FirstName, LastName FROM Patients";
$patientsResult = $conn->query($patientsQuery);
while ($patient = $patientsResult->fetch_assoc()) {
    $patients[] = $patient;
}

$physiciansQuery = "SELECT LabStaffID, FirstName, LastName FROM LabStaffs WHERE LabStaffType = 'Physician'";
$physiciansResult = $conn->query($physiciansQuery);

// Debug: Check if the query returns any results
if ($physiciansResult->num_rows > 0) {
    while ($physician = $physiciansResult->fetch_assoc()) {
        $physicians[] = $physician;
    }
} else {
    // Debug: Print an error message if no physicians are found
    echo "No physicians found.";
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Appointment</title>
</head>
<body>
    <?php if ($response['status'] == 'success'): ?>
        <h2><?php echo $response['message']; ?></h2>
        <pre><?php echo json_encode($response['appointment'], JSON_PRETTY_PRINT); ?></pre>
    <?php else: ?>
        <h2><?php echo $response['message']; ?></h2>
        <?php if (isset($response['post_data'])): ?>
            <pre>POST data: <?php echo json_encode($response['post_data'], JSON_PRETTY_PRINT); ?></pre>
        <?php endif; ?>
        <?php if (isset($response['session_data'])): ?>
            <pre>Session data: <?php echo json_encode($response['session_data'], JSON_PRETTY_PRINT); ?></pre>
        <?php endif; ?>
    <?php endif; ?>
    <button onclick="window.location.href='secretary_read_appointment_action.php'">Go Back</button>
</body>
</html>