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

require_once '../../connection/mysqli_conn_Pathologist.php';
require '../../Page/Account/auth.php';

// check_labstaff_type('Pathologist');

if (!isset($_COOKIE['accountId'])) {
    echo json_encode(['status' => 'error', 'message' => 'Not authenticated']);
    exit();
}

$accountId = $_COOKIE['accountId'];

$response = ['status' => 'error', 'message' => 'An error occurred.'];

// Check if form data is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $orderID = $_POST['orderID'] ?? null;
    $reportURL = $_POST['reportURL'] ?? null;
    $interpretation = $_POST['interpretation'] ?? null;
    $resultStatus = $_POST['resultStatus'] ?? null;
    $resultDateTime = date('Y-m-d H:i:s');

    if (!$orderID || !$reportURL || !$interpretation || !$resultStatus) {
        $response['message'] = 'Missing required fields.';
        echo json_encode($response);
        exit();
    }

    // Fetch the LabStaffID based on the AccountID
    $query = "SELECT LabStaffID FROM LabStaffs WHERE AccountID = ?";
    $stmt = $conn->prepare($query);
    if (!$stmt) {
        $response['message'] = 'Failed to prepare statement: ' . $conn->error;
        echo json_encode($response);
        exit();
    }
    $stmt->bind_param('i', $accountId);
    $stmt->execute();
    $stmt->bind_result($labStaffID);
    $stmt->fetch();
    $stmt->close();

    if (!$labStaffID) {
        $response['message'] = 'LabStaffID not found.';
        echo json_encode($response);
        exit();
    }

    // Insert the result into the database
    $query = "INSERT INTO Results (OrderID, LabStaffID, ReportURL, Interpretation, ResultDateTime, ResultStatus) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    if (!$stmt) {
        $response['message'] = 'Failed to prepare statement: ' . $conn->error;
        echo json_encode($response);
        exit();
    }
    $stmt->bind_param('iissss', $orderID, $labStaffID, $reportURL, $interpretation, $resultDateTime, $resultStatus);

    if ($stmt->execute()) {
        // Update the order status to 'Completed'
        $updateOrderQuery = "UPDATE Orders SET OrderStatus = 'Completed' WHERE OrderID = ?";
        $updateStmt = $conn->prepare($updateOrderQuery);
        if (!$updateStmt) {
            $response['message'] = 'Failed to prepare update statement: ' . $conn->error;
            echo json_encode($response);
            exit();
        }
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

// Return JSON response
echo json_encode($response);
?>