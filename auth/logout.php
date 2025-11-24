<?php
session_start();
session_unset();
session_destroy();
header("Location: /job_order_tracking_system/auth/login.php");
exit();
?>