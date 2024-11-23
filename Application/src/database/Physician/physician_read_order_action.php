<?php
session_start();

require_once '../../connection/mysqli_conn.php';

require '../../Page/Account/auth.php';
check_labstaff_type('Physician');

// Function to get the LabStaffID from the AccountID
function getLabStaffID($conn, $accountId) {
    $query = "SELECT LabStaffID FROM LabStaffs WHERE AccountID = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('i', $accountId);
    $stmt->execute();
    $stmt->bind_result($labStaffID);
    $stmt->fetch();
    $stmt->close();
    return $labStaffID;
}

// Get the AccountID from the session
$accountId = $_SESSION['accountId'];

// Get the LabStaffID using the AccountID
$labStaffID = getLabStaffID($conn, $accountId);

// Fetch all orders for the logged-in physician including patient, lab staff, secretary, and test details
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
    WHERE Orders.LabStaffID = ?
";
$stmt = $conn->prepare($ordersQuery);
$stmt->bind_param('i', $labStaffID);
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

// Return JSON response
header('Content-Type: application/json');
echo json_encode($orders);
?>