<?php
session_start();

require '../auth.php'; // Update the path to the correct location of auth.php
check_login();

// Ensure the user is a patient
if ($_SESSION['role'] !== 'Patient') {
    header("Location: ../login.php");
    exit();
}

// Fetch appointments for the logged-in patient
require_once '../connection/mysqli_conn.php';

$accountId = $_SESSION['accountId'];
$query = "
    SELECT 
        Appointments.AppointmentID,
        Appointments.AppointmentDateTime,
        Appointments.AppointmentsStatus,
        Secretaries.FirstName AS SecretaryFirstName,
        Secretaries.LastName AS SecretaryLastName
    FROM Appointments
    JOIN Secretaries ON Appointments.SecretaryID = Secretaries.SecretaryID
    WHERE Appointments.PatientID = (SELECT PatientID FROM Patients WHERE AccountID = ?)
";
$stmt = $conn->prepare($query);
$stmt->bind_param('i', $accountId);
$stmt->execute();
$result = $stmt->get_result();

$appointments = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $appointments[] = $row;
    }
}

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Appointments</title>
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
</head>
<body>
    <header>
        <div class="container">
            <div id="branding">
                <h1>My Appointments</h1>
            </div>
            <nav>
                <ul>
                    <li><a href="../patient.php">Dashboard</a></li>
                    <li><a href="../logout.php">Logout</a></li>
                </ul>
            </nav>
        </div>
    </header>
    <div class="container">
        <h2>My Appointments</h2>
        <?php if (count($appointments) > 0): ?>
            <table>
                <tr>
                    <th>AppointmentID</th>
                    <th>Date and Time</th>
                    <th>Status</th>
                    <th>Secretary</th>
                </tr>
                <?php foreach ($appointments as $appointment): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($appointment['AppointmentID']); ?></td>
                        <td><?php echo htmlspecialchars($appointment['AppointmentDateTime']); ?></td>
                        <td><?php echo htmlspecialchars($appointment['AppointmentsStatus']); ?></td>
                        <td><?php echo htmlspecialchars($appointment['SecretaryFirstName'] . ' ' . $appointment['SecretaryLastName']); ?></td>
                    </tr>
                <?php endforeach; ?>
            </table>
        <?php else: ?>
            <p>No appointments found.</p>
        <?php endif; ?>
    </div>
</body>
</html>