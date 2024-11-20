<?php

require_once '../connection/mysqli_conn.php';

$aesKeys = [
    'Patient' => getenv('AES_KEY_PATIENT'),
];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    $role = 'Patient';
    $accountStatus = 'active';
    $credentials = 'default';
    $firstName = trim($_POST['firstName']);
    $lastName = trim($_POST['lastName']);
    $dateOfBirth = trim($_POST['dateOfBirth']);
    $gender = trim($_POST['gender']);
    $phone = trim($_POST['phone']);
    $email = trim($_POST['email']);
    $conn->begin_transaction();

    try {

        $aesKey = $aesKeys['Patient'];
        $iv = random_bytes(16);
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

        $stmt = $conn->prepare("INSERT INTO Accounts (Username, Password, Role, AccountStatus, Credentials, IV) VALUES (?, AES_ENCRYPT(?, ?, ?, 'hkdf'), ?, ?, ?, ?)");
        $stmt->bind_param("ssssssss", $username, $hashedPassword, $aesKey, $iv, $role, $accountStatus, $credentials, $iv);
        $stmt->execute();
        $accountId = $stmt->insert_id;

        $stmt = $conn->prepare("INSERT INTO Patients (AccountID, FirstName, LastName, DateOfBirth, Gender, Phone, Email) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("issssss", $accountId, $firstName, $lastName, $dateOfBirth, $gender, $phone, $email);
        $stmt->execute();

        $conn->commit();

        header("Location: ../login.php?message=Register%20success!");
        exit();
    } catch (Exception $e) {
        $conn->rollback();
        echo "register failed：" . $e->getMessage();
    }

    $stmt->close();
    $conn->close();
}
?>