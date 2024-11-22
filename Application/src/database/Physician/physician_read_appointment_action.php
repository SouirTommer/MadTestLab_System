<?php
session_start();

require_once '../../connection/mysqli_conn.php';

require '../../Page/Account/auth.php';
check_labstaff_type('Physician');

// Function to get the LabStaffID from the AccountID
function getLabStaffID($conn, $accountId) {
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
$accountId = $_SESSION['accountId'];

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
    WHERE Appointments.LabStaffID = ?
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
$appointmentsJson = json_encode($appointments);

// Include the front-end file
include '../../Page/physician_appointment.php';
?>