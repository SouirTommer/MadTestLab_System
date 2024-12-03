<?php
session_start();
if (isset($_SERVER['HTTP_ORIGIN']) && in_array($_SERVER['HTTP_ORIGIN'], $allowed_origins)) {
    header('Access-Control-Allow-Origin: ' . $_SERVER['HTTP_ORIGIN']);
} else {
    header('Access-Control-Allow-Origin: ' . $allowed_origins[0]); // Default to the first allowed origin
}
header('Access-Control-Allow-Methods: GET, POST, OPTIONS'); // Allow specific HTTP methods
header('Access-Control-Allow-Headers: Content-Type, Accept'); // Allow specific headers
header('Access-Control-Allow-Credentials: true'); // Allow credentials (cookies) to be sent
header('Content-Type: application/json'); // Set the content type to JSON

require_once '../../connection/mysqli_conn_Secretary.php';
require '../../Page/Account/auth.php';
// check_role(['Secretary']);

$response = ['status' => 'error', 'message' => 'An error occurred.'];

// Check if form data is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $orderID = $_POST['orderID'];
    $insuranceID = $_POST['insuranceID'];
    $amount = $_POST['amount'];
    $paymentStatus = $_POST['status'];
    $billDateTime = date('Y-m-d H:i:s');

    // Handle the case where insurance is None
    if ($insuranceID === "") {
        $insuranceID = NULL; // Set to NULL
    }

    // Insert the bill into the database
    $insertQuery = "
        INSERT INTO Bills (OrderID, InsuranceID, Amount, PaymentStatus, BillDateTime)
        VALUES (?, ?, ?, ?, ?)
    ";
    $stmt = $conn->prepare($insertQuery);

    if ($stmt === false) {
        $response['message'] = 'Prepare failed: ' . $conn->error;
        error_log('Prepare failed: ' . $conn->error);
    } else {
        $stmt->bind_param('iisss', $orderID, $insuranceID, $amount, $paymentStatus, $billDateTime);

        if ($stmt->execute()) {
            // Update the order status to 'Paid'
            $updateOrderQuery = "
                UPDATE Orders
                SET OrderStatus = 'Paid'
                WHERE OrderID = ?
            ";
            $updateStmt = $conn->prepare($updateOrderQuery);
            if ($updateStmt === false) {
                $response['message'] = 'Prepare failed for order update: ' . $conn->error;
                error_log('Prepare failed for order update: ' . $conn->error);
            } else {
                $updateStmt->bind_param('i', $orderID);
                if ($updateStmt->execute()) {
                    $response['status'] = 'success';
                    $response['message'] = 'Bill created and order status updated successfully.';
                    $response['bill'] = [
                        'BillID' => $stmt->insert_id,
                        'OrderID' => $orderID,
                        'InsuranceID' => $insuranceID,
                        'Amount' => $amount,
                        'PaymentStatus' => $paymentStatus,
                        'BillDateTime' => $billDateTime
                    ];
                } else {
                    $response['message'] = 'Failed to update order status: ' . $updateStmt->error;
                    error_log('Execute failed for order update: ' . $updateStmt->error);
                }
                $updateStmt->close();
            }
        } else {
            $response['message'] = 'Failed to create bill: ' . $stmt->error;
            error_log('Execute failed: ' . $stmt->error);
        }

        $stmt->close();
    }
} else {
    $response['message'] = 'Invalid request method.';
}

$conn->close();

// Return JSON response for AJAX requests
header('Content-Type: application/json');
echo json_encode($response);
