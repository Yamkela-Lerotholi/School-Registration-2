<?php
session_start();
require_once '../includes/config.php';
require_once '../includes/functions.php';
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') { header('Location: ../login.php'); exit; }
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$stmt = $pdo->prepare("SELECT a.*, g.grade_name FROM applications a LEFT JOIN grades g ON a.grade_id = g.id WHERE a.id = ? LIMIT 1");
$stmt->execute([$id]); $a = $stmt->fetch(PDO::FETCH_ASSOC);
$subs = $pdo->prepare("SELECT s.subject_name FROM application_subjects aps JOIN subjects s ON aps.subject_id = s.id WHERE aps.application_id = ?");
$subs->execute([$id]); $subjects = $subs->fetchAll(PDO::FETCH_ASSOC);
?>
<!doctype html><html><head><meta charset="utf-8"><title>Application #<?php echo $id; ?></title><link href="../assets/css/style.css" rel="stylesheet"></head><body>
<div class="container" style="padding:20px;">
<h1>Application Details</h1>
<?php if(!$a){ echo "<p>Not found.</p>"; exit; } ?>
<h3><?php echo htmlspecialchars($a['full_name'].' '.$a['surname']); ?> (<?php echo htmlspecialchars($a['email']); ?>)</h3>
<p><strong>Grade:</strong> <?php echo htmlspecialchars($a['grade_name']); ?></p>
<p><strong>DOB:</strong> <?php echo htmlspecialchars($a['date_of_birth']); ?> | <strong>ID:</strong> <?php echo htmlspecialchars($a['id_number']); ?></p>
<p><strong>Nationality:</strong> <?php echo htmlspecialchars($a['nationality']); ?> | <strong>Race:</strong> <?php echo htmlspecialchars($a['race']); ?></p>
<p><strong>Phone:</strong> <?php echo htmlspecialchars($a['phone']); ?></p>
<h4>Next of Kin</h4>
<p><?php echo htmlspecialchars($a['parent_name'].' '.$a['parent_surname']); ?> | ID: <?php echo htmlspecialchars($a['parent_id_number']); ?></p>
<p><strong>Relationship:</strong> <?php echo htmlspecialchars($a['relationship']); ?> | <strong>Phone:</strong> <?php echo htmlspecialchars($a['parent_phone']); ?></p>
<h4>Subjects</h4>
<ul><?php foreach($subjects as $s) echo '<li>'.htmlspecialchars($s['subject_name']).'</li>'; ?></ul>
<h4>Documents</h4>
<ul>
<?php if($a['applicant_id_file']): ?><li><a href="../uploads/applications/<?php echo htmlspecialchars($a['applicant_id_file']); ?>" download>Applicant ID</a></li><?php endif; ?>
<?php if($a['parent_id_file']): ?><li><a href="../uploads/applications/<?php echo htmlspecialchars($a['parent_id_file']); ?>" download>Parent ID</a></li><?php endif; ?>
<?php if($a['school_report_file']): ?><li><a href="../uploads/applications/<?php echo htmlspecialchars($a['school_report_file']); ?>" download>School Report</a></li><?php endif; ?>
</ul>
<p><a href="applications.php">Back</a></p>
</div>
<?php include '../includes/footer.php'; ?>
</body></html>
