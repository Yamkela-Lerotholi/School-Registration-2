<?php
session_start();
require_once '../includes/config.php';
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') { header('Location: ../login.php'); exit; }
?>
<!doctype html><html><head><meta charset="utf-8"><title>Admin Portal</title><link href="../assets/css/style.css" rel="stylesheet"></head><body>
<div class="container" style="padding:20px;">
<h1>Admin Portal</h1>
<ul class="admin-menu">
<li><a href="applications.php">Manage Applications</a></li>
<li><a href="news.php">Manage News</a></li>
<li><a href="gallery.php">Manage Gallery</a></li>
<li><a href="contacts.php">Contacts</a></li>
<p><a href="../">Back to site</a></p>
</ul>
</div>
<?php include '../includes/footer.php'; ?>
</body></html>
