<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Appointments</title>
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
                    <li><a href="patient_read_bill_action.php">Bills</a></li>
                <li><a href="patient_read_order_action.php">ORDERS</a></li>
                    <li><a href="patient_read_appointment_action.php">APPOINTMENTS</a></li>
                    <li><a href="../patient.php">Dashboard</a></li>
                    <li><a href="../logout.php">Logout</a></li>
                </ul>
            </nav>
        </div>
    </header>
    <div class="container">
        <h3>All Appointments</h3>
        <?php if (count($appointments) > 0): ?>
            <table>
                <tr>
                    <th>AppointmentID</th>
                    <th>Patient First Name</th>
                    <th>Patient Last Name</th>
                    <th>Physician First Name</th>
                    <th>Physician Last Name</th>
                    <th>Secretary First Name</th>
                    <th>Secretary Last Name</th>
                    <th>Date and Time</th>
                    <th>Status</th>
                </tr>
                <?php foreach ($appointments as $appointment): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($appointment['AppointmentID']); ?></td>
                        <td><?php echo htmlspecialchars($appointment['PatientFirstName']); ?></td>
                        <td><?php echo htmlspecialchars($appointment['PatientLastName']); ?></td>
                        <td><?php echo htmlspecialchars($appointment['PhysicianFirstName']); ?></td>
                        <td><?php echo htmlspecialchars($appointment['PhysicianLastName']); ?></td>
                        <td><?php echo htmlspecialchars($appointment['SecretaryFirstName']); ?></td>
                        <td><?php echo htmlspecialchars($appointment['SecretaryLastName']); ?></td>
                        <td><?php echo htmlspecialchars($appointment['AppointmentDateTime']); ?></td>
                        <td><?php echo htmlspecialchars($appointment['AppointmentsStatus']); ?></td>
                    </tr>
                <?php endforeach; ?>
            </table>
        <?php else: ?>
            <p>No appointments found.</p>
        <?php endif; ?>
    </div>
</body>
</html>