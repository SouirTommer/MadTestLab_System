<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bill Records</title>
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
                <h1>Bill Records</h1>
            </div>
            <nav>
            <ul>
                    
                    <li><a href="secretary_read_insurance_action.php">Insurances</a></li>
                        <li><a href="secretary_read_bill_action.php">Bills</a></li>
                            <li><a href="secretary_read_order_action.php">Orders</a></li>
                            <li><a href="secretary_read_appointment_action.php">Appointments</a></li>
                            <li><a href="../secretary.php">Dashboard</a></li>
                            <li><a href="../logout.php">Logout</a></li>
                        </ul>
            </nav>
        </div>
    </header>
    <div class="container">
        <h3>All Bills</h3>
        <?php if (count($bills) > 0): ?>
            <table>
                <tr>
                    <th>BillID</th>
                    <th>OrderID</th>
                    <th>Patient First Name</th>
                    <th>Patient Last Name</th>
                    <th>Lab Staff First Name</th>
                    <th>Lab Staff Last Name</th>
                    <th>Secretary First Name</th>
                    <th>Secretary Last Name</th>
                    <th>Test Name</th>
                    <th>Amount</th>
                    <th>Payment Status</th>
                    <th>Bill Date and Time</th>
                    <th>Insurance Name</th>
                </tr>
                <?php foreach ($bills as $bill): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($bill['BillID']); ?></td>
                        <td><?php echo htmlspecialchars($bill['OrderID']); ?></td>
                        <td><?php echo htmlspecialchars($bill['PatientFirstName']); ?></td>
                        <td><?php echo htmlspecialchars($bill['PatientLastName']); ?></td>
                        <td><?php echo htmlspecialchars($bill['LabStaffFirstName']); ?></td>
                        <td><?php echo htmlspecialchars($bill['LabStaffLastName']); ?></td>
                        <td><?php echo htmlspecialchars($bill['SecretaryFirstName']); ?></td>
                        <td><?php echo htmlspecialchars($bill['SecretaryLastName']); ?></td>
                        <td><?php echo htmlspecialchars($bill['TestName']); ?></td>
                        <td><?php echo htmlspecialchars($bill['Amount']); ?></td>
                        <td><?php echo htmlspecialchars($bill['PaymentStatus']); ?></td>
                        <td><?php echo htmlspecialchars($bill['BillDateTime']); ?></td>
                        <td><?php echo htmlspecialchars($bill['InsuranceName'] ?? 'None'); ?></td>
                    </tr>
                <?php endforeach; ?>
            </table>
        <?php else: ?>
            <p>No bills found.</p>
        <?php endif; ?>
    </div>
</body>
</html>