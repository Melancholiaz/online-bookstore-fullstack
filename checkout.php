<?php
session_start();
require_once "db.php";

/* ถ้ายังไม่ได้ล็อกอิน → ส่งไปหน้า login.php (อย่าลืม .php) */
if (!isset($_SESSION['user_id'])) {
  header("Location: login.php?next=checkout.php");
  exit;
}

?>
<!DOCTYPE html>
<html lang="th">
<head>
  <meta charset="UTF-8">
  <title>สั่งซื้อ - BookStore</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <header>
    <nav class="navbar">
      <div class="logo">BookStore</div>
      <div>
        <span>สวัสดี, <b><?=htmlspecialchars($_SESSION['username'])?></b></span>
        &nbsp;|&nbsp; <a class="btn-auth" href="logout.php">ออกจากระบบ</a>
        &nbsp;|&nbsp; <a class="btn-auth" href="index.php">กลับหน้าหลัก</a>
      </div>
    </nav>
  </header>

  <main class="checkout-container">
    <h2>ข้อมูลการสั่งซื้อ</h2>
    <form id="checkoutForm" method="POST" action="place_order.php">
      <h3>ที่อยู่จัดส่ง</h3>
      <label>ชื่อ-นามสกุล</label>
      <input type="text" name="fullname" required>
      <label>ที่อยู่</label>
      <textarea name="address" required></textarea>
      <label>เบอร์โทรศัพท์</label>
      <input type="tel" name="phone" required>

      <h3>วิธีการชำระเงิน</h3>
      <select name="payment" required>
        <option value="">-- เลือกวิธีชำระเงิน --</option>
        <option value="cod">เก็บเงินปลายทาง</option>
        <option value="bank">โอนผ่านธนาคาร</option>
        <option value="card">บัตรเครดิต</option>
      </select>

      <h3>สรุปรายการสินค้า</h3>
      <div id="orderSummary" style="background:#fafafa;border:1px solid #eee;padding:12px;border-radius:8px"></div>
      <p style="text-align:right;margin-top:10px"><b>รวมทั้งหมด:</b> <span id="orderTotal">฿0</span></p>

      <input type="hidden" name="cart_json" id="cart_json">
      <input type="hidden" name="client_total" id="client_total">

      <button type="submit" class="btn-confirm">ยืนยันการสั่งซื้อ</button>
    </form>
  </main>

  <script>
    (function(){
      var cart = JSON.parse(localStorage.getItem("checkoutCart") || "[]");
      var summaryDiv = document.getElementById("orderSummary");
      var orderTotal  = document.getElementById("orderTotal");
      var cartJson    = document.getElementById("cart_json");
      var clientTotal = document.getElementById("client_total");

      var total = 0;
      summaryDiv.innerHTML = "";
      if (cart.length === 0) {
        summaryDiv.innerHTML = "<em>ตะกร้าว่างเปล่า</em>";
      } else {
        for (var i=0;i<cart.length;i++){
          var item = cart[i];
          var line = document.createElement("div");
          line.style.display = "flex";
          line.style.justifyContent = "space-between";
          line.style.margin = "6px 0";
          line.innerHTML = "<span>"+item.title+" × "+item.qty+"</span><b>฿"+(item.price*item.qty)+"</b>";
          summaryDiv.appendChild(line);
          total += item.price * item.qty;
        }
      }
      orderTotal.textContent = "฿" + total;
      cartJson.value = JSON.stringify(cart);
      clientTotal.value = String(total);
    })();
  </script>
</body>
</html>
