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
    <title>Patient Appointments</title>
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
                <h1>Patient Appointments</h1>
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
        <h3>All Appointments</h3>
        <table id="appointmentsTable">
            <thead>
                <tr>
                    <th>AppointmentID</th>
                    <th>Patient First Name</th>
                    <th>Patient Last Name</th>
                    <th>Physician First Name</th>
                    <th>Physician Last Name</th>
                    <th>Secretary First Name</th>
                    <th>Secretary Last Name</th>
                    <th>Date and Time</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <!-- Appointments will be dynamically populated here -->
            </tbody>
        </table>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            fetchAppointments();
        });

        function fetchAppointments() {
            fetch('../database/Patient/patient_read_appointment_action.php')
                .then(response => response.json())
                .then(data => {
                    console.log('Fetched data:', data); // Debugging: Log fetched data
                    displayAppointments(data);
                })
                .catch(error => console.error('Error fetching appointments:', error));
        }

        function displayAppointments(appointments) {
            const appointmentsTableBody = document.getElementById('appointmentsTable').getElementsByTagName('tbody')[0];
            appointmentsTableBody.innerHTML = '';

            appointments.forEach(appointment => {
                const row = appointmentsTableBody.insertRow();
                row.innerHTML = `
                    <td>${appointment.AppointmentID}</td>
                    <td>${appointment.PatientFirstName}</td>
                    <td>${appointment.PatientLastName}</td>
                    <td>${appointment.PhysicianFirstName}</td>
                    <td>${appointment.PhysicianLastName}</td>
                    <td>${appointment.SecretaryFirstName}</td>
                    <td>${appointment.SecretaryLastName}</td>
                    <td>${appointment.AppointmentDateTime}</td>
                    <td>${appointment.AppointmentsStatus}</td>
                `;
            });
        }
    </script>
</body>
</html>