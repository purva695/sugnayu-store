<?php
require_once __DIR__ . '/common.php'; admin_require_login();
$stats = ['products'=>0,'orders'=>0,'customers'=>0,'messages'=>0,'revenue'=>0];
try { $stats['products']=(int)$pdo->query('SELECT COUNT(*) FROM products')->fetchColumn(); } catch(Throwable $e){}
try { $stats['orders']=(int)$pdo->query('SELECT COUNT(*) FROM orders')->fetchColumn(); } catch(Throwable $e){}
try { $stats['customers']=(int)$pdo->query('SELECT COUNT(*) FROM customers')->fetchColumn(); } catch(Throwable $e){}
try { $stats['messages']=(int)$pdo->query('SELECT COUNT(*) FROM contacts')->fetchColumn(); } catch(Throwable $e){}
try { $stats['revenue']=(float)$pdo->query("SELECT COALESCE(SUM(total),0) FROM orders WHERE payment_status='paid'")->fetchColumn(); } catch(Throwable $e){}
$recent=[]; try {$recent=$pdo->query('SELECT id, order_number, customer_name, total, payment_status, order_status, created_at FROM orders ORDER BY id DESC LIMIT 6')->fetchAll(PDO::FETCH_ASSOC);}catch(Throwable $e){}
admin_render_start('Dashboard');
echo '<div class="welcome"><div><h2>Hello, '.admin_e($_SESSION['sugnayu_admin_name'] ?? 'Admin').' 👋</h2><p>Here is what is happening with your store.</p></div><a class="btn primary" href="product-form.php">+ Add product</a></div><section class="stats-grid">';
foreach ([['Products',$stats['products'],'products.php','▦'],['Orders',$stats['orders'],'orders.php','↗'],['Customers',$stats['customers'],'customers.php','♙'],['Paid revenue','₹'.number_format($stats['revenue'],2),'orders.php','₹']] as $s) echo '<a class="stat-card" href="'.admin_e($s[2]).'"><span class="stat-icon">'.admin_e($s[3]).'</span><span class="stat-label">'.admin_e($s[0]).'</span><strong>'.admin_e($s[1]).'</strong><span class="stat-link">View details →</span></a>';
echo '</section><section class="panel"><div class="panel-head"><div><h2>Recent orders</h2><p>Latest orders placed on your website</p></div><a class="text-link" href="orders.php">View all orders →</a></div><div class="table-wrap"><table><thead><tr><th>Order</th><th>Customer</th><th>Total</th><th>Payment</th><th>Order status</th><th></th></tr></thead><tbody>';
if (!$recent) echo '<tr><td colspan="6" class="empty">No orders found yet.</td></tr>';
foreach($recent as $r) echo '<tr><td><strong>'.admin_e($r['order_number']).'</strong><small>'.admin_e($r['created_at'] ?? '').'</small></td><td>'.admin_e($r['customer_name']).'</td><td>₹'.number_format((float)$r['total'],2).'</td><td><span class="pill">'.admin_e($r['payment_status']).'</span></td><td><span class="pill">'.admin_e($r['order_status']).'</span></td><td><a class="text-link" href="order-view.php?id='.(int)$r['id'].'">Open</a></td></tr>';
echo '</tbody></table></div></section><div class="note"><strong>Store tip</strong><p>Use Products to update your catalogue and Orders to track and update fulfilment. Never share your admin password.</p></div>';
admin_render_end();
