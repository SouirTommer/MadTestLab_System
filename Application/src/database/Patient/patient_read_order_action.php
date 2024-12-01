<?php
session_start();

require_once '../../connection/mysqli_conn.php';


require '../../Page/Account/auth.php';
check_role(['Patient']);

// Get the AccountID from the session
$accountId = $_SESSION['accountId'];

// Fetch the PatientID based on the AccountID
$patientQuery = "SELECT PatientID FROM Patients WHERE AccountID = ?";
$stmt = $conn->prepare($patientQuery);
$stmt->bind_param('i', $accountId);
$stmt->execute();
$stmt->bind_result($patientID);
$stmt->fetch();
$stmt->close();

if (!$patientID) {
    header("Location: ../login.php");
    exit();
}

// Fetch all orders for the logged-in patient
$ordersQuery = "
    SELECT 
        Orders.OrderID,
        Patients.FirstName AS PatientFirstName,
        Patients.LastName AS PatientLastName,
        LabStaffs.FirstName AS LabStaffFirstName,
        LabStaffs.LastName AS LabStaffLastName,
        Secretaries.FirstName AS SecretaryFirstName,
        Secretaries.LastName AS SecretaryLastName,
        Orders.OrderDateTime,
        Orders.OrderStatus,
        TestsCatalog.TestName
    FROM Orders
    JOIN Patients ON Orders.PatientID = Patients.PatientID
    JOIN LabStaffs ON Orders.LabStaffID = LabStaffs.LabStaffID
    JOIN Secretaries ON Orders.SecretaryID = Secretaries.SecretaryID
    JOIN TestsCatalog ON Orders.TestCode = TestsCatalog.TestCode
    WHERE Orders.PatientID = ?
";
$stmt = $conn->prepare($ordersQuery);
$stmt->bind_param('i', $patientID);
$stmt->execute();
$ordersResult = $stmt->get_result();

$orders = [];
if ($ordersResult->num_rows > 0) {
    while ($row = $ordersResult->fetch_assoc()) {
        $orders[] = $row;
    }
}

$stmt->close();
$conn->close();

//json object
header('Content-Type: application/json');
echo json_encode($orders);
?>