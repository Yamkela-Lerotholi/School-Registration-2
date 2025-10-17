<?php
require_once '../includes/config.php';

if (!isset($_GET['id'])) {
    header("Location: applications.php");
    exit;
}

$id = intval($_GET['id']);

try {
    $stmt = $pdo->prepare("UPDATE applications SET status = 'rejected' WHERE id = ?");
    $stmt->execute([$id]);
} catch (PDOException $e) {
    die("Error updating status: " . $e->getMessage());
}

header("Location: rejected_applications.php");
exit;
