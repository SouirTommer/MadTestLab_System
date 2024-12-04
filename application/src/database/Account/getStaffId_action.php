<?php
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

require_once '../../connection/mysqli_conn_Secretary.php';
require '../../Page/Account/auth.php';
// Check if the user is authenticated
if (!isset($_COOKIE['accountId'])) {
    echo json_encode(['status' => 'error', 'message' => 'Not authenticated']);
    exit();
}

// Get the AccountID from the cookies
$accountId = $_COOKIE['accountId'];

$stmt = $conn->prepare("SELECT SecretaryID FROM Secretaries WHERE AccountID = ?");
$stmt->bind_param("i", $accountId);
$stmt->execute();
$stmt->bind_result($secretaryId);
$stmt->fetch();
$stmt->close();

$_SESSION['secretaryId'] = $secretaryId;
setcookie('secretaryId', $secretaryId, time() + (86400 * 30), "/");
$response = [
    'status' => 'success',
    'secretaryId' => $secretaryId
];

echo json_encode($response);