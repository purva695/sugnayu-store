<?php
require_once __DIR__ . '/common.php'; admin_require_login(); admin_csrf_check();
$id=(int)($_POST['id']??0);
try { $stmt=$pdo->prepare('DELETE FROM products WHERE id=?');$stmt->execute([$id]);admin_flash($stmt->rowCount()?'Product deleted.':'Product not found.', $stmt->rowCount()?'success':'error'); }
catch(Throwable $e){admin_flash('This product could not be deleted, possibly because an existing order references it. Set its status to inactive instead.','error');}
header('Location: products.php');exit;
