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

require_once '../../connection/mysqli_conn_Pathologist.php';

require '../../Page/Account/auth.php';
// check_labstaff_type('Pathologist');

if (!isset($_COOKIE['accountId'])) {
    echo json_encode(['status' => 'error', 'message' => 'Not authenticated']);
    exit();
}
$accountId = $_COOKIE['accountId'];

// Get the account ID from the session

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