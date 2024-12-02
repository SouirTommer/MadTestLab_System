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
                    <li><a href="secretary_insurance.php">Insurances</a></li>
                    <li><a href="secretary_result.php">Results</a></li>
                    <li><a href="secretary_bill.php">Bills</a></li>
                    <li><a href="secretary_order.php">Orders</a></li>
                    <li><a href="secretary_appointment.php">Appointments</a></li>
                    <li><a href="secretary.php">Dashboard</a></li>
                    <li><a href="Account/logout.php">Logout</a></li>
                </ul>
            </nav>
        </div>
    </header>
    <div class="container">
        <h3>All Bills</h3>
        <div id="bills-container">
            <!-- Bills will be loaded here -->
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            fetch('../database/Secretary/secretary_read_bill_action.php')
                .then(response => response.json())
                .then(bills => {
                    const container = document.getElementById('bills-container');
                    if (bills.length > 0) {
                        let table = '<table><tr><th>BillID</th><th>OrderID</th><th>Patient First Name</th><th>Patient Last Name</th><th>Lab Staff First Name</th><th>Lab Staff Last Name</th><th>Secretary First Name</th><th>Secretary Last Name</th><th>Test Name</th><th>Amount</th><th>Payment Status</th><th>Bill Date and Time</th><th>Insurance Name</th></tr>';
                        bills.forEach(bill => {
                            table += `<tr>
                                <td>${bill.BillID}</td>
                                <td>${bill.OrderID}</td>
                                <td>${bill.PatientFirstName}</td>
                                <td>${bill.PatientLastName}</td>
                                <td>${bill.LabStaffFirstName}</td>
                                <td>${bill.LabStaffLastName}</td>
                                <td>${bill.SecretaryFirstName}</td>
                                <td>${bill.SecretaryLastName}</td>
                                <td>${bill.TestName}</td>
                                <td>${bill.Amount}</td>
                                <td>${bill.PaymentStatus}</td>
                                <td>${bill.BillDateTime}</td>
                                <td>${bill.InsuranceName ?? 'None'}</td>
                            </tr>`;
                        });
                        table += '</table>';
                        container.innerHTML = table;
                    } else {
                        container.innerHTML = '<p>No bills found.</p>';
                    }
                })
                .catch(error => {
                    console.error('Error fetching bills:', error);
                    document.getElementById('bills-container').innerHTML = '<p>Error loading bills.</p>';
                });
        });
    </script>
</body>
</html>