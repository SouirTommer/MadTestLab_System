<?php

require_once '../../connection/mysqli_conn_Guest.php';

$aesKeys = [
    'Patient' => getenv('AES_KEY_PATIENT'),
    'Secretary' => getenv('AES_KEY_SECRETARY'),
    'LabStaff' => getenv('AES_KEY_LABSTAFF')
];

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Accept');
header('Content-Type: application/json');


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
            
                echo json_encode(['status' => 'success', 'username' => $username, 'accountId' => $accountId, 'role' => $role]);
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