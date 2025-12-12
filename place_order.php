<?php
ini_set('display_errors',1); ini_set('display_startup_errors',1); error_reporting(E_ALL);
session_start();
require_once "db.php";

if (!isset($_SESSION['user_id'])) {
  header("Location: login.php?next=checkout.php");
  exit;
}

$user_id = (int)$_SESSION['user_id'];

$fullname    = trim(isset($_POST['fullname']) ? $_POST['fullname'] : '');
$address     = trim(isset($_POST['address']) ? $_POST['address'] : '');
$phone       = trim(isset($_POST['phone']) ? $_POST['phone'] : '');
$payment     = trim(isset($_POST['payment']) ? $_POST['payment'] : '');
$cartJson    = isset($_POST['cart_json']) ? $_POST['cart_json'] : '';
$clientTotal = floatval(isset($_POST['client_total']) ? $_POST['client_total'] : 0);

if ($fullname==='' || $address==='' || $phone==='' || $payment==='' || $cartJson==='') {
  exit("ข้อมูลไม่ครบ");
}

$cart = json_decode($cartJson, true);
if (!is_array($cart) || count($cart)===0) exit("ตะกร้าไม่ถูกต้อง");

try {
  $pdo->beginTransaction();

  // คำนวณใหม่จาก books table
  $reTotal = 0.0;
  $items   = array();
  $stmtB = $pdo->prepare("SELECT id, title, price, stock FROM books WHERE title = ? LIMIT 1");

  for ($i=0;$i<count($cart);$i++){
    $c = $cart[$i];
    $title = isset($c['title']) ? $c['title'] : '';
    $qty   = isset($c['qty']) ? (int)$c['qty'] : 0;
    if ($title==='' || $qty<=0) continue;

    $stmtB->execute(array($title));
    $book = $stmtB->fetch();
    if (!$book) { throw new Exception("ไม่พบสินค้า: ".$title); }
    if ($book['stock'] !== null && $book['stock'] < $qty) {
      throw new Exception("สต็อกไม่พอ: ".$title);
    }
    $linePrice = (float)$book['price'];
    $reTotal += $linePrice * $qty;
    $items[] = array('book_id'=>(int)$book['id'], 'qty'=>$qty, 'price'=>$linePrice);
  }

  if ($reTotal <= 0) throw new Exception("ยอดรวมไม่ถูกต้อง");

  $stmtOrder = $pdo->prepare("
    INSERT INTO orders (user_id, total, status, fullname, address, phone, payment_method)
    VALUES (:user_id, :total, 'paid', :fullname, :address, :phone, :payment)
  ");
  $stmtOrder->execute(array(
    ':user_id'  => $user_id,
    ':total'    => $reTotal,
    ':fullname' => $fullname,
    ':address'  => $address,
    ':phone'    => $phone,
    ':payment'  => $payment
  ));
  $orderId = $pdo->lastInsertId();

  $stmtItem = $pdo->prepare("INSERT INTO order_items (order_id, book_id, quantity, price) VALUES (:oid,:bid,:q,:p)");
  $stmtStock= $pdo->prepare("UPDATE books SET stock = stock - :q WHERE id = :id");

  for ($i=0;$i<count($items);$i++){
    $it = $items[$i];
    $stmtItem->execute(array(':oid'=>$orderId, ':bid'=>$it['book_id'], ':q'=>$it['qty'], ':p'=>$it['price']));
    try {
      $stmtStock->execute(array(':q'=>$it['qty'], ':id'=>$it['book_id']));
    } catch (Exception $e) {}
  }

  $pdo->commit();
  header("Location: success.php?order_id=".$orderId);
  exit;

} catch (Exception $e) {
  if ($pdo->inTransaction()) $pdo->rollBack();
  http_response_code(400);
  echo "สั่งซื้อไม่สำเร็จ: ".htmlspecialchars($e->getMessage());
  exit;
}
