<?php
require_once '../connection/mysqli_conn.php';
session_start();

// 檢查使用者是否已登入
if (!isset($_SESSION['username'])) {
    header("Location: ../login.php");
    exit();
}

// Fetch all appointment records
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

// Fetch all patients
$patientsQuery = "SELECT PatientID, FirstName, LastName FROM Patients";
$patientsResult = $conn->query($patientsQuery);

$patients = [];

if ($patientsResult->num_rows > 0) {
    while ($row = $patientsResult->fetch_assoc()) {
        $patients[] = $row; // Add each row to the patients array
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
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid black;
        }
        th, td {
            padding: 10px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
    <script>
        function showCreateAppointmentForm() {
            document.getElementById('createAppointmentForm').style.display = 'block';
        }
    </script>
</head>
<body>
    <h2>Appointment Records</h2>

    <button onclick="showCreateAppointmentForm()" style="padding: 10px; background-color: #007bff; color: white; text-align: center; text-decoration: none; border-radius: 5px; border: none; cursor: pointer;">Create Appointment</button>

    <div id="createAppointmentForm" style="display: none; margin-top: 20px;">
        <h3>Create Appointment</h3>
        <form action="secretary_create_appointment_action.php" method="post">
            <label for="patient">Patient:</label>
            <select name="patient" id="patient" required>
                <?php foreach ($patients as $patient): ?>
                    <option value="<?php echo $patient['PatientID']; ?>">
                        <?php echo $patient['FirstName'] . ' ' . $patient['LastName']; ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <br><br>
            <label for="datetime">Date and Time:</label>
            <input type="datetime-local" id="datetime" name="datetime" required>
            <br><br>
            <input type="hidden" name="secretaryID" value="<?php echo $_SESSION['accountId']; ?>">
            <input type="submit" value="Create Appointment">
        </form>
    </div>

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
</body>
</html>