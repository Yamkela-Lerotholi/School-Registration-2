<?php
session_start();
require_once '../includes/config.php';
require_once '../includes/functions.php';


if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../login.php');
    exit;
}

$stmt = $pdo->query("SELECT * FROM applications WHERE status = 'pending' ORDER BY id DESC");
$apps = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Pending Applications</title>
  <link href="../assets/css/style.css" rel="stylesheet">
</head>
<body>
<div class="container" style="padding:20px;">
  <h1>Pending Applications</h1>

  <table class="apps-table">
    <thead>
      <tr>
        <th>ID</th>
        <th>Full Name</th>
        <th>Email</th>
        <th>Grade</th>
        <th>Date Applied</th>
        <th>Status</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($apps as $a): ?>
      <tr class="app-row <?= htmlspecialchars($a['status']) ?>">
        <td><?= $a['id']; ?></td>
        <td><?= htmlspecialchars($a['full_name'] . ' ' . $a['surname']); ?></td>
        <td><?= htmlspecialchars($a['email']); ?><br><?= htmlspecialchars($a['phone']); ?></td>
        <td><?= htmlspecialchars($a['grade_name']); ?></td>
        <td><?= htmlspecialchars($a['applied_at']); ?></td>
        <td><?= htmlspecialchars($a['status']); ?></td>
        <td>
          <a class="btn btn-sm" href="view_application.php?id=<?= $a['id']; ?>">View</a>
          <a class="btn btn-success btn-sm" href="accept_application.php?id=<?= $a['id']; ?>">Accept</a>
          <a class="btn btn-danger btn-sm" href="reject_application.php?id=<?= $a['id']; ?>">Reject</a>
        </td>
      </tr>
      <?php endforeach; ?>
    </tbody>
  </table>

  <p><a href="index.php">Back</a></p>
</div>
<?php include '../includes/footer.php'; ?>
</body>
</html>
