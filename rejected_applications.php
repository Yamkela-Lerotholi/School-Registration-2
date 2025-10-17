<?php
session_start();
require_once '../includes/config.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../login.php');
    exit;
}

$stmt = $pdo->query("SELECT * FROM applications WHERE status = 'rejected' ORDER BY id DESC");
$apps = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Rejected Applications</title>
  <link href="../assets/css/style.css" rel="stylesheet">
</head>
<body>
<div class="container" style="padding:20px;">
  <h1>Rejected Applications</h1>

  <table class="apps-table">
    <thead>
      <tr><th>ID</th><th>Name</th><th>Email</th><th>Grade</th><th>Actions</th></tr>
    </thead>
    <tbody>
      <?php foreach ($apps as $a): ?>
      <tr>
        <td><?= $a['id']; ?></td>
        <td><?= htmlspecialchars($a['full_name'] . ' ' . $a['surname']); ?></td>
        <td><?= htmlspecialchars($a['email']); ?></td>
        <td><?= htmlspecialchars($a['grade_name']); ?></td>
        <td>
          <a class="btn btn-success btn-sm" href="accept_application.php?id=<?= $a['id']; ?>">Re-Accept</a>
          <a class="btn btn-danger btn-sm" href="delete_application.php?id=<?= $a['id']; ?>" onclick="return confirm('Delete this permanently?');">Delete</a>
        </td>
      </tr>
      <?php endforeach; ?>
    </tbody>
  </table>

  <a href="applications.php">← Back to Pending Applications</a>
</div>
<?php include '../includes/footer.php'; ?>
</body>
</html>
