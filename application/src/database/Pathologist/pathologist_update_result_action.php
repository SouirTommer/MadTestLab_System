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

$response = ['status' => 'error', 'message' => 'An error occurred.'];

// Check if form data is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $resultID = $_POST['resultID'];
    $reportURL = $_POST['reportURL'];
    $interpretation = $_POST['interpretation'];
    $resultStatus = $_POST['resultStatus'];
    $resultDateTime = date('Y-m-d H:i:s');



    // Update the result status
    $updateResultQuery = "UPDATE Results SET ResultStatus = ?, Interpretation = ?, ReportURL = ?  WHERE ResultID = ?";
    $updateStmt = $conn->prepare($updateResultQuery);
    $updateStmt->bind_param('sssi', $resultStatus, $interpretation, $reportURL, $resultID);
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

    $stmt->close();
} else {
    $response['message'] = 'Invalid request method.';
}

$conn->close();

// header('Location: ../../Page/pathologist_order.php');

// Return JSON response
header('Content-Type: application/json');
echo json_encode($response);
