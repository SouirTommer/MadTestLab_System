<?php
session_start();
require './Account/auth.php';
check_labstaff_type('Physician');
?>

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
                    <li><a href="./physician_testCatalog.php">Test Catalog</a></li>
                    <li><a href="./physician_order.php">Orders</a></li>
                    <li><a href="./physician_appointment.php">My Appointments</a></li>
                    <li><a href="./physician.php">Dashboard</a></li>
                    <li><a href="./Account/logout.php">Logout</a></li>
                </ul>
            </nav>
        </div>
    </header>
    <div class="container">
        <h3>All Orders</h3>
        <div id="orders-container">
            <!-- Orders will be loaded here -->
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            fetch('../database/Physician/physician_read_order_action.php')
                .then(response => response.json())
                .then(orders => {
                    const container = document.getElementById('orders-container');
                    if (orders.length > 0) {
                        let table = '<table><tr><th>OrderID</th><th>Patient First Name</th><th>Patient Last Name</th><th>Lab Staff First Name</th><th>Lab Staff Last Name</th><th>Secretary First Name</th><th>Secretary Last Name</th><th>Test Name</th><th>Date and Time</th><th>Status</th></tr>';
                        orders.forEach(order => {
                            table += `<tr>
                                <td>${order.OrderID}</td>
                                <td>${order.PatientFirstName}</td>
                                <td>${order.PatientLastName}</td>
                                <td>${order.LabStaffFirstName}</td>
                                <td>${order.LabStaffLastName}</td>
                                <td>${order.SecretaryFirstName}</td>
                                <td>${order.SecretaryLastName}</td>
                                <td>${order.TestName}</td>
                                <td>${order.OrderDateTime}</td>
                                <td>${order.OrderStatus}</td>
                            </tr>`;
                        });
                        table += '</table>';
                        container.innerHTML = table;
                    } else {
                        container.innerHTML = '<p>No orders found.</p>';
                    }
                })
                .catch(error => {
                    console.error('Error fetching orders:', error);
                    document.getElementById('orders-container').innerHTML = '<p>Error loading orders.</p>';
                });
        });
    </script>
</body>
</html>