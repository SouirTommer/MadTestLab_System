<?php
session_start();

require '../auth.php'; // Update the path to the correct location of auth.php
check_login();

// Ensure the user is a secretary
if ($_SESSION['role'] !== 'Secretary') {
    header("Location: ../login.php");
    exit();
}

// Fetch all orders
require_once '../connection/mysqli_conn.php';

$query = "
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
";
$result = $conn->query($query);

$orders = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $orders[] = $row;
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

// Fetch all lab staff
$labStaffQuery = "SELECT LabStaffID, FirstName, LastName FROM LabStaffs";
$labStaffResult = $conn->query($labStaffQuery);

$labStaffs = [];

if ($labStaffResult->num_rows > 0) {
    while ($row = $labStaffResult->fetch_assoc()) {
        $labStaffs[] = $row; // Add each row to the lab staff array
    }
}

// Fetch all tests
$testsQuery = "SELECT TestCode, TestName FROM TestsCatalog";
$testsResult = $conn->query($testsQuery);

$tests = [];

if ($testsResult->num_rows > 0) {
    while ($row = $testsResult->fetch_assoc()) {
        $tests[] = $row; // Add each row to the tests array
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All Orders</title>
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
        function showCreateOrderForm() {
            document.getElementById('createOrderForm').style.display = 'block';
        }
    </script>
</head>
<body>
    <header>
        <div class="container">
            <div id="branding">
                <h1>All Orders</h1>
            </div>
            <nav>
                <ul>
                    <li><a href="../secretary.php">Dashboard</a></li>
                    <li><a href="../secretary_read_appointment_action.php">Appointments</a></li>
                    <li><a href="../secretary_read_order_action.php">Orders</a></li>
                    <li><a href="../logout.php">Logout</a></li>
                </ul>
            </nav>
        </div>
    </header>
    <div class="container">
        <h2>All Orders</h2>

        <button onclick="showCreateOrderForm()" class="button">Create Order</button>

        <div id="createOrderForm" style="display: none; margin-top: 20px;">
            <h3>Create Order</h3>
            <form action="secretary_create_order_action.php" method="post">
                <label for="patient">Patient:</label>
                <select name="patient" id="patient" required>
                    <?php foreach ($patients as $patient): ?>
                        <option value="<?php echo $patient['PatientID']; ?>">
                            <?php echo $patient['FirstName'] . ' ' . $patient['LastName']; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <br><br>
                <label for="labStaff">Lab Staff:</label>
                <select name="labStaff" id="labStaff" required>
                    <?php foreach ($labStaffs as $labStaff): ?>
                        <option value="<?php echo $labStaff['LabStaffID']; ?>">
                            <?php echo $labStaff['FirstName'] . ' ' . $labStaff['LastName']; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <br><br>
                <label for="test">Test:</label>
                <select name="test" id="test" required>
                    <?php foreach ($tests as $test): ?>
                        <option value="<?php echo $test['TestCode']; ?>">
                            <?php echo $test['TestName']; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <br><br>
                <label for="datetime">Order Date and Time:</label>
                <input type="datetime-local" id="datetime" name="datetime" required>
                <br><br>
                <input type="submit" value="Create Order" class="button">
            </form>
        </div>

        <?php if (count($orders) > 0): ?>
            <table>
                <tr>
                    <th>OrderID</th>
                    <th>Patient First Name</th>
                    <th>Patient Last Name</th>
                    <th>Lab Staff First Name</th>
                    <th>Lab Staff Last Name</th>
                    <th>Secretary First Name</th>
                    <th>Secretary Last Name</th>
                    <th>Order Date and Time</th>
                    <th>Order Status</th>
                    <th>Test Name</th>
                </tr>
                <?php foreach ($orders as $order): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($order['OrderID']); ?></td>
                        <td><?php echo htmlspecialchars($order['PatientFirstName']); ?></td>
                        <td><?php echo htmlspecialchars($order['PatientLastName']); ?></td>
                        <td><?php echo htmlspecialchars($order['LabStaffFirstName']); ?></td>
                        <td><?php echo htmlspecialchars($order['LabStaffLastName']); ?></td>
                        <td><?php echo htmlspecialchars($order['SecretaryFirstName']); ?></td>
                        <td><?php echo htmlspecialchars($order['SecretaryLastName']); ?></td>
                        <td><?php echo htmlspecialchars($order['OrderDateTime']); ?></td>
                        <td><?php echo htmlspecialchars($order['OrderStatus']); ?></td>
                        <td><?php echo htmlspecialchars($order['TestName']); ?></td>
                    </tr>
                <?php endforeach; ?>
            </table>
        <?php else: ?>
            <p>No orders found.</p>
        <?php endif; ?>
    </div>
</body>
</html>