
<?php
require_once __DIR__ . '/includes/auth.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: account.php');
    exit;
}

verifyCsrf();

unset($_SESSION['customer']);
session_regenerate_id(true);

header('Location: index.php');
exit;
