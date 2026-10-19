<?php
require_once __DIR__ . '/common.php'; admin_require_login();
$q=trim($_GET['q']??'');
if($q!==''){$like='%'.$q.'%';$stmt=$pdo->prepare('SELECT * FROM orders WHERE order_number LIKE ? OR customer_name LIKE ? OR email LIKE ? ORDER BY id DESC');$stmt->execute([$like,$like,$like]);$orders=$stmt->fetchAll(PDO::FETCH_ASSOC);}else{$orders=$pdo->query('SELECT * FROM orders ORDER BY id DESC')->fetchAll(PDO::FETCH_ASSOC);}
admin_render_start('Orders');echo '<section class="panel"><div class="panel-head"><div><h2>Customer orders</h2><p>Review purchases, payment and fulfilment status.</p></div></div><form class="search-form"><input name="q" placeholder="Search order number, customer or email" value="'.admin_e($q).'"><button class="btn secondary">Search</button></form><div class="table-wrap"><table><thead><tr><th>Order</th><th>Customer</th><th>Total</th><th>Payment</th><th>Order status</th><th></th></tr></thead><tbody>';
if(!$orders)echo '<tr><td colspan="6" class="empty">No orders found.</td></tr>';
foreach($orders as $o)echo '<tr><td><strong>'.admin_e($o['order_number']).'</strong><small>'.admin_e($o['created_at']??'').'</small></td><td>'.admin_e($o['customer_name']).'<small>'.admin_e($o['email']).'</small></td><td>₹'.number_format((float)$o['total'],2).'</td><td><span class="pill">'.admin_e($o['payment_status']).'</span></td><td><span class="pill">'.admin_e($o['order_status']).'</span></td><td><a class="btn small secondary" href="order-view.php?id='.(int)$o['id'].'">View / update</a></td></tr>';
echo '</tbody></table></div></section>';admin_render_end();
