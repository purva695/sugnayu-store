
<?php
require_once __DIR__ . '/includes/auth.php';

if (isCustomerLoggedIn()) {
    header('Location: account.php');
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verifyCsrf();

    $email = strtolower(trim($_POST['email'] ?? ''));
    $password = $_POST['password'] ?? '';

    if (!filter_var($email, FILTER_VALIDATE_EMAIL) || $password === '') {
        $error = 'Enter your email and password.';
    } else {
        $stmt = $pdo->prepare(
            'SELECT id, full_name, email, phone, password_hash
             FROM customers WHERE email = ? LIMIT 1'
        );
        $stmt->execute([$email]);
        $customer = $stmt->fetch();

        if ($customer && password_verify($password, $customer['password_hash'])) {
            session_regenerate_id(true);

            $_SESSION['customer'] = [
                'id' => (int)$customer['id'],
                'full_name' => $customer['full_name'],
                'email' => $customer['email'],
                'phone' => $customer['phone']
            ];

            $redirect = $_SESSION['redirect_after_login'] ?? 'account.php';
            unset($_SESSION['redirect_after_login']);

            // Only allow local relative paths, not external URLs.
            if (!is_string($redirect) ||
                $redirect === '' ||
                str_starts_with($redirect, '//') ||
                preg_match('/^[a-z][a-z0-9+.-]*:/i', $redirect)) {
                $redirect = 'account.php';
            }

            header('Location: ' . $redirect);
            exit;
        } else {
            $error = 'Invalid email or password.';
        }
    }
}

$pageTitle = 'Login | SUGNAYU';
require __DIR__ . '/includes/header.php';
?>

<main class="auth-page">
  <section class="auth-card">
    <h1>Welcome back</h1>
    <p>Log in to your SUGNAYU account.</p>

    <?php if ($error): ?>
      <p class="auth-error"><?= e($error) ?></p>
    <?php endif; ?>

    <form method="post" action="login.php">
      <?= csrfField() ?>

      <label>Email address</label>
      <input type="email" name="email" autocomplete="email" required>

      <label>Password</label>
      <input type="password" name="password"
             autocomplete="current-password" required>

      <button class="btn btn-primary" type="submit">Login</button>
    </form>

    <p>New to SUGNAYU? <a href="signup.php">Create an account</a></p>
  </section>
</main>

<?php require __DIR__ . '/includes/footer.php'; ?>
