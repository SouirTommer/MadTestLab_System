<?php

require_once '../../connection/mysqli_conn_Guest.php';
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
                        document.location = '../../Page/Account/login.php'; 
                      </script>";
                exit();
            }

            if (password_verify($password, $decryptedPassword)) {
                $_SESSION['username'] = $username;
                $_SESSION['accountId'] = $accountId;
                $_SESSION['role'] = $role;
                if ($role === 'Patient') {
                    header("Location: ../../Page/patient.php");
                } elseif ($role === 'Secretary') {
                    header("Location: ../../Page/secretary.php");
                } elseif ($role === 'LabStaff') {
                    // Check LabStaffType
                    $stmt = $conn->prepare("SELECT LabStaffType FROM LabStaffs WHERE AccountID = ?");
                    $stmt->bind_param("i", $accountId);
                    $stmt->execute();
                    $stmt->bind_result($labStaffType);
                    $stmt->fetch();
                    if ($labStaffType === 'Physician') {
                        $_SESSION['labStaffType'] = $labStaffType;
                        header("Location: ../../Page/physician.php");
                    } elseif ($labStaffType === 'Pathologist') {
                        $_SESSION['labStaffType'] = $labStaffType;
                        header("Location: ../../Page/pathologist.php");
                    } else {
                        echo "<script type='text/javascript'> 
                                alert('Unauthorized LabStaff type!');
                                document.location = '../../Page/Account/login.php'; 
                              </script>";
                    }
                    $stmt->close();
                }
                exit();
            } else {
                echo "<script type='text/javascript'> 
                        alert('Invalid password!');
                        document.location = '../../Page/Account/login.php'; 
                      </script>";
                exit();
            }
        } else {
            echo "<script type='text/javascript'> 
                    alert('Invalid password!');
                    document.location = '../../Page/Account/login.php'; 
                  </script>";
            exit();
        }

        $stmt->close();
    } else {
        echo "<script type='text/javascript'> 
                alert('Invalid password!');
                document.location = '../../Page/Account/login.php'; 
              </script>";
        exit();
    }

    $conn->close();
}
?>