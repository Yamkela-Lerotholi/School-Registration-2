<?php
session_start();
require_once '../includes/config.php';


if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'student') { header('Location: ../login.php'); exit; }
$uid = $_SESSION['user_id'];
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ? LIMIT 1"); $stmt->execute([$uid]); $user = $stmt->fetch(PDO::FETCH_ASSOC);
$stmt = $pdo->prepare("SELECT a.* FROM applications a WHERE a.email = ? LIMIT 1"); $stmt->execute([$user['email']]); $app = $stmt->fetch(PDO::FETCH_ASSOC);
?>
<!doctype html><html><head><meta charset="utf-8"><title>Student Dashboard</title><link href="../assets/css/style.css" rel="stylesheet"></head><body>
<div class="container" style="padding:20px;">
<h1>Welcome <?php echo htmlspecialchars($user['full_name']); ?></h1>
<?php if($app): ?>
<h2>Personal Information</h2><ul>
<li><?php echo htmlspecialchars($app['full_name'].' '.$app['surname']); ?></li>
<li>ID: <?php echo htmlspecialchars($app['id_number']); ?></li>
<li>DOB: <?php echo htmlspecialchars($app['date_of_birth']); ?></li>
<li>Nationality: <?php echo htmlspecialchars($app['nationality']); ?></li>
<li>Phone: <?php echo htmlspecialchars($app['phone']); ?></li>
</ul>
<h3>Next of Kin</h3><ul>
<li><?php echo htmlspecialchars($app['parent_name'].' '.$app['parent_surname']); ?></li>
<li>ID: <?php echo htmlspecialchars($app['parent_id_number']); ?></li>
<li>Phone: <?php echo htmlspecialchars($app['parent_phone']); ?></li>
<li>Address: <?php echo htmlspecialchars($app['address']); ?></li>
</ul>
<h3>Documents</h3><ul>
<?php if($app['applicant_id_file']): ?><li><a href="../uploads/applications/<?php echo htmlspecialchars($app['applicant_id_file']); ?>">Applicant ID</a></li><?php endif; ?>
<?php if($app['parent_id_file']): ?><li><a href="../uploads/applications/<?php echo htmlspecialchars($app['parent_id_file']); ?>">Parent ID</a></li><?php endif; ?>
<?php if($app['school_report_file']): ?><li><a href="../uploads/applications/<?php echo htmlspecialchars($app['school_report_file']); ?>">School report</a></li><?php endif; ?>
</ul>
<?php else: echo '<p>No application found.</p>'; endif; ?>
<p><a href="../">Back to site</a></p>
</div>
<?php include '../includes/footer.php'; ?>
</body></html>
