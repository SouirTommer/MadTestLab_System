<?php
session_start();
require_once '../../connection/mysqli_conn.php';

require '../../Page/Account/auth.php';
check_labstaff_type('Pathologist');

$response = ['status' => 'error', 'message' => 'An error occurred.'];

// Check if form data is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
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

        $response['status'] = 'success';
        $response['message'] = 'Result created successfully.';
        $response['result'] = [
            'OrderID' => $orderID,
            'LabStaffID' => $labStaffID,
            'ReportURL' => $reportURL,
            'Interpretation' => $interpretation,
            'ResultDateTime' => $resultDateTime,
            'ResultStatus' => $resultStatus
        ];
    } else {
        $response['message'] = 'Error creating result: ' . $stmt->error;
    }

    $stmt->close();
} else {
    $response['message'] = 'Invalid request method.';
}

$conn->close();

header('Location: ../../Page/pathologist_order.php');

// Return JSON response
// header('Content-Type: application/json');
// echo json_encode($response);
?>