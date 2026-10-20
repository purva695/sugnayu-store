
<?php
require_once __DIR__ . '/includes/auth.php';
requireCustomerLogin();

$customer = currentCustomer();
$email = $customer['email'];
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$message = '';
$error = '';
$editAddress = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    try {
        if ($action === 'save') {
            $id = (int)($_POST['id'] ?? 0);
            $name = trim($_POST['full_name'] ?? '');
            $phone = trim($_POST['phone'] ?? '');
            $line = trim($_POST['address_line'] ?? '');
            $landmark = trim($_POST['landmark'] ?? '');
            $city = trim($_POST['city'] ?? '');
            $state = trim($_POST['state'] ?? '');
            $postal = trim($_POST['postal_code'] ?? '');
            $type = $_POST['address_type'] ?? 'Home';
            $lat = $_POST['latitude'] ?? null;
            $lng = $_POST['longitude'] ?? null;
            $isDefault = isset($_POST['is_default']) ? 1 : 0;

            if (
                $name === '' || $phone === '' || $line === '' ||
                $city === '' || $state === '' ||
                !preg_match('/^[0-9]{6}$/', $postal)
            ) {
                throw new Exception(
                    'Please enter your name, phone number, complete address, city, state, and a valid 6-digit PIN code.'
                );
            }

            if (!in_array($type, ['Home', 'Work', 'Other'], true)) {
                $type = 'Home';
            }

            $lat = is_numeric($lat) ? (float)$lat : null;
            $lng = is_numeric($lng) ? (float)$lng : null;

            $pdo->beginTransaction();

            if ($isDefault) {
                $stmt = $pdo->prepare(
                    'UPDATE customer_addresses
                     SET is_default = 0 WHERE customer_email = ?'
                );
                $stmt->execute([$email]);
            }

            if ($id > 0) {
                $stmt = $pdo->prepare(
                    'UPDATE customer_addresses
                     SET full_name=?, phone=?, address_line=?, landmark=?,
                         city=?, state=?, postal_code=?, address_type=?,
                         latitude=?, longitude=?, is_default=?
                     WHERE id=? AND customer_email=?'
                );

                $stmt->execute([
                    $name, $phone, $line, $landmark ?: null,
                    $city, $state, $postal, $type,
                    $lat, $lng, $isDefault, $id, $email
                ]);
            } else {
                $stmt = $pdo->prepare(
                    'INSERT INTO customer_addresses
                     (customer_email, full_name, phone, address_line,
                      landmark, city, state, postal_code, address_type,
                      latitude, longitude, is_default)
                     VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)'
                );

                $stmt->execute([
                    $email, $name, $phone, $line, $landmark ?: null,
                    $city, $state, $postal, $type,
                    $lat, $lng, $isDefault
                ]);
            }

            // Agar customer ka pehla address hai, use default bana do.
            $stmt = $pdo->prepare(
                'SELECT COUNT(*) FROM customer_addresses
                 WHERE customer_email = ? AND is_default = 1'
            );
            $stmt->execute([$email]);

            if ((int)$stmt->fetchColumn() === 0) {
                $stmt = $pdo->prepare(
                    'UPDATE customer_addresses SET is_default=1
                     WHERE customer_email=? ORDER BY id DESC LIMIT 1'
                );
                $stmt->execute([$email]);
            }

            $pdo->commit();
            header('Location: address.php?saved=1');
            exit;
        }

        if ($action === 'delete') {
            $id = (int)($_POST['id'] ?? 0);

            $stmt = $pdo->prepare(
                'DELETE FROM customer_addresses
                 WHERE id = ? AND customer_email = ?'
            );
            $stmt->execute([$id, $email]);

            // Agar default address delete hua, doosra address default banao.
            $stmt = $pdo->prepare(
                'SELECT COUNT(*) FROM customer_addresses
                 WHERE customer_email = ? AND is_default = 1'
            );
            $stmt->execute([$email]);

            if ((int)$stmt->fetchColumn() === 0) {
                $stmt = $pdo->prepare(
                    'UPDATE customer_addresses SET is_default=1
                     WHERE customer_email=? ORDER BY id DESC LIMIT 1'
                );
                $stmt->execute([$email]);
            }

            header('Location: address.php?deleted=1');
            exit;
        }

        if ($action === 'default') {
            $id = (int)($_POST['id'] ?? 0);
            $pdo->beginTransaction();

            $stmt = $pdo->prepare(
                'UPDATE customer_addresses SET is_default=0
                 WHERE customer_email=?'
            );
            $stmt->execute([$email]);

            $stmt = $pdo->prepare(
                'UPDATE customer_addresses SET is_default=1
                 WHERE id=? AND customer_email=?'
            );
            $stmt->execute([$id, $email]);

            $pdo->commit();
            header('Location: address.php');
            exit;
        }
    } catch (Throwable $e) {
        if ($pdo->inTransaction()) {
            $pdo->rollBack();
        }
        $error = 'Unable to save the address. Please check your details and try again.';
    }
}

