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
    <title>Patient Dashboard</title>
    <style>
        body {
            font-family: Arial, sans-serif;
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

    </style>
</head>
<body>
    <header>
        <div class="container">
            <div id="branding">
                <h1>Patient Dashboard</h1>
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
        <h2>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h2>
        <p>Use the navigation above to manage your appointments.</p>
    </div>
</body>
</html>