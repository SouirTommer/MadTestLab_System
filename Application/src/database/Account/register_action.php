<?php

require_once '../../connection/mysqli_conn_Guest.php';

$aesKeys = [
    'Patient' => getenv('AES_KEY_PATIENT'),
    'PatientPrivate' => getenv('AES_KEY_PATIENT_PRIVATE'),
    'Secretary' => getenv('AES_KEY_SECRETARY'),
    'LabStaff' => getenv('AES_KEY_LABSTAFF')
];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    $role = 'Patient';
    $accountStatus = 'active';
    $firstName = trim($_POST['firstName']);
    $lastName = trim($_POST['lastName']);
    $dateOfBirth = trim($_POST['dateOfBirth']);
    $gender = trim($_POST['gender']);
    $phone = trim($_POST['phone']);
    $email = trim($_POST['email']);
    $conn->begin_transaction();

    try {

        $aesKey = $aesKeys['Patient'];
        $aesKeyPrivate = $aesKeys['PatientPrivate'];
        $iv = random_bytes(16);
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

        $stmt = $conn->prepare("INSERT INTO Accounts (Username, Password, Role, AccountStatus, IV) VALUES (?, AES_ENCRYPT(?, ?, ?, 'hkdf'), ?, ?, ?)");
        $stmt->bind_param("sssssss", $username, $hashedPassword, $aesKey, $iv, $role, $accountStatus, $iv);
        $stmt->execute();
        $accountId = $stmt->insert_id;

        $stmt = $conn->prepare("INSERT INTO Patients (AccountID, FirstName, LastName, DateOfBirth, Gender, Phone, Email) VALUES (?, ?, ?, ?, ?, AES_ENCRYPT(?, ?, ?, 'hkdf'), AES_ENCRYPT(?, ?, ?, 'hkdf'))");
        $stmt->bind_param("issssssssss", $accountId, $firstName, $lastName, $dateOfBirth, $gender, $phone, $aesKeyPrivate, $iv, $email, $aesKeyPrivate, $iv);
        $stmt->execute();

        $conn->commit();
        echo "<script type='text/javascript'> 
                alert('Register success!');
                document.location = '../../Page/Account/login.php'; 
              </script>";
        exit();
    } catch (Exception $e) {
        $conn->rollback();
        header("Location: ../../Page/Account/register.php");
        exit();
    }

    $stmt->close();
    $conn->close();
}
?>