<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Physician Appointments</title>
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
        .modal {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgb(0,0,0);
            background-color: rgba(0,0,0,0.4);
            padding-top: 60px;
        }
        .modal-content {
            background-color: #fefefe;
            margin: 5% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
        }
        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }
        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <header>
        <div class="container">
            <div id="branding">
                <h1>Physician Appointments</h1>
            </div>
            <nav>
                <ul>
                <li><a href="physician_read_testCatalog_action.php">Test Catalog</a></li>
                    <li><a href="physician_read_order_action.php">Orders</a></li>
                    <li><a href="../physician.php">Dashboard</a></li>
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
                    <th>Action</th>
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
                        <td>
                            <button class="button" onclick="openModal(<?php echo htmlspecialchars(json_encode($appointment)); ?>)">Create Order</button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </table>
        <?php else: ?>
            <p>No appointments found.</p>
        <?php endif; ?>
    </div>

    <!-- The Modal -->
<div id="orderModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeModal()">&times;</span>
        <h3>Create Order</h3>
        <form id="createOrderForm" action="labStaff_create_order_action.php" method="post">
            <input type="hidden" id="appointmentID" name="appointmentID">
            <label for="patient">Patient:</label>
            <input type="text" id="patient" name="patient" readonly>
            <br><br>
            <label for="secretary">Secretary:</label>
            <input type="text" id="secretary" name="secretary" readonly>
            <br><br>
            <label for="testCode">Test Code:</label>
            <input type="number" id="testCode" name="testCode" min="1" max="8" required>
            <br><br>
            <label for="orderDateTime">Date and Time:</label>
            <input type="datetime-local" id="orderDateTime" name="orderDateTime" required>
            <br><br>
            <label for="orderStatus">Status:</label>
            <select id="orderStatus" name="orderStatus" required>
                <option value="Pending">Pending</option>
                <option value="Completed">Completed</option>
                <option value="Cancelled">Cancelled</option>
            </select>
            <br><br>
            <input type="submit" value="Create Order" class="button">
        </form>
    </div>
</div>

<script>
    function openModal(appointment) {
        document.getElementById('appointmentID').value = appointment.AppointmentID;
        document.getElementById('patient').value = appointment.PatientFirstName + ' ' + appointment.PatientLastName;
        document.getElementById('secretary').value = appointment.SecretaryFirstName + ' ' + appointment.SecretaryLastName;
        document.getElementById('orderModal').style.display = 'block';
    }

    function closeModal() {
        document.getElementById('orderModal').style.display = 'none';
    }

    window.onclick = function(event) {
        if (event.target == document.getElementById('orderModal')) {
            closeModal();
        }
    }
</script>
</body>
</html>