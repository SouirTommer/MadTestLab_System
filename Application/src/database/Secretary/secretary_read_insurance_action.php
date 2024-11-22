<?php
session_start();

require_once '../../connection/mysqli_conn.php';

require '../../Page/Account/auth.php';
check_role(['Secretary']);
// Fetch all insurances
$insurancesQuery = "SELECT InsuranceID, InsuranceName, InsuranceAmount, InsuranceDetails, InsuranceStatus FROM Insurances";
$insurancesResult = $conn->query($insurancesQuery);

$insurances = [];
if ($insurancesResult->num_rows > 0) {
    while ($row = $insurancesResult->fetch_assoc()) {
        $insurances[] = $row;
    }
}

//json object
$insurancesJson = json_encode($insurances);

$conn->close();
include '../../Page/secretary_insurance.php';
?>