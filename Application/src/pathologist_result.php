<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pathologist Results</title>
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
                <h1>Pathologist Results</h1>
            </div>
            <nav>
            <ul>
                <li><a href="pathologist_read_result_action.php">Results</a></li>
                <li><a href="pathologist_read_order_action.php">Orders</a></li>
                <li><a href="../pathologist.php">Dashboard</a></li>        
                <li><a href="../logout.php">Logout</a></li>
                </ul>
            </nav>
        </div>
    </header>
    <div class="container">
        <h3>All Results</h3>
        <?php if (count($results) > 0): ?>
            <table>
                <tr>
                    <th>ResultID</th>
                    <th>OrderID</th>
                    <th>Patient First Name</th>
                    <th>Patient Last Name</th>
                    <th>Lab Staff First Name</th>
                    <th>Lab Staff Last Name</th>
                    <th>Report URL</th>
                    <th>Interpretation</th>
                    <th>Result Date and Time</th>
                    <th>Result Status</th>
                </tr>
                <?php foreach ($results as $result): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($result['ResultID']); ?></td>
                        <td><?php echo htmlspecialchars($result['OrderID']); ?></td>
                        <td><?php echo htmlspecialchars($result['PatientFirstName']); ?></td>
                        <td><?php echo htmlspecialchars($result['PatientLastName']); ?></td>
                        <td><?php echo htmlspecialchars($result['LabStaffFirstName']); ?></td>
                        <td><?php echo htmlspecialchars($result['LabStaffLastName']); ?></td>
                        <td><a href="<?php echo htmlspecialchars($result['ReportURL']); ?>" target="_blank">View Report</a></td>
                        <td><?php echo htmlspecialchars($result['Interpretation']); ?></td>
                        <td><?php echo htmlspecialchars($result['ResultDateTime']); ?></td>
                        <td><?php echo htmlspecialchars($result['ResultStatus']); ?></td>
                    </tr>
                <?php endforeach; ?>
            </table>
        <?php else: ?>
            <p>No results found.</p>
        <?php endif; ?>
    </div>
</body>
</html>