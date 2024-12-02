<?php
session_start();

require_once '../../connection/mysqli_conn_Patient.php';
require '../../Page/Account/auth.php';
check_role(['Patient']);

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
    header("Location: ../../Page/Account/login.php");
    exit();
}

// Fetch all results for the logged-in patient
$resultsQuery = "
    SELECT 
        Results.ResultID,
        Results.OrderID,
        Results.ReportURL,
        Results.Interpretation,
        Results.ResultDateTime,
        Results.ResultStatus,
        Patients.FirstName AS PatientFirstName,
        Patients.LastName AS PatientLastName,
        LabStaffs.FirstName AS LabStaffFirstName,
        LabStaffs.LastName AS LabStaffLastName
    FROM Results
    JOIN Orders ON Results.OrderID = Orders.OrderID
    JOIN Patients ON Orders.PatientID = Patients.PatientID
    JOIN LabStaffs ON Results.LabStaffID = LabStaffs.LabStaffID
    WHERE Orders.PatientID = ?
";
$stmt = $conn->prepare($resultsQuery);
$stmt->bind_param('i', $patientID);
$stmt->execute();
$resultsResult = $stmt->get_result();

$results = [];
if ($resultsResult->num_rows > 0) {
    while ($row = $resultsResult->fetch_assoc()) {
        $results[] = $row;
    }
}

$stmt->close();
$conn->close();

// Return JSON response
header('Content-Type: application/json');
echo json_encode($results);
?>