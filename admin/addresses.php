<?php
require_once __DIR__ . '/common.php';
admin_require_login();

$q = trim($_GET['q'] ?? '');
$addresses = [];
$tableError = false;

try {
    if ($q !== '') {
        $like = '%' . $q . '%';

        $stmt = $pdo->prepare(
            "SELECT id, customer_email, full_name, phone, address_line,
                    landmark, city, state, postal_code, address_type,
                    is_default, created_at
             FROM customer_addresses
             WHERE customer_email LIKE ?
                OR full_name LIKE ?
                OR phone LIKE ?
                OR address_line LIKE ?
                OR city LIKE ?
                OR state LIKE ?
                OR postal_code LIKE ?
             ORDER BY is_default DESC, id DESC"
        );

        $stmt->execute(array_fill(0, 7, $like));
        $addresses = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } else {
        $stmt = $pdo->query(
            "SELECT id, customer_email, full_name, phone, address_line,
                    landmark, city, state, postal_code, address_type,
                    is_default, created_at
             FROM customer_addresses
             ORDER BY is_default DESC, id DESC"
        );

        $addresses = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
} catch (Throwable $e) {
    $tableError = true;
}

admin_render_start('Customer Addresses');

echo '<section class="panel">';
echo '<div class="panel-head">
        <div>
          <h2>Saved Delivery Addresses</h2>
          <p>View addresses saved by your customers.</p>
        </div>
        <span class="pill">' . count($addresses) . ' shown</span>
      </div>';

echo '<form class="search-form" method="get">
        <input name="q"
               placeholder="Search name, email, phone, city or PIN code"
               value="' . admin_e($q) . '">
        <button class="btn secondary" type="submit">Search</button>';

if ($q !== '') {
    echo '<a class="btn secondary" href="addresses.php">Clear</a>';
}

echo '</form>';

if ($tableError) {
    echo '<div class="alert error">
            Could not load addresses. Please confirm that the
            customer_addresses table exists in your database.
          </div>';
} else {
    echo '<div class="table-wrap">
            <table>
              <thead>
                <tr>
                  <th>Customer</th>
                  <th>Contact</th>
                  <th>Delivery Address</th>
                  <th>Type</th>
                  <th>Default</th>
                  <th>Saved On</th>
                </tr>
              </thead>
              <tbody>';

    if (empty($addresses)) {
        $message = $q !== ''
            ? 'No matching addresses found.'
            : 'No saved addresses yet.';

        echo '<tr><td colspan="6" class="empty">'
            . admin_e($message)
            . '</td></tr>';
    }

    foreach ($addresses as $a) {
        $fullAddress = $a['address_line'];

        if (!empty($a['landmark'])) {
            $fullAddress .= ', ' . $a['landmark'];
        }

        $fullAddress .= ', ' . $a['city']
            . ', ' . $a['state']
            . ' - ' . $a['postal_code'];

        echo '<tr>';

        echo '<td><strong>'
            . admin_e($a['full_name'])
            . '</strong><small>'
            . admin_e($a['customer_email'])
            . '</small></td>';

        echo '<td>' . admin_e($a['phone']) . '</td>';

        echo '<td class="message-cell">'
            . admin_e($fullAddress)
            . '</td>';

        echo '<td>'
            . admin_e($a['address_type'] ?? 'Home')
            . '</td>';

        echo '<td>';

        if (!empty($a['is_default'])) {
            echo '<span class="pill green">Default</span>';
        } else {
            echo '<span class="pill">No</span>';
        }

        echo '</td>';

        echo '<td>'
            . admin_e($a['created_at'] ?? '—')
            . '</td>';

        echo '</tr>';
    }

    echo '</tbody></table></div>';
}

echo '<p class="muted small-text">
        Customer addresses are private information. Use them only
        for order fulfilment and customer support.
      </p>';

echo '</section>';

admin_render_end();