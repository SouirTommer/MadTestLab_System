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
    <title>Order Records</title>
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
                <h1>Order Records</h1>
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
        <h3>All Orders</h3>
        <div id="orders-container">
            <!-- Orders will be loaded here -->
        </div>
    </div>

    <!-- Create Bill Modal -->
<!-- Create Bill Modal -->
<div id="createBillModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeBillModal()">&times;</span>
        <h3>Create Bill</h3>
        <form id="createBillForm" action="../database/Secretary/secretary_create_bill_action.php" method="post">
            <input type="hidden" id="createBillOrderID" name="orderID">
            <label for="insurance">Insurance:</label>
            <select id="insurance" name="insuranceID" required>
                <!-- Insurance options will be populated here -->
            </select>
            <br><br>
            <label for="insuranceAmount">Insurance Amount:</label>
            <input type="number" id="insuranceAmount" name="insuranceAmount" readonly>
            <br><br>
            <label for="testName">Test Name:</label>
            <input type="text" id="testName" name="testName" readonly>
            <br><br>
            <label for="testAmount">Test Amount:</label>
            <input type="number" id="testAmount" name="testAmount" readonly>
            <br><br>
            <label for="billAmount">Bill Amount:</label>
            <input type="number" id="billAmount" name="amount" readonly>
            <br><br>
            <label for="paymentStatus">Payment Status:</label>
            <select id="paymentStatus" name="status" required>
                <option value="Paid">Paid</option>
                <option value="Unpaid">Unpaid</option>
            </select>
            <br><br>
            <input type="submit" value="Create Bill" class="button">
        </form>
    </div>
</div>

    <script>
document.addEventListener('DOMContentLoaded', function() {
    fetch('../database/Secretary/secretary_read_order_action.php')
        .then(response => response.json())
        .then(data => {
            const orders = data.orders || [];
            const insurances = data.insurances || [];
            const testsCatalog = data.testsCatalog || [];

            // Populate orders table
            const ordersContainer = document.getElementById('orders-container');
            if (orders.length > 0) {
                let table = '<table><tr><th>OrderID</th><th>Patient Name</th><th>Lab Staff Name</th><th>Secretary Name</th><th>Test Name</th><th>Order Date and Time</th><th>Order Status</th><th>Action</th></tr>';
                orders.forEach(order => {
                    table += `<tr>
                        <td>${order.OrderID}</td>
                        <td>${order.PatientFirstName} ${order.PatientLastName}</td>
                        <td>${order.LabStaffFirstName} ${order.LabStaffLastName}</td>
                        <td>${order.SecretaryFirstName} ${order.SecretaryLastName}</td>
                        <td>${order.TestName}</td>
                        <td>${order.OrderDateTime}</td>
                        <td>${order.OrderStatus}</td>
                        <td><button class="button" onclick='openBillModal(${JSON.stringify(order)}, ${JSON.stringify(insurances)}, ${JSON.stringify(testsCatalog)})'>Create Bill</button></td>
                    </tr>`;
                });
                table += '</table>';
                ordersContainer.innerHTML = table;
            } else {
                ordersContainer.innerHTML = '<p>No orders found.</p>';
            }
        })
        .catch(error => {
            console.error('Error fetching data:', error);
            document.getElementById('orders-container').innerHTML = '<p>Error loading orders.</p>';
        });

        document.getElementById('createBillForm').addEventListener('submit', function(event) {
    event.preventDefault();
    const formData = new FormData(this);

    // Log form data for debugging
    for (let [key, value] of formData.entries()) {
        console.log(key, value);
    }

    fetch('../database/Secretary/secretary_create_bill_action.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.text()) // Change to text to inspect the raw response
    .then(data => {
        try {
            const jsonData = JSON.parse(data);
            if (jsonData.status === 'error') {
                console.error('Error creating bill:', jsonData.message);
                alert('Error creating bill: ' + jsonData.message);
            } else {
                alert('Bill created successfully!');
                location.reload();
            }
        } catch (e) {
            console.error('Error parsing JSON:', e);
            console.error('Response data:', data);
            alert('Error creating bill: Invalid JSON response');
        }
    })
    .catch(error => {
        console.error('Error creating bill:', error);
        alert('Error creating bill: ' + error);
    });
});
});

function openBillModal(order, insurances, testsCatalog) {
    document.getElementById('createBillOrderID').value = order.OrderID;
    document.getElementById('testName').value = order.TestName;
    const test = testsCatalog.find(test => test.TestName === order.TestName);
    document.getElementById('testAmount').value = test ? test.Price : 0;

    const insuranceSelect = document.getElementById('insurance');
    insuranceSelect.innerHTML = '';
    insurances.forEach(insurance => {
        const option = document.createElement('option');
        option.value = insurance.InsuranceID;
        option.textContent = `${insurance.InsuranceName} - ${insurance.InsuranceAmount}`;
        insuranceSelect.appendChild(option);
    });

    insuranceSelect.addEventListener('change', function() {
        const selectedInsurance = insurances.find(insurance => insurance.InsuranceID == this.value);
        const insuranceAmount = selectedInsurance ? selectedInsurance.InsuranceAmount : 0;
        document.getElementById('insuranceAmount').value = insuranceAmount;
        const testAmount = parseFloat(document.getElementById('testAmount').value);
        const billAmount = Math.max(0, testAmount - insuranceAmount);
        document.getElementById('billAmount').value = billAmount;
    });

    document.getElementById('createBillModal').style.display = 'block';
}

function closeBillModal() {
    document.getElementById('createBillModal').style.display = 'none';
}

window.onclick = function(event) {
    if (event.target == document.getElementById('createBillModal')) {
        closeBillModal();
    }
}
    </script>
</body>
</html>