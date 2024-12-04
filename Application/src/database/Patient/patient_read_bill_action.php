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

require_once '../../connection/mysqli_conn_Patient.php';
require '../../Page/Account/auth.php';
// check_role(['Patient']);

// Check if the user is authenticated
if (!isset($_COOKIE['accountId'])) {
    echo json_encode(['status' => 'error', 'message' => 'Not authenticated']);
    exit();
}

// Get the AccountID from the cookies
$accountId = $_COOKIE['accountId'];

// Fetch the PatientID based on the AccountID
$patientQuery = "SELECT PatientID FROM Patients WHERE AccountID = ?";
$stmt = $conn->prepare($patientQuery);
$stmt->bind_param('i', $accountId);
$stmt->execute();
$stmt->bind_result($patientID);
$stmt->fetch();
$stmt->close();

// if (!$patientID) {
//     header("Location: ../login.php");
//     exit();
// }

// Fetch all bills for the logged-in patient
$billsQuery = "
    SELECT 
        Bills.BillID,
        Orders.OrderID,
        Patients.FirstName AS PatientFirstName,
        Patients.LastName AS PatientLastName,
        LabStaffs.FirstName AS LabStaffFirstName,
        LabStaffs.LastName AS LabStaffLastName,
        Secretaries.FirstName AS SecretaryFirstName,
        Secretaries.LastName AS SecretaryLastName,
        TestsCatalog.TestName,
        Bills.Amount,
        Bills.PaymentStatus,
        Bills.BillDateTime,
        Insurances.InsuranceName
    FROM Bills
    JOIN Orders ON Bills.OrderID = Orders.OrderID
    JOIN Patients ON Orders.PatientID = Patients.PatientID
    JOIN LabStaffs ON Orders.LabStaffID = LabStaffs.LabStaffID
    JOIN Secretaries ON Orders.SecretaryID = Secretaries.SecretaryID
    JOIN TestsCatalog ON Orders.TestCode = TestsCatalog.TestCode
    LEFT JOIN Insurances ON Bills.InsuranceID = Insurances.InsuranceID
    WHERE Orders.PatientID = ?
";
$stmt = $conn->prepare($billsQuery);
$stmt->bind_param('i', $patientID);
$stmt->execute();
$billsResult = $stmt->get_result();

$bills = [];
if ($billsResult->num_rows > 0) {
    while ($row = $billsResult->fetch_assoc()) {
        $bills[] = $row;
    }
}

$stmt->close();
$conn->close();

if (!empty($bills)) {
    echo json_encode(['status' => 'success', 'data' => $bills]);
} else {
    echo json_encode(['status' => 'error', 'message' => 'No results found', 'patientID' => $patientID]);
}
?>