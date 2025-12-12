<?php
ini_set('display_errors',1); ini_set('display_startup_errors',1); error_reporting(E_ALL);
session_start();
require_once "db.php";

function safe_next($next){
  return preg_match('/^[a-zA-Z0-9_\-\/\.]+$/',$next) ? $next : 'index.php';
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $email    = trim($_POST['email']);
  $password = $_POST['password'];

  $st = $pdo->prepare("SELECT id, username, email, role, password_hash FROM users WHERE email=? LIMIT 1");
  $st->execute(array($email));
  $u = $st->fetch();

  if ($u && password_verify($password, $u['password_hash'])) {
    $_SESSION['user_id']  = (int)$u['id'];
    $_SESSION['username'] = $u['username'];
    $_SESSION['role']     = $u['role'] ? $u['role'] : 'user';
    header("Location: " . safe_next(isset($_GET['next']) ? $_GET['next'] : 'index.php'));
    exit;
  } else {
    $error = "อีเมลหรือรหัสผ่านไม่ถูกต้อง";
  }
}
?>
<!DOCTYPE html>
<html lang="th">
<head>
  <meta charset="UTF-8">
  <title>เข้าสู่ระบบ</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <div class="auth-container">
    <h2>เข้าสู่ระบบ</h2>
    <?php if (isset($_GET['registered'])): ?>
      <p style="color:#28a745">สมัครสมาชิกสำเร็จ! กรุณาเข้าสู่ระบบ</p>
    <?php endif; ?>
    <?php if ($error): ?><p style="color:#d9534f"><?=htmlspecialchars($error)?></p><?php endif; ?>
    <form method="post">
      <label>อีเมล</label>
      <input type="email" name="email" required>
      <label>รหัสผ่าน</label>
      <input type="password" name="password" required>
      <button type="submit">เข้าสู่ระบบ</button>
    </form>
    <p>ยังไม่มีบัญชี? <a href="register.php">สมัครสมาชิก</a></p>
  </div>
</body>
</html>
