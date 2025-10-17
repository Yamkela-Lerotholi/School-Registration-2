<?php
session_start();
require_once '../includes/config.php';
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') { header('Location: ../login.php'); exit; }
$stmt = $pdo->query("SELECT * FROM messages ORDER BY sent_at DESC"); $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!doctype html><html><head><meta charset="utf-8"><title>Contacts</title></head><body>
<div class="container" style="padding:20px;">
<h1>Contact Messages</h1>
<?php foreach($rows as $r): ?>
<div class="card"><strong><?php echo htmlspecialchars($r['name']); ?> (<?php echo htmlspecialchars($r['email']); ?>)</strong><p><?php echo nl2br(htmlspecialchars($r['message'])); ?></p><small><?php echo $r['sent_at']; ?></small></div>
<?php endforeach; ?>
<p><a href="index.php">Back</a></p>
</div>
<?php include '../includes/footer.php'; ?>
</body></html>
