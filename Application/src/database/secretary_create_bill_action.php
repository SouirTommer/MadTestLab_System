<?php
session_start();
require_once '../connection/mysqli_conn_Secretary.php';

// Ensure the user is a secretary
if ($_SESSION['role'] !== 'Secretary') {
    header("Location: ../login.php");
    exit();
}

// Get the form data
$orderID = $_POST['orderID'];
$insuranceID = $_POST['insurance'];
$amount = $_POST['amount'];
$paymentStatus = $_POST['status'];
$billDateTime = date('Y-m-d H:i:s');

// Handle the case where insurance is None
if ($insuranceID === "") {
    $insuranceID = NULL; // Set to NULL
}

// Insert the bill into the database
$query = "INSERT INTO Bills (OrderID, InsuranceID, Amount, PaymentStatus, BillDateTime) VALUES (?, ?, ?, ?, ?)";
$stmt = $conn->prepare($query);
$stmt->bind_param('iisss', $orderID, $insuranceID, $amount, $paymentStatus, $billDateTime);

if ($stmt->execute()) {
    $_SESSION['message'] = 'Bill created successfully!';
} else {
    $_SESSION['message'] = 'Error creating bill: ' . $stmt->error;
}

$stmt->close();
$conn->close();

header("Location: secretary_read_bill_action.php");
exit();
?>