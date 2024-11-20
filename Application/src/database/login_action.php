<?php

require_once '../connection/mysqli_conn.php';
session_start();


$aesKeys = [
    'Patient' => getenv('AES_KEY_PATIENT'),
    'Secretary' => getenv('AES_KEY_SECRETARY'),
    'LabStaff' => getenv('AES_KEY_LABSTAFF')
];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    $aesKey = $aesKeys['Patient'];
    $stmt = $conn->prepare("SELECT AccountID, AES_DECRYPT(Password, ?, IV, 'hkdf') AS DecryptedPassword, Role FROM Accounts WHERE Username = ?");
    $stmt->bind_param("ss", $aesKey, $username);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($accountId, $decryptedPassword, $role);
        $stmt->fetch();

        if (password_verify($password, $decryptedPassword)) {
            $_SESSION['username'] = $username;
            $_SESSION['accountId'] = $accountId;
            $_SESSION['role'] = $role;
            if ($role === 'Patient') {
                header("Location: ../welcome.php");
            } elseif ($role === 'secretary') {
                header("Location: ../Żecretary.php");
            } elseif ($role === 'LabStaff') {
                header("Location: ../welcome.php");
            }
            exit();
        } else {
            header("Location: ../login.php?message=Invalid%20password");
        }
    } else {
        header("Location: ../login.php?message=Invalid%20password");
    }

    $stmt->close();
    $conn->close();
}
?>