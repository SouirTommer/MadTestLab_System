
<?php
session_start();

require './Page/Account/auth.php';
check_role(['Patient', 'Secretary', 'LabStaff']);
?>