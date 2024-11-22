<?php
session_start();

require 'auth.php';
check_login();

// Ensure the user is a patient
if ($_SESSION['role'] !== 'LabStaff') {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Physician Dashboard</title>
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
                <h1>Physician Dashboard</h1>
            </div>
            <nav>
                <ul>
                <li><a href="database/physician_read_testCatalog_action.php">Test Catalog</a></li>
                <li><a href="database/physician_read_order_action.php">Orders</a></li>
                    <li><a href="database/physician_read_appointment_action.php">My Appointments</a></li>
                    <li><a href="logout.php">Logout</a></li>
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