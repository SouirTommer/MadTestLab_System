<?php
session_start();
require './Account/auth.php';
check_labstaff_type('Pathologist');
?>


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
                <li><a href="./pathologist_result.php">Results</a></li>
                <li><a href="./pathologist_order.php">Orders</a></li>
                <li><a href="./pathologist.php">Dashboard</a></li>    
                <li><a href="./Account/logout.php">Logout</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <div class="container">
        <h3>All Orders</h3>
        <table id="ordersTable">
            <thead>
                <tr>
                    <th>Order ID</th>
                    <th>Patient</th>
                    <th>Lab Staff</th>
                    <th>Secretary</th>
                    <th>Order Date</th>
                    <th>Order Status</th>
                    <th>Test Name</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <!-- Orders will be dynamically populated here -->
            </tbody>
        </table>
    </div>

    <!-- The Modal -->
    <div id="resultModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal()">&times;</span>
            <h3>Create Result</h3>
            <form id="createResultForm" action="../database/Pathologist/pathologist_create_result_action.php" method="post">
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
        document.addEventListener('DOMContentLoaded', function() {
            fetchOrders();
        });

        function fetchOrders() {
            fetch('../database/Pathologist/pathologist_read_order_action.php')
                .then(response => response.json())
                .then(data => {
                    displayOrders(data);
                })
                .catch(error => console.error('Error fetching orders:', error));
        }

        function displayOrders(orders) {
            const ordersTableBody = document.getElementById('ordersTable').getElementsByTagName('tbody')[0];
            ordersTableBody.innerHTML = '';

            orders.forEach(order => {
                const row = ordersTableBody.insertRow();
                row.innerHTML = `
                    <td>${order.OrderID}</td>
                    <td>${order.PatientFirstName} ${order.PatientLastName}</td>
                    <td>${order.LabStaffFirstName} ${order.LabStaffLastName}</td>
                    <td>${order.SecretaryFirstName} ${order.SecretaryLastName}</td>
                    <td>${order.OrderDateTime}</td>
                    <td>${order.OrderStatus}</td>
                    <td>${order.TestName}</td>
                    <td><button class="button" onclick="openModal(${order.OrderID})">Create Result</button></td>
                `;
            });
        }

        function openModal(orderID) {
            document.getElementById('orderID').value = orderID;
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