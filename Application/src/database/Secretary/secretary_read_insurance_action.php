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

require_once '../../connection/mysqli_conn_Secretary.php';
require '../../Page/Account/auth.php';
// check_role(['Secretary']);

// Fetch all insurances
$insurances = [];

$insurancesQuery = "SELECT InsuranceID, InsuranceName, InsuranceDetails FROM Insurances";
$insurancesResult = $conn->query($insurancesQuery);
while ($insurance = $insurancesResult->fetch_assoc()) {
    $insurances[] = $insurance;
}

$conn->close();

// Return JSON response
header('Content-Type: application/json');
echo json_encode([
    'insurances' => $insurances
]);
?>