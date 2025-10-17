<?php
session_start();
require_once '../includes/config.php';
require_once '../includes/functions.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../login.php');
    exit;
}

// Fetch all accepted applications
$stmt = $pdo->query("SELECT * FROM applications WHERE status = 'accepted' ORDER BY grade_id ASC, id DESC");
$applications = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Grade mapping
$gradeMap = [
    1 => 'Grade 8',
    2 => 'Grade 9',
    3 => 'Grade 10',
    4 => 'Grade 11',
    5 => 'Grade 12'
];
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>Accepted Applications - Greenfield Academy</title>
<link href="../assets/css/style.css" rel="stylesheet">
<style>
body { font-family: Arial, sans-serif; background: #f4f6f8; }
.container { padding: 20px; max-width: 1000px; margin: auto; background: #fff; border-radius: 8px; }
h2 { color: #2e7d32; margin-top: 30px; }
table { width: 100%; border-collapse: collapse; margin-top: 10px; }
th, td { padding: 10px; border-bottom: 1px solid #ccc; }
th { background: #2e7d32; color: #fff; }
tr:nth-child(even) { background: #f9f9f9; }
.back-link { display: inline-block; margin-top: 20px; color: #2e7d32; text-decoration: none; }
</style>
</head>
<body>
<div class="container">
  <h1>Accepted Applications</h1>
  <?php foreach ($gradeMap as $grade_id => $grade_name): ?>
    <?php 
      $filtered = array_filter($applications, fn($a) => $a['grade_id'] == $grade_id);
      if (empty($filtered)) continue;
    ?>
    <h2><?= htmlspecialchars($grade_name) ?></h2>
    <table>
      <thead>
        <tr>
          <th>Name</th>
          <th>Email</th>
          <th>Phone</th>
          <th>Status</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($filtered as $app): ?>
          <tr>
            <td><?= htmlspecialchars($app['full_name'] . ' ' . $app['surname']); ?></td>
            <td><?= htmlspecialchars($app['email']); ?></td>
            <td><?= htmlspecialchars($app['phone']); ?></td>
            <td><?= htmlspecialchars($app['status']); ?></td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  <?php endforeach; ?>

  <a href="applications.php" class="back-link">← Back to Pending Applications</a>
</div>
<?php include '../includes/footer.php'; ?>
</body>
</html>
