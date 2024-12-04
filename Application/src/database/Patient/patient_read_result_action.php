<?php
session_start();
require_once '../../connection/mysqli_conn_Patient.php';
require '../../Page/Account/auth.php';

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

// check_role(['Patient']);
// Check if the user is authenticated
if (!isset($_COOKIE['accountId'])) {
    echo json_encode(['status' => 'error', 'message' => 'Not authenticated']);
    exit();
}

// Get the AccountID from the cookies
$accountId = $_COOKIE['accountId'];
$patientId = $_COOKIE['patientId'];

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
$stmt->bind_param('i', $patientId);
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


if (!empty($results)) {
    echo json_encode(['status' => 'success', 'data' => $results]);
} else {
    echo json_encode(['status' => 'error', 'message' => 'No results found']);
}
?>