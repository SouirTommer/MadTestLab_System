<?php

require_once '../connection/mysqli_conn.php';

// 檢查表單是否提交
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // 從表單獲取資料並進行基本的輸入驗證
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    $role = 'user'; // 假設角色為 'user'
    $accountStatus = 'active'; // 假設帳戶狀態為 'active'
    $credentials = 'default'; // 假設憑證為 'default'
    $firstName = trim($_POST['firstName']);
    $lastName = trim($_POST['lastName']);
    $dateOfBirth = trim($_POST['dateOfBirth']);
    $gender = trim($_POST['gender']);
    $phone = trim($_POST['phone']);
    $email = trim($_POST['email']);

    // 開始交易
    $conn->begin_transaction();

    try {
        // 插入 Accounts 資料表
        $stmt = $conn->prepare("INSERT INTO Accounts (Username, Password, Role, AccountStatus, Credentials, IV) VALUES (?, AES_ENCRYPT(?, 'encryption_key'), ?, ?, ?, UUID_TO_BIN(UUID()))");
        $stmt->bind_param("sssss", $username, $password, $role, $accountStatus, $credentials);
        $stmt->execute();
        $accountId = $stmt->insert_id;

        // 插入 Patients 資料表
        $stmt = $conn->prepare("INSERT INTO Patients (AccountID, FirstName, LastName, DateOfBirth, Gender, Phone, Email) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("issssss", $accountId, $firstName, $lastName, $dateOfBirth, $gender, $phone, $email);
        $stmt->execute();

        // 提交交易
        $conn->commit();

        echo "註冊成功！";
    } catch (Exception $e) {
        // 回滾交易
        $conn->rollback();
        echo "註冊失敗：" . $e->getMessage();
    }

    $stmt->close();
    $conn->close();
}
?>