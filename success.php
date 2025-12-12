<?php session_start(); ?>
<!DOCTYPE html>
<html lang="th">
<head>
  <meta charset="UTF-8">
  <title>สั่งซื้อสำเร็จ</title>
  <link rel="stylesheet" href="style.css">
  <script>
    // เคลียร์ตะกร้าฝั่ง client
    localStorage.removeItem("checkoutCart");
  </script>
</head>
<body>
  <div class="auth-container">
    <h2>🎉 สั่งซื้อสำเร็จแล้ว</h2>
    <p>ขอบคุณที่ใช้บริการ BookStore</p>
    <a href="index.php" class="btn-auth">กลับหน้าหลัก</a>
  </div>
</body>
</html>
