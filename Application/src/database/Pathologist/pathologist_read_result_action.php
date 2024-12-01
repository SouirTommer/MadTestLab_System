<?php
session_start();

require_once '../../connection/mysqli_conn.php';

require '../../Page/Account/auth.php';
check_labstaff_type('Pathologist');

// Get the account ID from the session
$accountId = $_SESSION['accountId'];

// Fetch the LabStaffID based on the AccountID
$query = "SELECT LabStaffID FROM LabStaffs WHERE AccountID = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param('i', $accountId);
$stmt->execute();
$stmt->bind_result($labStaffID);
$stmt->fetch();
$stmt->close();

// Fetch all results for the specific LabStaffID
$resultsQuery = "
    SELECT 
        Results.ResultID,
        Orders.OrderID,
        Patients.FirstName AS PatientFirstName,
        Patients.LastName AS PatientLastName,
        LabStaffs.FirstName AS LabStaffFirstName,
        LabStaffs.LastName AS LabStaffLastName,
        Results.ReportURL,
        Results.Interpretation,
        Results.ResultDateTime,
        Results.ResultStatus
    FROM Results
    JOIN Orders ON Results.OrderID = Orders.OrderID
    JOIN Patients ON Orders.PatientID = Patients.PatientID
    JOIN LabStaffs ON Results.LabStaffID = LabStaffs.LabStaffID
    WHERE Results.LabStaffID = ?
";
$stmt = $conn->prepare($resultsQuery);
$stmt->bind_param('i', $labStaffID);
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