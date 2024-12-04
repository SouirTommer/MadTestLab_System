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
check_labstaff_type('Pathologist');
if (!isset($_COOKIE['accountId'])) {
    echo json_encode(['status' => 'error', 'message' => 'Not authenticated']);
    exit();
}
$accountId = $_COOKIE['accountId'];

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

// header('Location: ../../Page/pathologist_order.php');

// Return JSON response
header('Content-Type: application/json');
echo json_encode($response);
?>