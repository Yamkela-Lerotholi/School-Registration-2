<?php
session_start();
require_once 'includes/config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $u = $_POST['username'] ?? '';
    $p = $_POST['password'] ?? '';

    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ? AND password = ? LIMIT 1");
    $stmt->execute([$u, $p]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['role'] = $user['role'];
        if ($user['role'] === 'admin') {
            header('Location: admin/');
        } else {
            header('Location: student/');
        }
        exit;
    } else {
        $error = "Invalid credentials or not accepted yet.";
    }
}
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Login</title>
  <link href="assets/css/style.css" rel="stylesheet">
  <style>
    body {
      min-height: 100vh;
      display: flex;
      flex-direction: column;
      justify-content: space-between;
      background: #f9f9f9;
      margin: 0;
      font-family: Arial, sans-serif;
    }
    .login-wrap {
      margin: auto;
      width: 320px;
      background: #fff;
      padding: 20px;
      border-radius: 8px;
      box-shadow: 0 2px 10px rgba(0,0,0,0.1);
      text-align: center;
    }
    .login-wrap input {
      width: 100%;
      padding: 10px;
      margin-bottom: 10px;
      border: 1px solid #ccc;
      border-radius: 5px;
    }
    .btn {
      padding: 10px 20px;
      background: #2b9348;
      color: #fff;
      border: none;
      border-radius: 5px;
      cursor: pointer;
    }
    .btn:hover {
      background: #247c3d;
    }
    .error {
      color: red;
      margin-bottom: 10px;
    }
    footer.footer {
      background: #2b9348;
      color: white;
      padding: 10px 0;
      text-align: center;
      font-size: 14px;
      width: 100%;
    }
    .footer-bottom {
      border-top: 1px solid rgba(255,255,255,0.2);
      margin-top: 5px;
      padding-top: 5px;
      font-size: 13px;
    }
  </style>
</head>
<body>

  <div class="login-wrap">
    <h2>Login</h2>
    <?php if (isset($error)) echo '<p class="error">'.htmlspecialchars($error).'</p>'; ?>
    <form method="post">
      <input name="username" placeholder="Username" required>
      <input name="password" type="password" placeholder="Password" required>
      <button class="btn btn-primary" type="submit">Login</button>
    </form>
    <p><a href="index.php">Back to site</a></p>
  </div>

  <footer class="footer">
    <div class="footer-bottom">
      <p style="margin:0;">&copy; <?php echo date('Y'); ?> Greenfield Academy. Designed by LeroTech</p>
    </div>
  </footer>

</body>
</html>
