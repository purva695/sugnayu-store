<?php
if (session_status() !== PHP_SESSION_ACTIVE) session_start();
unset($_SESSION['sugnayu_admin_id'], $_SESSION['sugnayu_admin_name'], $_SESSION['sugnayu_admin_email'], $_SESSION['sugnayu_admin_csrf']);
session_regenerate_id(true); header('Location: login.php'); exit;
