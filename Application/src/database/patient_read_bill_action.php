<?php
session_start();
require_once '../connection/mysqli_conn_Patient.php';

// Ensure the user is a patient
if ($_SESSION['role'] !== 'Patient') {
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

// Fetch all bills for the logged-in patient
$billsQuery = "
    SELECT 
        Bills.BillID,
        Orders.OrderID,
        Bills.Amount,
        Bills.PaymentStatus,
        Bills.BillDateTime,
        Insurances.InsuranceName
    FROM Bills
    JOIN Orders ON Bills.OrderID = Orders.OrderID
    JOIN Insurances ON Bills.InsuranceID = Insurances.InsuranceID
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

//json object
$billsJson = json_encode($bills);

include '../patient_bill.php';
?>