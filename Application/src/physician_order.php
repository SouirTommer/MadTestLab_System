<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Physician Orders</title>
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
                <h1>Physician Orders</h1>
            </div>
            <nav>
            <ul>
                <li><a href="physician_read_testCatalog_action.php">Test Catalog</a></li>
                <li><a href="physician_read_order_action.php">Orders</a></li>
                    <li><a href="physician_read_appointment_action.php">My Appointments</a></li>
                    <li><a href="../physician.php">Dashboard</a></li>
                    <li><a href="../logout.php">Logout</a></li>
                </ul>
            </nav>
        </div>
    </header>
    <div class="container">
        <h3>All Orders</h3>
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
                    <th>Test Name</th>
                    <th>Date and Time</th>
                    <th>Status</th>
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
                        <td><?php echo htmlspecialchars($order['TestName']); ?></td>
                        <td><?php echo htmlspecialchars($order['OrderDateTime']); ?></td>
                        <td><?php echo htmlspecialchars($order['OrderStatus']); ?></td>
                    </tr>
                <?php endforeach; ?>
            </table>
        <?php else: ?>
            <p>No orders found.</p>
        <?php endif; ?>
    </div>
</body>
</html>