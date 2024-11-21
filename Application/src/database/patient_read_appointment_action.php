<?php
session_start();

require '../auth.php'; // Update the path to the correct location of auth.php
check_login();

// Ensure the user is a patient
if ($_SESSION['role'] !== 'Patient') {
    header("Location: ../login.php");
    exit();
}

// Fetch appointments for the logged-in patient
require_once '../connection/mysqli_conn.php';

$response = [];

try {
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

    if ($conn->connect_error) {
        throw new Exception("Connection failed: " . $conn->connect_error);
    }

    $accountId = $_SESSION['accountId'];
    $query = "
        SELECT 
            Appointments.AppointmentID,
            Appointments.AppointmentDateTime,
            Appointments.AppointmentsStatus,
            Secretaries.FirstName AS SecretaryFirstName,
            Secretaries.LastName AS SecretaryLastName,
            LabStaffs.FirstName AS PhysicianFirstName,
            LabStaffs.LastName AS PhysicianLastName
        FROM Appointments
        JOIN Secretaries ON Appointments.SecretaryID = Secretaries.SecretaryID
        JOIN LabStaffs ON Appointments.LabStaffID = LabStaffs.LabStaffID
        WHERE Appointments.PatientID = (SELECT PatientID FROM Patients WHERE AccountID = ?)
        AND LabStaffs.LabStaffType = 'Physician'
    ";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('i', $accountId);
    $stmt->execute();
    $result = $stmt->get_result();

    $appointments = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $appointments[] = $row;
        }
    }

    $stmt->close();
    $conn->close();

    // Create an associative array for JSON output
    $data = array(
        "status" => "success",
        "message" => "Data processed successfully",
        "appointments" => $appointments
    );

    // Convert the array to a JSON string
    $json_data = json_encode($data, JSON_PRETTY_PRINT);

    // Print the JSON string
    echo "<pre>" . $json_data . "</pre>";

} catch (Exception $e) {
    $response['status'] = 'error';
    $response['message'] = $e->getMessage();
    echo json_encode($response);
}
?>