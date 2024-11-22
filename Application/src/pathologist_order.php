<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pathologist Orders</title>
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
                <h1>Pathologist Orders</h1>
            </div>
            <nav>
                <ul>
                <li><a href="pathologist_read_result_action.php">Results</a></li>
                    <li><a href="../pathologist.php">Dashboard</a></li>
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
                    <th>Order Date and Time</th>
                    <th>Order Status</th>
                    <th>Test Name</th>
                    <th>Action</th>
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
                        <td>
                            <button class="button" onclick="openModal(<?php echo htmlspecialchars(json_encode($order)); ?>)">Create Result</button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </table>
        <?php else: ?>
            <p>No orders found.</p>
        <?php endif; ?>
    </div>

    <!-- The Modal -->
    <div id="resultModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal()">&times;</span>
            <h3>Create Result</h3>
            <form id="createResultForm" action="pathologist_create_result_action.php" method="post">
                <input type="hidden" id="orderID" name="orderID">
                <label for="reportURL">Report URL:</label>
                <input type="text" id="reportURL" name="reportURL" required>
                <br><br>
                <label for="interpretation">Interpretation:</label>
                <textarea id="interpretation" name="interpretation" required></textarea>
                <br><br>
                <label for="resultStatus">Result Status:</label>
                <select id="resultStatus" name="resultStatus" required>
                <option value="Completed">Completed</option>    
                <option value="Pending">Pending</option>
                </select>
                <br><br>
                <input type="submit" value="Create Result" class="button">
            </form>
        </div>
    </div>

    <script>
        function openModal(order) {
            document.getElementById('orderID').value = order.OrderID;
            document.getElementById('resultModal').style.display = 'block';
        }

        function closeModal() {
            document.getElementById('resultModal').style.display = 'none';
        }

        window.onclick = function(event) {
            if (event.target == document.getElementById('resultModal')) {
                closeModal();
            }
        }
    </script>
</body>
</html>