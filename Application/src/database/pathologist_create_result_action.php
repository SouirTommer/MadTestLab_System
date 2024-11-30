<?php
session_start();
require_once '../connection/mysqli_conn_Pathologist.php';

// Ensure the user is a pathologist
if ($_SESSION['role'] !== 'LabStaff' || $_SESSION['labStaffType'] !== 'Pathologist') {
    header("Location: ../login.php");
    exit();
}

// Get the form data
$orderID = $_POST['orderID'];
$accountId = $_SESSION['accountId']; // Get the AccountID from the session
$reportURL = $_POST['reportURL'];
$interpretation = $_POST['interpretation'];
$resultStatus = $_POST['resultStatus'];
$resultDateTime = date('Y-m-d H:i:s');

// Fetch the LabStaffID based on the AccountID
$query = "SELECT LabStaffID FROM LabStaffs WHERE AccountID = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param('i', $accountId);
$stmt->execute();
$stmt->bind_result($labStaffID);
$stmt->fetch();
$stmt->close();

if (!$labStaffID) {
    $_SESSION['message'] = 'Error: LabStaffID not found.';
    header("Location: pathologist_order.php");
    exit();
}

// Insert the result into the database
$query = "INSERT INTO Results (OrderID, LabStaffID, ReportURL, Interpretation, ResultDateTime, ResultStatus) VALUES (?, ?, ?, ?, ?, ?)";
$stmt = $conn->prepare($query);
$stmt->bind_param('iissss', $orderID, $labStaffID, $reportURL, $interpretation, $resultDateTime, $resultStatus);

if ($stmt->execute()) {
    // Update the order status to 'Completed'
    $updateOrderQuery = "UPDATE Orders SET OrderStatus = 'Completed' WHERE OrderID = ?";
    $updateStmt = $conn->prepare($updateOrderQuery);
    $updateStmt->bind_param('i', $orderID);
    $updateStmt->execute();
    $updateStmt->close();

    $_SESSION['message'] = 'Result created successfully!';
} else {
    $_SESSION['message'] = 'Error creating result: ' . $stmt->error;
}

$stmt->close();
$conn->close();

header("Location: pathologist_read_result_action.php");
exit();
?>