<?php
session_start();
require './Account/auth.php';
check_role(['Secretary']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Records</title>
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
                <h1>Order Records</h1>
            </div>
            <nav>
            <ul>
                    
            <li><a href="../database/Secretary/secretary_read_insurance_action.php">Insurances</a></li>
                <li><a href="../database/Secretary/secretary_read_bill_action.php">Bills</a></li>
                    <li><a href="../database/Secretary/secretary_read_order_action.php">Orders</a></li>
                    <li><a href="../database/Secretary/secretary_read_appointment_action.php">Appointments</a></li>
                    <li><a href="./secretary.php">Dashboard</a></li>
                    <li><a href="./Account/logout.php">Logout</a></li>
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
                            <button class="button" onclick="openModal(<?php echo htmlspecialchars(json_encode($order)); ?>)">Create Bill</button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </table>
        <?php else: ?>
            <p>No orders found.</p>
        <?php endif; ?>
    </div>

    <!-- The Modal -->
    <div id="billModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal()">&times;</span>
            <h3>Create Bill</h3>
<form id="createBillForm" action="../database/Secretary/secretary_create_bill_action.php" method="post">
<input type="hidden" id="orderID" name="orderID">
<label for="patient">Patient:</label>
<input type="text" id="patient" name="patient" readonly>
<br><br>
<label for="testName">Test Name:</label>
<input type="text" id="testName" name="testName" readonly>
<br><br>
<label for="testPrice">Test Price:</label>
<input type="text" id="testPrice" name="testPrice" readonly>
<br><br>
<label for="insurance">Insurance:</label>
<br>
<input type="radio" id="insuranceNone" name="insurance" value="" checked onchange="updateInsuranceAmount()"> None<br>
<input type="radio" id="insurance1" name="insurance" value="1" data-amount="100000.00" onchange="updateInsuranceAmount()"> 1<br>
<input type="radio" id="insurance2" name="insurance" value="2" data-amount="150000.00" onchange="updateInsuranceAmount()"> 2<br>
<input type="radio" id="insurance3" name="insurance" value="3" data-amount="80000.00" onchange="updateInsuranceAmount()"> 3<br>
<input type="radio" id="insurance4" name="insurance" value="4" data-amount="50000.00" onchange="updateInsuranceAmount()"> 4<br>
<input type="radio" id="insurance5" name="insurance" value="5" data-amount="200000.00" onchange="updateInsuranceAmount()"> 5<br>
<input type="radio" id="insurance6" name="insurance" value="6" data-amount="60000.00" onchange="updateInsuranceAmount()"> 6<br>
<br>
<label for="insuranceAmount">Insurance Amount:</label>
<input type="text" id="insuranceAmount" name="insuranceAmount" readonly>
<br><br>
<label for="Amount">Amount:</label>
<input type="text" id="Amount" name="amount" readonly>
<br><br>
<label for="status">Status:</label>
<select id="status" name="status" required>
    <option value="Paid">Paid</option>
    <option value="Cancelled">Cancelled</option>
</select>
<br><br>
<input type="submit" value="Create Bill" class="button">
</form>
</div>
</div>

<script>
    function openModal(order) {
        document.getElementById('orderID').value = order.OrderID;
        document.getElementById('patient').value = order.PatientFirstName + ' ' + order.PatientLastName;
        document.getElementById('testName').value = order.TestName;
        document.getElementById('testPrice').value = order.TestPrice;
        document.getElementById('insuranceNone').checked = true;
        document.getElementById('insuranceAmount').value = "0";
        document.getElementById('Amount').value = order.TestPrice;
        document.getElementById('billModal').style.display = 'block';
    }

    function closeModal() {
        document.getElementById('billModal').style.display = 'none';
    }

    function updateInsuranceAmount() {
        var insuranceAmount = 0;
        var testPrice = parseFloat(document.getElementById('testPrice').value);

        if (document.getElementById('insurance1').checked) {
            insuranceAmount = parseFloat(document.getElementById('insurance1').getAttribute('data-amount'));
        } else if (document.getElementById('insurance2').checked) {
            insuranceAmount = parseFloat(document.getElementById('insurance2').getAttribute('data-amount'));
        } else if (document.getElementById('insurance3').checked) {
            insuranceAmount = parseFloat(document.getElementById('insurance3').getAttribute('data-amount'));
        } else if (document.getElementById('insurance4').checked) {
            insuranceAmount = parseFloat(document.getElementById('insurance4').getAttribute('data-amount'));
        } else if (document.getElementById('insurance5').checked) {
            insuranceAmount = parseFloat(document.getElementById('insurance5').getAttribute('data-amount'));
        } else if (document.getElementById('insurance6').checked) {
            insuranceAmount = parseFloat(document.getElementById('insurance6').getAttribute('data-amount'));
        }

        document.getElementById('insuranceAmount').value = insuranceAmount;
        var Amount = insuranceAmount >= testPrice ? 0 : testPrice;
        document.getElementById('Amount').value = Amount;
    }

    window.onclick = function(event) {
        if (event.target == document.getElementById('billModal')) {
            closeModal();
        }
    }
</script>