<?php

require_once '../connection/mysqli_conn.php';
session_start();

// 檢查表單是否提交
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // 從表單獲取資料並進行基本的輸入驗證
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    // 準備查詢語句
    $stmt = $conn->prepare("SELECT AccountID, AES_DECRYPT(Password, 'encryption_key') AS DecryptedPassword FROM Accounts WHERE Username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($accountId, $decryptedPassword);
        $stmt->fetch();

        // 驗證密碼
        if ($password === $decryptedPassword) {
            $_SESSION['username'] = $username;
            $_SESSION['accountId'] = $accountId;
            header("Location: ../welcome.php");
            exit();
        } else {
            echo "密碼錯誤！";
        }
    } else {
        echo "使用者名稱不存在！";
    }

    $stmt->close();
    $conn->close();
}
?>