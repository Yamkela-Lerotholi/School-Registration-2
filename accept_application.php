<?php
require_once '../includes/config.php';
require_once '../includes/functions.php';

if (!isset($_GET['id'])) {
    header("Location: applications.php");
    exit;
}

$id = intval($_GET['id']);

try {
    $stmt = $pdo->prepare("UPDATE applications SET status = 'accepted' WHERE id = ?");
    $stmt->execute([$id]);
} catch (PDOException $e) {
    die("Error updating status: " . $e->getMessage());
}

// Redirect to accepted applications page
header("Location: accepted_applications.php");
exit;
