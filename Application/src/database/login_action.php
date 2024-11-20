<?php

require_once '../connection/mysqli_conn.php';
session_start();


$aesKeys = [
    'Patient' => getenv('AES_KEY_PATIENT'),
];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    $aesKey = $aesKeys['Patient'];
    $stmt = $conn->prepare("SELECT AccountID, AES_DECRYPT(Password, ?, IV, 'hkdf') AS DecryptedPassword FROM Accounts WHERE Username = ?");
    $stmt->bind_param("ss", $aesKey, $username);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($accountId, $decryptedPassword);
        $stmt->fetch();

        if (password_verify($password, $decryptedPassword)) {
            $_SESSION['username'] = $username;
            $_SESSION['accountId'] = $accountId;
            header("Location: ../welcome.php");
            exit();
        } else {
            echo "Invalid password";
        }
    } else {
        echo "User not found";
    }

    $stmt->close();
    $conn->close();
}
?>