<?php
session_start();

require_once '../../connection/mysqli_conn.php';

require '../../Page/Account/auth.php';
check_labstaff_type('Pathologist');

// Fetch all results
$resultsQuery = "
    SELECT 
        Results.ResultID,
        Orders.OrderID,
        Patients.FirstName AS PatientFirstName,
        Patients.LastName AS PatientLastName,
        LabStaffs.FirstName AS LabStaffFirstName,
        LabStaffs.LastName AS LabStaffLastName,
        Results.ReportURL,
        Results.Interpretation,
        Results.ResultDateTime,
        Results.ResultStatus
    FROM Results
    JOIN Orders ON Results.OrderID = Orders.OrderID
    JOIN Patients ON Orders.PatientID = Patients.PatientID
    JOIN LabStaffs ON Results.LabStaffID = LabStaffs.LabStaffID
";
$resultsResult = $conn->query($resultsQuery);

$results = [];
if ($resultsResult->num_rows > 0) {
    while ($row = $resultsResult->fetch_assoc()) {
        $results[] = $row;
    }
}

$conn->close();

//json object
$resultsJson = json_encode($results);

include '../../Page/pathologist_result.php';
?>