<?php
session_start();

require_once '../../connection/mysqli_conn_Secretary.php';

require '../../Page/Account/auth.php';
check_role(['Secretary']);

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