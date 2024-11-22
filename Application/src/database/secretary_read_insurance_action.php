<?php
session_start();
require_once '../connection/mysqli_conn.php';

// Ensure the user is logged in
if (!isset($_SESSION['accountId'])) {
    header("Location: ../login.php");
    exit();
}

// Fetch all insurances
$insurancesQuery = "SELECT InsuranceID, InsuranceName, InsuranceAmount, InsuranceDetails, InsuranceStatus FROM Insurances";
$insurancesResult = $conn->query($insurancesQuery);

$insurances = [];
if ($insurancesResult->num_rows > 0) {
    while ($row = $insurancesResult->fetch_assoc()) {
        $insurances[] = $row;
    }
}

$conn->close();
include '../secretary_insurance.php';
?>