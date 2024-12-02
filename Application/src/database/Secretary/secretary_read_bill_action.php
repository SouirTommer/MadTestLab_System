<?php
session_start();

require_once '../../connection/mysqli_conn_Secretary.php';

require '../../Page/Account/auth.php';
check_role(['Secretary']);

// Fetch all bills
$billsQuery = "
    SELECT 
        Bills.BillID,
        Orders.OrderID,
        Patients.FirstName AS PatientFirstName,
        Patients.LastName AS PatientLastName,
        LabStaffs.FirstName AS LabStaffFirstName,
        LabStaffs.LastName AS LabStaffLastName,
        Secretaries.FirstName AS SecretaryFirstName,
        Secretaries.LastName AS SecretaryLastName,
        TestsCatalog.TestName,
        Bills.Amount,
        Bills.PaymentStatus,
        Bills.BillDateTime,
        Insurances.InsuranceName
    FROM Bills
    JOIN Orders ON Bills.OrderID = Orders.OrderID
    JOIN Patients ON Orders.PatientID = Patients.PatientID
    JOIN LabStaffs ON Orders.LabStaffID = LabStaffs.LabStaffID
    JOIN Secretaries ON Orders.SecretaryID = Secretaries.SecretaryID
    JOIN TestsCatalog ON Orders.TestCode = TestsCatalog.TestCode
    LEFT JOIN Insurances ON Bills.InsuranceID = Insurances.InsuranceID
";
$billsResult = $conn->query($billsQuery);

$bills = [];
if ($billsResult->num_rows > 0) {
    while ($row = $billsResult->fetch_assoc()) {
        $bills[] = $row;
    }
}

$conn->close();

// Return JSON response
header('Content-Type: application/json');
echo json_encode($bills);
?>