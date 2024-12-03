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

if (!isset($_COOKIE['accountId'])) {
    echo json_encode(['status' => 'error', 'message' => 'Not authenticated']);
    exit();
}
$accountId = $_COOKIE['accountId'];

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
while ($physician = $physiciansResult->fetch_assoc()) {
    $physicians[] = $physician;
}

// Fetch all appointments including secretary's name
$appointmentsQuery = "
    SELECT 
        Appointments.AppointmentID,
        Patients.FirstName AS PatientFirstName,
        Patients.LastName AS PatientLastName,
        LabStaffs.FirstName AS PhysicianFirstName,
        LabStaffs.LastName AS PhysicianLastName,
        Secretaries.FirstName AS SecretaryFirstName,
        Secretaries.LastName AS SecretaryLastName,
        Appointments.AppointmentDateTime,
        Appointments.AppointmentsStatus,
        Appointments.LabStaffID
    FROM Appointments
    JOIN Patients ON Appointments.PatientID = Patients.PatientID
    JOIN LabStaffs ON Appointments.LabStaffID = LabStaffs.LabStaffID
    JOIN Secretaries ON Appointments.SecretaryID = Secretaries.SecretaryID
";
$appointmentsResult = $conn->query($appointmentsQuery);

$appointments = [];
if ($appointmentsResult->num_rows > 0) {
    while ($row = $appointmentsResult->fetch_assoc()) {
        $appointments[] = $row;
    }
}

$conn->close();

// Return JSON data
header('Content-Type: application/json');


echo json_encode([
    'patients' => $patients,
    'physicians' => $physicians,
    'appointments' => $appointments
]);


?>