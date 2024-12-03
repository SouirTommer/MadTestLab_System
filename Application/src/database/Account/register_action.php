<?php

require_once '../../connection/mysqli_conn_Guest.php';

$aesKeys = [
    'Patient' => getenv('AES_KEY_PATIENT'),
    'PatientPrivate' => getenv('AES_KEY_PATIENT_PRIVATE'),
    'Secretary' => getenv('AES_KEY_SECRETARY'),
    'LabStaff' => getenv('AES_KEY_LABSTAFF')
];

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');
header('Content-Type: application/json');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    error_log("Received POST request");
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
        if (!$stmt) {
            throw new Exception("Prepare statement failed: " . $conn->error);
        }
        $stmt->bind_param("sssssss", $username, $hashedPassword, $aesKey, $iv, $role, $accountStatus, $iv);
        $stmt->execute();
        if ($stmt->error) {
            throw new Exception("Execute statement failed: " . $stmt->error);
        }
        $accountId = $stmt->insert_id;

        $stmt = $conn->prepare("INSERT INTO Patients (AccountID, FirstName, LastName, DateOfBirth, Gender, Phone, Email) VALUES (?, ?, ?, ?, ?, AES_ENCRYPT(?, ?, ?, 'hkdf'), AES_ENCRYPT(?, ?, ?, 'hkdf'))");
        if (!$stmt) {
            throw new Exception("Prepare statement failed: " . $conn->error);
        }
        $stmt->bind_param("issssssssss", $accountId, $firstName, $lastName, $dateOfBirth, $gender, $phone, $aesKeyPrivate, $iv, $email, $aesKeyPrivate, $iv);
        $stmt->execute();
        if ($stmt->error) {
            throw new Exception("Execute statement failed: " . $stmt->error);
        }

        $conn->commit();
        echo json_encode(['status' => 'success']);
    } catch (Exception $e) {
        $conn->rollback();
        error_log("Error: " . $e->getMessage());
        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
    }
} else {
    error_log("Invalid request method");
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
}