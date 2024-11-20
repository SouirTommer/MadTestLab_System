<?php
require_once '../connection/mysqli_conn.php';
session_start();

// 檢查使用者是否已登入
if (!isset($_SESSION['username'])) {
    header("Location: ../login.php");
    exit();
}

// 查詢所有預約記錄並進行聯結
$query = "
    SELECT 
        Appointments.AppointmentID,
        Patients.FirstName AS PatientFirstName,
        Patients.LastName AS PatientLastName,
        Secretaries.FirstName AS SecretaryFirstName,
        Secretaries.LastName AS SecretaryLastName,
        Appointments.AppointmentDateTime,
        Appointments.AppointmentsStatus
    FROM Appointments
    JOIN Patients ON Appointments.PatientID = Patients.PatientID
    JOIN Secretaries ON Appointments.SecretaryID = Secretaries.SecretaryID
";
$result = $conn->query($query);

$appointments = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $appointments[] = $row; // Add each row to the appointments array
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Appointment Records</title>
</head>
<body>
    <h2>Appointment Records</h2>
    <?php
    if (count($appointments) > 0) {
        echo "<table border='1'>
                <tr>
                    <th>AppointmentID</th>
                    <th>Patient First Name</th>
                    <th>Patient Last Name</th>
                    <th>Secretary First Name</th>
                    <th>Secretary Last Name</th>
                    <th>AppointmentDateTime</th>
                    <th>AppointmentsStatus</th>
                </tr>";
        foreach ($appointments as $row) {
            echo "<tr>
                    <td>{$row['AppointmentID']}</td>
                    <td>{$row['PatientFirstName']}</td>
                    <td>{$row['PatientLastName']}</td>
                    <td>{$row['SecretaryFirstName']}</td>
                    <td>{$row['SecretaryLastName']}</td>
                    <td>{$row['AppointmentDateTime']}</td>
                    <td>{$row['AppointmentsStatus']}</td>
                  </tr>";
        }
        echo "</table>";
    } else {
        echo "No appointment records found.";
    }
    ?>
    <h2>Appointment Records JSON</h2>
    <pre><?php echo json_encode($appointments, JSON_PRETTY_PRINT); ?></pre>
</body>
</html>