if (isset($_GET['edit'])) {
    $stmt = $pdo->prepare(
        'SELECT * FROM customer_addresses
         WHERE id=? AND customer_email=?'
    );
    $stmt->execute([(int)$_GET['edit'], $email]);
    $editAddress = $stmt->fetch(PDO::FETCH_ASSOC);
}

$stmt = $pdo->prepare(
    'SELECT * FROM customer_addresses
     WHERE customer_email=?
     ORDER BY is_default DESC, id DESC'
);
$stmt->execute([$email]);
$addresses = $stmt->fetchAll(PDO::FETCH_ASSOC);

function addressEscape($value) {
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}

$form = $editAddress ?: [
    'id' => 0,
    'full_name' => $customer['full_name'] ?? '',
    'phone' => '',
    'address_line' => '',
    'landmark' => '',
    'city' => '',
    'state' => '',
    'postal_code' => '',
    'address_type' => 'Home',
    'latitude' => '',
    'longitude' => '',
    'is_default' => count($addresses) === 0 ? 1 : 0
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Saved Addresses | SUGNAYU</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        .address-wrap{max-width:1050px;margin:45px auto;padding:0 18px}
        .address-grid{display:grid;grid-template-columns:1fr 1fr;gap:24px}
        .address-card{border:1px solid #e5e5e5;border-radius:12px;padding:22px;margin-bottom:15px;background:#fff}
        .address-form label{display:block;margin:12px 0 6px}
        .address-form input,.address-form select{width:100%;padding:11px;border:1px solid #ccc;border-radius:7px}
        .address-form button,.address-btn{display:inline-block;margin-top:14px;padding:11px 16px;border:0;border-radius:7px;cursor:pointer;text-decoration:none}
        .address-form button,.primary{background:#315b43;color:#fff}
        .secondary{background:#eee;color:#222}
        .address-actions{display:flex;gap:8px;flex-wrap:wrap}
        .notice{padding:12px;background:#edf7ed;border-radius:7px;margin:12px 0}
        .error{padding:12px;background:#fff0f0;border-radius:7px;margin:12px 0}
        @media(max-width:700px){.address-grid{grid-template-columns:1fr}}
    </style>
</head>
<body>
<?php include __DIR__ . '/includes/header.php'; ?>

<main class="address-wrap">
    <h1>My Delivery Addresses</h1>
    <p>Manage your saved delivery addresses.</p>

    <?php if (isset($_GET['saved'])): ?>
        <p class="notice">Address saved successfully.</p>
    <?php endif; ?>
    <?php if (isset($_GET['deleted'])): ?>
        <p class="notice">Address deleted successfully.</p>
    <?php endif; ?>
    <?php if ($error): ?>
        <p class="error"><?= addressEscape($error) ?></p>
    <?php endif; ?>

    <div class="address-grid">
        <section>
            <h2><?= $editAddress ? 'Edit Address' : 'Add New Address' ?></h2>

            <form method="post" class="address-form">
                <input type="hidden" name="action" value="save">
                <input type="hidden" name="id" value="<?= (int)$form['id'] ?>">
                <input type="hidden" name="latitude" id="latitude"
                       value="<?= addressEscape($form['latitude']) ?>">
                <input type="hidden" name="longitude" id="longitude"
                       value="<?= addressEscape($form['longitude']) ?>">

                <button type="button" class="secondary" id="currentLocation">
                    Use Current Location
                </button>
                <p id="locationMessage" aria-live="polite"></p>

                <label>Full Name</label>
                <input name="full_name" required maxlength="150"
                       value="<?= addressEscape($form['full_name']) ?>">

                <label>Phone Number</label>
                <input name="phone" required maxlength="20"
                       value="<?= addressEscape($form['phone']) ?>">

                <label>House No., Street, Area</label>
                <input name="address_line" required maxlength="255"
                       value="<?= addressEscape($form['address_line']) ?>">

                <label>Landmark (optional)</label>
                <input name="landmark" maxlength="150"
                       value="<?= addressEscape($form['landmark']) ?>">

                <label>City</label>
                <input name="city" required
                       value="<?= addressEscape($form['city']) ?>">

                <label>State</label>
                <input name="state" required
                       value="<?= addressEscape($form['state']) ?>">

                <label>PIN Code</label>
                <input name="postal_code" required inputmode="numeric"
                       pattern="[0-9]{6}" maxlength="6"
                       value="<?= addressEscape($form['postal_code']) ?>">

                <label>Address Type</label>
                <select name="address_type">
                    <?php foreach (['Home','Work','Other'] as $type): ?>
                        <option value="<?= $type ?>"
                            <?= $form['address_type'] === $type ? 'selected' : '' ?>>
                            <?= $type ?>
                        </option>
                    <?php endforeach; ?>
                </select>

                <label>
                    <input type="checkbox" name="is_default" value="1"
                        <?= $form['is_default'] ? 'checked' : '' ?>>
                    Set as default address
                </label>

                <button type="submit">
                    <?= $editAddress ? 'Update Address' : 'Save Address' ?>
                </button>

                <?php if ($editAddress): ?>
                    <a class="address-btn secondary" href="address.php">Cancel</a>
                <?php endif; ?>
            </form>
        </section>

        <section>
            <h2>Saved Addresses</h2>

            <?php if (!$addresses): ?>
                <p>No saved addresses yet. Add your first delivery address.</p>
            <?php endif; ?>

            <?php foreach ($addresses as $a): ?>
                <article class="address-card">
                    <strong><?= addressEscape($a['address_type']) ?></strong>
                    <?php if ($a['is_default']): ?>
                        <span> · Default</span>
                    <?php endif; ?>

                    <p><strong><?= addressEscape($a['full_name']) ?></strong></p>
                    <p><?= addressEscape($a['phone']) ?></p>
                    <p>
                        <?= addressEscape($a['address_line']) ?><br>
                        <?php if ($a['landmark']): ?>
                            <?= addressEscape($a['landmark']) ?><br>
                        <?php endif; ?>
                        <?= addressEscape($a['city']) ?>,
                        <?= addressEscape($a['state']) ?> -
                        <?= addressEscape($a['postal_code']) ?>
                    </p>

                    <div class="address-actions">
                        <a class="address-btn secondary"
                           href="address.php?edit=<?= (int)$a['id'] ?>">Edit</a>

                        <?php if (!$a['is_default']): ?>
                            <form method="post">
                                <input type="hidden" name="action" value="default">
                                <input type="hidden" name="id" value="<?= (int)$a['id'] ?>">
                                <button class="address-btn secondary" type="submit">
                                    Set Default
                                </button>
                            </form>
                        <?php endif; ?>

                        <form method="post"
                              onsubmit="return confirm('Are you sure you want to delete this address?')">
                            <input type="hidden" name="action" value="delete">
                            <input type="hidden" name="id" value="<?= (int)$a['id'] ?>">
                            <button class="address-btn secondary" type="submit">
                                Delete
                            </button>
                        </form>
                    </div>
                </article>
            <?php endforeach; ?>
        </section>
    </div>
</main>

<script>
document.getElementById('currentLocation').addEventListener('click', function () {
    const message = document.getElementById('locationMessage');

    if (!navigator.geolocation) {
        message.textContent = 'Your browser does not support location services.';
        return;
    }

    message.textContent = 'Please allow location access to continue...';

    navigator.geolocation.getCurrentPosition(
        function (position) {
            document.getElementById('latitude').value =
                position.coords.latitude;
            document.getElementById('longitude').value =
                position.coords.longitude;

            message.textContent =
                'Location detected. Please enter or verify your address, city, and PIN code.';
        },
        function () {
            message.textContent =
                'Unable to detect your location. Please allow location access or enter your address manually.';
        },
        { enableHighAccuracy: true, timeout: 10000 }
    );
});
</script>

<?php include __DIR__ . '/includes/footer.php'; ?>
</body>
</html>
