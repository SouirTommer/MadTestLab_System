<?php
session_start();
require_once '../connection/mysqli_conn.php';

// Fetch patients and physicians for the form
$patients = [];
$physicians = [];

$patientsQuery = "SELECT PatientID, FirstName, LastName FROM Patients";
$patientsResult = $conn->query($patientsQuery);
while ($patient = $patientsResult->fetch_assoc()) {
    $patients[] = $patient;
}

$physiciansQuery = "SELECT LabStaffID, FirstName, LastName FROM LabStaffs WHERE LabStaffType = 'Physician'";
$physiciansResult = $conn->query($physiciansQuery);
while ($physician = $physiciansResult->fetch_assoc()) {
    $physicians[] = $physician;
}

// Fetch all appointments
$appointmentsQuery = "
    SELECT 
        Appointments.AppointmentID,
        Patients.FirstName AS PatientFirstName,
        Patients.LastName AS PatientLastName,
        LabStaffs.FirstName AS PhysicianFirstName,
        LabStaffs.LastName AS PhysicianLastName,
        Appointments.AppointmentDateTime,
        Appointments.AppointmentsStatus,
        Appointments.LabStaffID
    FROM Appointments
    JOIN Patients ON Appointments.PatientID = Patients.PatientID
    JOIN LabStaffs ON Appointments.LabStaffID = LabStaffs.LabStaffID
";
$appointmentsResult = $conn->query($appointmentsQuery);

$appointments = [];
if ($appointmentsResult->num_rows > 0) {
    while ($row = $appointmentsResult->fetch_assoc()) {
        $appointments[] = $row;
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
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
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
            <label for="physician">Physician:</label>
            <select name="physician" id="physician" required>
                <?php foreach ($physicians as $physician): ?>
                    <option value="<?php echo $physician['LabStaffID']; ?>">
                        <?php echo $physician['FirstName'] . ' ' . $physician['LastName']; ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <br><br>
            <label for="datetime">Date and Time:</label>
            <input type="datetime-local" id="datetime" name="datetime" required>
            <br><br>
            <input type="submit" value="Create Appointment" class="button">
        </form>

        <div id="updateAppointmentForm" style="display: none; margin-top: 20px;">
            <h3>Update Appointment</h3>
            <form action="secretary_update_appointment_action.php" method="post">
                <input type="hidden" id="updateAppointmentID" name="appointmentID">
                <label for="updatePhysician">Physician:</label>
                <select name="physician" id="updatePhysician" required>
                    <?php foreach ($physicians as $physician): ?>
                        <option value="<?php echo $physician['LabStaffID']; ?>">
                            <?php echo $physician['FirstName'] . ' ' . $physician['LastName']; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <br><br>
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

        <h3>All Appointments</h3>
        <?php if (count($appointments) > 0): ?>
            <table>
                <tr>
                    <th>AppointmentID</th>
                    <th>Patient First Name</th>
                    <th>Patient Last Name</th>
                    <th>Physician First Name</th>
                    <th>Physician Last Name</th>
                    <th>Date and Time</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
                <?php foreach ($appointments as $appointment): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($appointment['AppointmentID']); ?></td>
                        <td><?php echo htmlspecialchars($appointment['PatientFirstName']); ?></td>
                        <td><?php echo htmlspecialchars($appointment['PatientLastName']); ?></td>
                        <td><?php echo htmlspecialchars($appointment['PhysicianFirstName']); ?></td>
                        <td><?php echo htmlspecialchars($appointment['PhysicianLastName']); ?></td>
                        <td><?php echo htmlspecialchars($appointment['AppointmentDateTime']); ?></td>
                        <td><?php echo htmlspecialchars($appointment['AppointmentsStatus']); ?></td>
                        <td>
                            <button class="button" onclick="populateUpdateForm(<?php echo htmlspecialchars(json_encode($appointment)); ?>)">Update</button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </table>
        <?php else: ?>
            <p>No appointments found.</p>
        <?php endif; ?>
    </div>
    <script>
        function populateUpdateForm(appointment) {
            document.getElementById('updateAppointmentID').value = appointment.AppointmentID;
            document.getElementById('updatePhysician').value = appointment.LabStaffID;
            document.getElementById('updateDateTime').value = appointment.AppointmentDateTime.replace(' ', 'T');
            document.getElementById('updateStatus').value = appointment.AppointmentsStatus;
            document.getElementById('updateAppointmentForm').style.display = 'block';
        }
    </script>
</body>
</html>