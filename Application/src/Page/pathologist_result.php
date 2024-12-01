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
                <li><a href="./pathologist_result.php">Results</a></li>
                <li><a href="./pathologist_order.php">Orders</a></li>
                <li><a href="./pathologist.php">Dashboard</a></li>    
                <li><a href="./Account/logout.php">Logout</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <div class="container">
        <h2>All Results</h2>
        <table id="resultsTable">
            <thead>
                <tr>
                    <th>Result ID</th>
                    <th>Order ID</th>
                    <th>Patient</th>
                    <th>Lab Staff</th>
                    <th>Report URL</th>
                    <th>Interpretation</th>
                    <th>Result Date</th>
                    <th>Result Status</th>
                </tr>
            </thead>
            <tbody>
                <!-- Results will be dynamically populated here -->
            </tbody>
        </table>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            fetchResults();
        });

        function fetchResults() {
            fetch('../database/Pathologist/pathologist_read_result_action.php')
                .then(response => response.json())
                .then(data => {
                    displayResults(data);
                })
                .catch(error => console.error('Error fetching results:', error));
        }

        function displayResults(results) {
            const resultsTableBody = document.getElementById('resultsTable').getElementsByTagName('tbody')[0];
            resultsTableBody.innerHTML = '';

            results.forEach(result => {
                const row = resultsTableBody.insertRow();
                row.innerHTML = `
                    <td>${result.ResultID}</td>
                    <td>${result.OrderID}</td>
                    <td>${result.PatientFirstName} ${result.PatientLastName}</td>
                    <td>${result.LabStaffFirstName} ${result.LabStaffLastName}</td>
                    <td><a href="${result.ReportURL}" target="_blank">View Report</a></td>
                    <td>${result.Interpretation}</td>
                    <td>${result.ResultDateTime}</td>
                    <td>${result.ResultStatus}</td>
                `;
            });
        }
    </script>
</body>
</html>