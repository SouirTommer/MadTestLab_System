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
                echo "<script type='text/javascript'> 
                        alert('Your account is blocked!');
                        document.location = '../login.php'; 
                      </script>";
                exit();
            }

            if (password_verify($password, $decryptedPassword)) {
                $_SESSION['username'] = $username;
                $_SESSION['accountId'] = $accountId;
                $_SESSION['role'] = $role;
                if ($role === 'Patient') {
                    header("Location: ../patient.php");
                } elseif ($role === 'Secretary') {
                    header("Location: ../secretary.php");
                } elseif ($role === 'LabStaff') {
                    header("Location: ../welcome.php");
                }
                exit();
            } else {
                echo "<script type='text/javascript'> 
                        alert('Invalid password!');
                        document.location = '../login.php'; 
                      </script>";
                exit();
            }
        } else {
            echo "<script type='text/javascript'> 
                    alert('Invalid password!');
                    document.location = '../login.php'; 
                  </script>";
            exit();
        }

        $stmt->close();
    } else {
        echo "<script type='text/javascript'> 
                alert('Invalid password!');
                document.location = '../login.php'; 
              </script>";
        exit();
    }

    $conn->close();
}
?>