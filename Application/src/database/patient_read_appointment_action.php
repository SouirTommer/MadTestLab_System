<?php
session_start();
require_once '../connection/mysqli_conn.php';

// Ensure the user is a patient
if ($_SESSION['role'] !== 'Patient') {
    header("Location: ../login.php");
    exit();
}

// Check if accountId is set in the session
if (!isset($_SESSION['accountId'])) {
    header("Location: ../login.php");
    exit();
}

// Get the AccountID from the session
$accountId = $_SESSION['accountId'];

// Fetch the PatientID based on the AccountID
$patientQuery = "SELECT PatientID FROM Patients WHERE AccountID = ?";
$stmt = $conn->prepare($patientQuery);
$stmt->bind_param('i', $accountId);
$stmt->execute();
$stmt->bind_result($patientID);
$stmt->fetch();
$stmt->close();

if (!$patientID) {
    header("Location: ../login.php");
    exit();
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
while ($physician = $physiciansResult->fetch_assoc()) {
    $physicians[] = $physician;
}

// Fetch all appointments for the logged-in patient including secretary's name
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
    WHERE Appointments.PatientID = ?
";
$stmt = $conn->prepare($appointmentsQuery);
$stmt->bind_param('i', $patientID);
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

// Include the front-end file
include '../patient_appointment.php';
?>