<?php
ini_set('display_errors',1); ini_set('display_startup_errors',1); error_reporting(E_ALL);
session_start();
require_once "db.php";

$errors = array();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $username = trim(isset($_POST['username']) ? $_POST['username'] : '');
  $email    = trim(isset($_POST['email']) ? $_POST['email'] : '');
  $password = isset($_POST['password']) ? $_POST['password'] : '';
  $confirm  = isset($_POST['confirmPassword']) ? $_POST['confirmPassword'] : '';

  if ($username=='' || $email=='' || $password=='' || $confirm=='') $errors[] = "กรุณากรอกข้อมูลให้ครบถ้วน";
  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = "อีเมลไม่ถูกต้อง";
  if ($password !== $confirm) $errors[] = "รหัสผ่านและยืนยันรหัสผ่านไม่ตรงกัน";
  if (strlen($password) < 6) $errors[] = "รหัสผ่านควรมีอย่างน้อย 6 ตัวอักษร";

  if (!$errors) {
    $st = $pdo->prepare("SELECT id FROM users WHERE email=? LIMIT 1");
    $st->execute(array($email));
    if ($st->fetch()) {
      $errors[] = "อีเมลนี้ถูกใช้งานแล้ว";
    } else {
      $hash = password_hash($password, PASSWORD_BCRYPT);
      $ins = $pdo->prepare("INSERT INTO users (username, email, password_hash, role) VALUES (?,?,?,'user')");
      $ins->execute(array($username, $email, $hash));
      header("Location: login.php?registered=1");
      exit;
    }
  }
}
?>
<!DOCTYPE html>
<html lang="th">
<head>
  <meta charset="UTF-8">
  <title>สมัครสมาชิก</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <div class="auth-container">
    <h2>สมัครสมาชิก</h2>
    <?php if ($errors): ?>
      <div style="color:#d9534f;text-align:left">
        <?php foreach($errors as $e) echo "• ".htmlspecialchars($e)."<br>"; ?>
      </div>
    <?php endif; ?>
    <form method="post">
      <label>ชื่อผู้ใช้</label>
      <input type="text" name="username" required value="<?=htmlspecialchars(isset($_POST['username'])?$_POST['username']:'')?>">
      <label>อีเมล</label>
      <input type="email" name="email" required value="<?=htmlspecialchars(isset($_POST['email'])?$_POST['email']:'')?>">
      <label>รหัสผ่าน</label>
      <input type="password" name="password" required>
      <label>ยืนยันรหัสผ่าน</label>
      <input type="password" name="confirmPassword" required>
      <button type="submit">สมัครสมาชิก</button>
    </form>
    <p>มีบัญชีแล้ว? <a href="login.php">เข้าสู่ระบบ</a></p>
  </div>
</body>
</html>
