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
        body {
            font-family: Arial, sans-serif;
        }
        .container {
            width: 80%;
            margin: auto;
            overflow: hidden;
        }
        header {
            background: #333;
            color: #fff;
            padding-top: 30px;
            min-height: 70px;
            border-bottom: #0779e4 3px solid;
        }
        header a {
            color: #fff;
            text-decoration: none;
            text-transform: uppercase;
            font-size: 16px;
        }
        header ul {
            padding: 0;
            list-style: none;
        }
        header li {
            float: left;
            display: inline;
            padding: 0 20px 0 20px;
        }
        header #branding {
            float: left;
        }
        header #branding h1 {
            margin: 0;
        }
        header nav {
            float: right;
            margin-top: 10px;
        }
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
        .button {
            padding: 10px;
            background-color: #007bff;
            color: white;
            text-align: center;
            text-decoration: none;
            border-radius: 5px;
            border: none;
            cursor: pointer;
        }
    </style>
    <script>
        function showCreateAppointmentForm() {
            document.getElementById('createAppointmentForm').style.display = 'block';
        }
        function showUpdateAppointmentForm(appointmentID, dateTime, status) {
            document.getElementById('updateAppointmentForm').style.display = 'block';
            document.getElementById('updateAppointmentID').value = appointmentID;
            document.getElementById('updateDateTime').value = dateTime;
            document.getElementById('updateStatus').value = status;
        }
    </script>
</head>
<body>
    <header>
        <div class="container">
            <div id="branding">
                <h1>Appointment Records</h1>
            </div>
            <nav>
                <ul>
                    <li><a href="secretary_read_order_action.php">Orders</a></li>
                    <li><a href="../secretary.php">Dashboard</a></li>
                    <li><a href="../logout.php">Logout</a></li>
                </ul>
            </nav>
        </div>
    </header>
    <div class="container">
        <h2>Appointment Records</h2>

        <button onclick="showCreateAppointmentForm()" class="button">Create Appointment</button>

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
                <input type="submit" value="Create Appointment" class="button">
            </form>
        </div>

        <div id="updateAppointmentForm" style="display: none; margin-top: 20px;">
            <h3>Update Appointment</h3>
            <form action="secretary_update_appointment_action.php" method="post">
                <input type="hidden" id="updateAppointmentID" name="appointmentID">
                <label for="updateDateTime">Date and Time:</label>
                <input type="datetime-local" id="updateDateTime" name="datetime" required>
                <br><br>
                <label for="updateStatus">Status:</label>
                <select id="updateStatus" name="status" required>
                    <option value="Scheduled">Scheduled</option>
                    <option value="Completed">Completed</option>
                    <option value="Cancelled">Cancelled</option>
                </select>
                <br><br>
                <input type="submit" value="Update Appointment" class="button">
            </form>
            </div>

            <?php
if (count($appointments) > 0) {
    echo "<table>
            <tr>
                <th>AppointmentID</th>
                <th>Patient First Name</th>
                <th>Patient Last Name</th>
                <th>Secretary First Name</th>
                <th>Secretary Last Name</th>
                <th>AppointmentDateTime</th>
                <th>AppointmentsStatus</th>
                <th>Actions</th>
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
                <td>
                    <button class='button' onclick=\"showUpdateAppointmentForm('{$row['AppointmentID']}', '{$row['AppointmentDateTime']}', '{$row['AppointmentsStatus']}')\">Update</button>
                </td>
              </tr>";
    }
    echo "</table>";
} else {
    echo "No appointment records found.";
}

// Create an associative array for JSON output
$data = array(
    "status" => "success",
    "message" => "Data processed successfully",
    "appointments" => $appointments
);

// Convert the array to a JSON string
$json_data = json_encode($data, JSON_PRETTY_PRINT);

// Print the JSON string
echo "<pre>" . $json_data . "</pre>";
?>
</div>
</body>
</html>