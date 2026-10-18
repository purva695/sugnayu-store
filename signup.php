
<?php
require_once __DIR__ . '/includes/auth.php';

if (isCustomerLoggedIn()) {
    header('Location: account.php');
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verifyCsrf();

    $name = trim($_POST['full_name'] ?? '');
    $email = strtolower(trim($_POST['email'] ?? ''));
    $phone = trim($_POST['phone'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm = $_POST['confirm_password'] ?? '';

    if ($name === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Please enter a valid name and email.';
    } elseif (!preg_match('/^[0-9]{10}$/', $phone)) {
        $error = 'Enter a valid 10-digit mobile number.';
    } elseif (strlen($password) < 8) {
        $error = 'Password must contain at least 8 characters.';
    } elseif ($password !== $confirm) {
        $error = 'Passwords do not match.';
    } else {
        try {
            $hash = password_hash($password, PASSWORD_DEFAULT);

            $stmt = $pdo->prepare(
                'INSERT INTO customers
                 (full_name, email, phone, password_hash)
                 VALUES (?, ?, ?, ?)'
            );
            $stmt->execute([$name, $email, $phone, $hash]);

            session_regenerate_id(true);
            $_SESSION['customer'] = [
                'id' => (int)$pdo->lastInsertId(),
                'full_name' => $name,
                'email' => $email,
                'phone' => $phone
            ];

            header('Location: account.php');
            exit;
        } catch (PDOException $e) {
            if ($e->getCode() === '23000') {
                $error = 'This email is already registered. Please log in.';
            } else {
                error_log($e->getMessage());
                $error = 'Account could not be created. Please try again.';
            }
        }
    }
}

$pageTitle = 'Create Account | SUGNAYU';
require __DIR__ . '/includes/header.php';
?>

<main class="auth-page">
  <section class="auth-card">
    <h1>Create your account</h1>
    <p>Join SUGNAYU to manage your orders.</p>

    <?php if ($error): ?>
      <p class="auth-error"><?= e($error) ?></p>
    <?php endif; ?>

    <form method="post" action="signup.php">
      <?= csrfField() ?>

      <label>Full name</label>
      <input type="text" name="full_name" maxlength="120"
             autocomplete="name" required>

      <label>Email address</label>
      <input type="email" name="email" maxlength="190"
             autocomplete="email" required>

      <label>Mobile number</label>
      <input type="tel" name="phone" inputmode="numeric"
             pattern="[0-9]{10}" maxlength="10"
             autocomplete="tel" required>

      <label>Password</label>
      <input type="password" name="password" minlength="8"
             autocomplete="new-password" required>

      <label>Confirm password</label>
      <input type="password" name="confirm_password" minlength="8"
             autocomplete="new-password" required>

      <button class="btn btn-primary" type="submit">
        Create Account
      </button>
    </form>

    <p>Already registered? <a href="login.php">Log in</a></p>
  </section>
</main>

<?php require __DIR__ . '/includes/footer.php'; ?>
