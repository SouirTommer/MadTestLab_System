<?php
session_start();
require './Account/auth.php';
check_role(['Patient']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Patient Results</title>
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
                <h1>Patient Results</h1>
            </div>
            <nav>
                <ul>
                    <li><a href="./patient_result.php">Results</a></li>
                    <li><a href="./patient_bill.php">Bills</a></li>
                    <li><a href="./patient_order.php">ORDERS</a></li>
                    <li><a href="./patient_appointment.php">APPOINTMENTS</a></li>
                    <li><a href="./patient.php">Dashboard</a></li>
                    <li><a href="./Account/logout.php">Logout</a></li>
                </ul>
            </nav>
        </div>
    </header>
    <div class="container">
        <h3>All Results</h3>
        <div id="results-container">
            <!-- Results will be loaded here -->
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            fetch('../database/Patient/patient_read_result_action.php')
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok ' + response.statusText);
                    }
                    return response.json();
                })
                .then(results => {
                    console.log('Fetched results:', results); // Debugging: Log fetched results
                    const container = document.getElementById('results-container');
                    if (results.length > 0) {
                        let table = '<table><tr><th>ResultID</th><th>OrderID</th><th>Report URL</th><th>Interpretation</th><th>Result Date and Time</th><th>Result Status</th><th>Lab Staff</th></tr>';
                        results.forEach(result => {
                            table += `<tr>
                                <td>${result.ResultID}</td>
                                <td>${result.OrderID}</td>
                                <td><a href="${result.ReportURL}" target="_blank">View Report</a></td>
                                <td>${result.Interpretation}</td>
                                <td>${result.ResultDateTime}</td>
                                <td>${result.ResultStatus}</td>
                                <td>${result.LabStaffFirstName} ${result.LabStaffLastName}</td>
                            </tr>`;
                        });
                        table += '</table>';
                        container.innerHTML = table;
                    } else {
                        container.innerHTML = '<p>No results found.</p>';
                    }
                })
                .catch(error => {
                    console.error('Error fetching results:', error);
                    document.getElementById('results-container').innerHTML = '<p>Error loading results.</p>';
                });
        });
    </script>
</body>
</html>