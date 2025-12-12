<?php session_start(); ?>
<!DOCTYPE html>
<html lang="th">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>BookStore</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <!-- Navbar -->
  <header>
    <nav class="navbar">
      <div class="logo">BookStore</div>
      <ul>
        <li><a href="index.php">หน้าแรก</a></li>
        <li><a href="#">หมวดหมู่</a></li>
        <li><a href="#">ใหม่</a></li>
        <li><a href="#">ลดราคา</a></li>
        <li><a href="#">เกี่ยวกับเรา</a></li>
      </ul>
      <div class="search-box">
        <input type="text" id="search" placeholder="ค้นหาหนังสือ...">
      </div>
      <div class="auth-buttons">
        <?php if (isset($_SESSION['user_id'])): ?>
          <span>ยินดีต้อนรับ, <b><?=htmlspecialchars($_SESSION['username'])?></b></span>
          <a href="logout.php" class="btn-auth">ออกจากระบบ</a>
        <?php else: ?>
          <!-- 🔧 เปลี่ยนเป็น .php แล้ว -->
          <a href="login.php" class="btn-auth">เข้าสู่ระบบ</a>
          <a href="register.php" class="btn-auth">สมัครสมาชิก</a>
        <?php endif; ?>
      </div>
      <button id="cartBtn" class="cart-btn">🛒 <span id="cartCount">0</span></button>
    </nav>
  </header>

  <!-- Hero -->
  <section class="hero">
    <h1>ค้นพบโลกแห่งหนังสือ</h1>
    <p>รวมรวมหนังสือคุณภาพหลากหลายหมวดหมู่ พร้อมส่งตรงถึงบ้านคุณ</p>
    <div class="hero-stats">
      <span>10,000+ หนังสือในสต็อก</span>
      <span>50,000+ ลูกค้าพึงพอใจ</span>
      <span>24/7 บริการออนไลน์</span>
    </div>
    <div class="filter-buttons">
      <button class="filter-btn" data-category="all">ทั้งหมด</button>
      <button class="filter-btn" data-category="novel">นิยาย</button>
      <button class="filter-btn" data-category="education">การศึกษา</button>
      <button class="filter-btn" data-category="science">วิทยาศาสตร์</button>
      <button class="filter-btn" data-category="kids">เด็ก</button>
      <button class="filter-btn" data-category="philosophy">ปรัชญา</button>
    </div>
  </section>

  <!-- Books -->
  <section class="books">
    <h2>หนังสือแนะนำ</h2>
    <div class="book-list" id="book-list"></div>
  </section>

  <!-- Slide Cart -->
  <div id="cartPanel" class="cart-panel">
    <h3>ตะกร้าสินค้า</h3>
    <div id="cartItems"></div>
    <div class="cart-total">รวมทั้งหมด: <span id="cartTotal">฿0</span></div>
    <button id="checkoutBtn" class="btn-confirm">ไปหน้าสั่งซื้อ</button>
    <button id="closeCart">ปิด</button>
  </div>

  <!-- Footer -->
  <footer>
    <div class="footer-container">
      <div>
        <h4>📚 BookStore</h4>
        <p>ร้านหนังสือออนไลน์ที่รวมหนังสือคุณภาพหลากหลาย พร้อมบริการจัดส่งทั่วประเทศ</p>
      </div>
      <div>
        <h4>ลิงก์ด่วน</h4>
        <ul>
          <li><a href="#">เกี่ยวกับเรา</a></li>
          <li><a href="#">วิธีการสั่งซื้อ</a></li>
          <li><a href="#">การจัดส่ง</a></li>
        </ul>
      </div>
      <div>
        <h4>หมวดหมู่</h4>
        <ul>
          <li><a href="#" class="filter-link" data-category="novel">นิยาย</a></li>
          <li><a href="#" class="filter-link" data-category="education">การศึกษา</a></li>
          <li><a href="#" class="filter-link" data-category="science">วิทยาศาสตร์</a></li>
        </ul>
      </div>
      <div>
        <h4>ติดต่อเรา</h4>
        <p>📞 02-123-4567<br>✉ info@bookstore.com</p>
      </div>
    </div>
    <p class="copyright">© 2024 BookStore. สงวนลิขสิทธิ์</p>
  </footer>

  <!-- ส่งสถานะล็อกอินไป JS -->
  <script>
    window.IS_LOGGED_IN = <?= isset($_SESSION['user_id']) ? 'true' : 'false' ?>;
  </script>
  <script src="script.js"></script>
</body>
</html>
