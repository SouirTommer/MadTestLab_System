<?php

require_once '../../connection/mysqli_conn_Guest.php';

$aesKeys = [
    'Patient' => getenv('AES_KEY_PATIENT'),
    'Secretary' => getenv('AES_KEY_SECRETARY'),
    'LabStaff' => getenv('AES_KEY_LABSTAFF')
];

$allowed_origins = [
    'http://localhost:5173',
    'http://localhost:3000'
];
if (isset($_SERVER['HTTP_ORIGIN']) && in_array($_SERVER['HTTP_ORIGIN'], $allowed_origins)) {
    header('Access-Control-Allow-Origin: ' . $_SERVER['HTTP_ORIGIN']);
} else {
    header('Access-Control-Allow-Origin: ' . $allowed_origins[0]); // Default to the first allowed origin
}
header('Access-Control-Allow-Methods: GET, POST, OPTIONS'); // Allow specific HTTP methods
header('Access-Control-Allow-Headers: Content-Type, Accept'); // Allow specific headers
header(header: 'Access-Control-Allow-Credentials: true'); // Allow credentials (cookies) to be sent
header(header: 'Content-Type: application/json'); // Set the content type to JSON


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    $stmt = $conn->prepare("SELECT Role FROM Accounts WHERE Username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($role);
        $stmt->fetch();
        $stmt->close();

        $aesKey = $aesKeys[$role];

        $stmt = $conn->prepare("SELECT AccountID, AES_DECRYPT(Password, ?, IV, 'hkdf') AS DecryptedPassword, Role, AccountStatus FROM Accounts WHERE Username = ?");
        $stmt->bind_param("ss", $aesKey, $username);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $stmt->bind_result($accountId, $decryptedPassword, $role, $accountStatus);
            $stmt->fetch();

            if ($accountStatus === 'Block') {
                echo json_encode(['status' => 'error', 'message' => 'Your account is blocked!']);
                exit();
            }

            if (password_verify($password, $decryptedPassword)) {
                session_start();
                $_SESSION['username'] = $username;
                $_SESSION['accountId'] = $accountId;
                $_SESSION['role'] = $role;

                setcookie('username', $username, time() + (86400 * 30), "/"); // 86400 = 1 day
                setcookie('accountId', $accountId, time() + (86400 * 30), "/");
                setcookie('role', $role, time() + (86400 * 30), "/");
                if ($role === 'LabStaff') {
                    $stmt = $conn->prepare("SELECT LabStaffType FROM LabStaffs WHERE AccountID = ?");
                    $stmt->bind_param("i", $accountId);
                    $stmt->execute();
                    $stmt->bind_result($labStaffType);
                    $stmt->fetch();
                    $stmt->close();

                    $_SESSION['labStaffType'] = $labStaffType;
                    setcookie('labStaffType', $labStaffType, time() + (86400 * 30), "/");
                }
                echo json_encode(['status' => 'success', 'username' => $username, 'accountId' => $accountId, 'role' => $role, 'labStaffType' => $labStaffType ?? null]);
                exit();
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Invalid password']);
                exit();
            }
        } else {
            echo json_encode(['status' => 'error', 'message' => 'User not found']);
            exit();
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'User not found']);
        exit();
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
    exit();
}