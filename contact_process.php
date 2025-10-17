<?php
require_once 'config.php';
require_once 'functions.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'] ?? '';
    $email = $_POST['email'] ?? '';
    $message = $_POST['message'] ?? '';
    if ($name && $email && $message) {
        saveMessage($name, $email, $message);
        header('Location: ../?success=message_sent');
        exit;
    }
}
header('Location: ../?error=invalid');
