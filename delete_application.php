<?php
require_once '../includes/config.php';

if (!isset($_GET['id'])) {
    header("Location: rejected_applications.php");
    exit;
}

$id = intval($_GET['id']);
$stmt = $pdo->prepare("DELETE FROM applications WHERE id = ?");
$stmt->execute([$id]);

header("Location: rejected_applications.php");
exit;
