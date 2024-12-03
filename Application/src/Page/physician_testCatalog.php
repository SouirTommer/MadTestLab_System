<?php
session_start();
require './Account/auth.php';
// check_labstaff_type('Physician');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test Catalog</title>
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
                <h1>Test Catalog</h1>
            </div>
            <nav>
                <ul>
                    <li><a href="./physician_testCatalog.php">Test Catalog</a></li>
                    <li><a href="./physician_order.php">Orders</a></li>
                    <li><a href="./physician_appointment.php">MY Appointments</a></li>
                    <li><a href="./physician.php">Dashboard</a></li>
                    <li><a href="./Account/logout.php">Logout</a></li>
                </ul>
            </nav>
        </div>
    </header>
    <div class="container">
        <h3>All Tests</h3>
        <div id="tests-container">
            <!-- Tests will be loaded here -->
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            fetch('../database/Physician/physician_read_testCatalog_action.php')
                .then(response => response.json())
                .then(tests => {
                    const container = document.getElementById('tests-container');
                    if (tests.length > 0) {
                        let table = '<table><tr><th>Test Code</th><th>Test Name</th><th>Description</th><th>Price</th><th>Test Type</th></tr>';
                        tests.forEach(test => {
                            table += `<tr>
                                <td>${test.TestCode}</td>
                                <td>${test.TestName}</td>
                                <td>${test.Description}</td>
                                <td>${test.Price}</td>
                                <td>${test.TestType}</td>
                            </tr>`;
                        });
                        table += '</table>';
                        container.innerHTML = table;
                    } else {
                        container.innerHTML = '<p>No tests found.</p>';
                    }
                })
                .catch(error => {
                    console.error('Error fetching tests:', error);
                    document.getElementById('tests-container').innerHTML = '<p>Error loading tests.</p>';
                });
        });
    </script>
</body>
</html>