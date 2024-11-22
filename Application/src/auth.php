<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

function check_login() {
    if (!isset($_SESSION['username']) || !isset($_SESSION['accountId']) || !isset($_SESSION['role'])) {
        header("Location: login.php");
        exit();
    }
}
//例子
// allow these role. (Secretary and LabStaff)
// 
// <?php
// session_start();

// require 'auth.php';
// check_role(['Secretary', 'LabStaff']);
//

function check_role($roles) {
    check_login();
    if (!in_array($_SESSION['role'], (array)$roles)) {
        $redirects = [
            'Patient' => 'patient.php',
            'Secretary' => 'secretary.php',
            'LabStaff' => 'labstaff.php',
            'default' => 'login.php'
        ];

        $role = $_SESSION['role'];
        $redirect_url = isset($redirects[$role]) ? $redirects[$role] : $redirects['default'];
        header("Location: $redirect_url");
        exit();
    }
}