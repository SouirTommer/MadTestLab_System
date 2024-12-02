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
    <title>Insurance Records</title>
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
                <h1>Insurance Records</h1>
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
        <h3>All Insurances</h3>
        <div id="insurances-container">
            <!-- Insurances will be loaded here -->
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            fetch('../database/Secretary/secretary_read_insurance_action.php')
                .then(response => response.json())
                .then(data => {
                    const insurances = data.insurances || [];
                    const container = document.getElementById('insurances-container');
                    if (insurances.length > 0) {
                        let table = '<table><tr><th>InsuranceID</th><th>Insurance Name</th><th>Insurance Details</th></tr>';
                        insurances.forEach(insurance => {
                            table += `<tr>
                                <td>${insurance.InsuranceID}</td>
                                <td>${insurance.InsuranceName}</td>
                                <td>${insurance.InsuranceDetails}</td>
                            </tr>`;
                        });
                        table += '</table>';
                        container.innerHTML = table;
                    } else {
                        container.innerHTML = '<p>No insurances found.</p>';
                    }
                })
                .catch(error => {
                    console.error('Error fetching insurances:', error);
                    document.getElementById('insurances-container').innerHTML = '<p>Error loading insurances.</p>';
                });
        });
    </script>
</body>
</html>