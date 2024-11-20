<?php
session_start();

// 檢查使用者是否已登入
if (isset($_SESSION['username'])) {
    header("Location: welcome.php");
} else {
    header("Location: login.php");
}
exit();
?>