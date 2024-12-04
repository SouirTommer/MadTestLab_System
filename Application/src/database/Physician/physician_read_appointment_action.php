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
header(header: 'Access-Control-Allow-Credentials: true'); // Allow credentials (cookies) to be sent
header(header: 'Content-Type: application/json'); // Set the content type to JSON

require_once '../../connection/mysqli_conn_Physician.php';

require '../../Page/Account/auth.php';
// check_labstaff_type('Physician');
if (!isset($_COOKIE['accountId'])) {
    echo json_encode(['status' => 'error', 'message' => 'Not authenticated']);
    exit();
}
$accountId = $_COOKIE['accountId'];

// Function to get the LabStaffID from the AccountID
function getLabStaffID($conn, $accountId) {
    $labStaffID = null;
    $query = "SELECT LabStaffID FROM LabStaffs WHERE AccountID = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('i', $accountId);
    $stmt->execute();
    $stmt->bind_result($labStaffID);
    $stmt->fetch();
    $stmt->close();
    return $labStaffID;
}

// Get the AccountID from the session

// Get the LabStaffID using the AccountID
$labStaffID = getLabStaffID($conn, $accountId);

// Fetch patients for the form
$patients = [];

$patientsQuery = "SELECT PatientID, FirstName, LastName FROM Patients";
$patientsResult = $conn->query($patientsQuery);
while ($patient = $patientsResult->fetch_assoc()) {
    $patients[] = $patient;
}

// Fetch all appointments for the logged-in physician including secretary's name
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
    WHERE Appointments.LabStaffID = ? AND Appointments.AppointmentsStatus = 'Scheduled'
";
$stmt = $conn->prepare($appointmentsQuery);
$stmt->bind_param('i', $labStaffID);
$stmt->execute();
$appointmentsResult = $stmt->get_result();

$appointments = [];
if ($appointmentsResult->num_rows > 0) {
    while ($row = $appointmentsResult->fetch_assoc()) {
        $appointments[] = $row;
    }
}

$stmt->close();
$conn->close();

//json object
header('Content-Type: application/json');
echo json_encode([
    'appointments' => $appointments,
    'patients' => $patients
]);
