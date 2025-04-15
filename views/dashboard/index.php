<?php
require_once '../../includes/session.php';
require_once '../../includes/auth_check.php';

echo "<h2>Welcome to your dashboard, " . ucfirst($_SESSION['role']) . "!</h2>";
?>