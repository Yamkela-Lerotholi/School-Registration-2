<?php
require_once 'config.php';
require_once 'functions.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = [
        'full_name' => $_POST['full_name'] ?? null,
        'surname' => $_POST['surname'] ?? null,
        'id_number' => $_POST['id_number'] ?? null,
        'dob' => $_POST['dob'] ?? null,
        'nationality' => $_POST['nationality'] ?? null,
        'race' => $_POST['race'] ?? null,
        'email' => $_POST['email'] ?? null,
        'applicant_id' => $_POST['applicant_id'] ?? ($_POST['id_number'] ?? null),
        'grade' => $_POST['grade'] ?? null,
        'address' => $_POST['parent_address'] ?? null,
        'phone' => $_POST['phone'] ?? null,
        'parent_name' => $_POST['parent_name'] ?? null,
        'parent_surname' => $_POST['parent_surname'] ?? null,
        'parent_id_number' => $_POST['parent_id_number'] ?? null,
        'relationship' => $_POST['relationship'] ?? null,
        'parent_phone' => $_POST['parent_phone'] ?? null
    ];
    $subjects = $_POST['subjects'] ?? [];
    $ok = submitApplication($data, $subjects);
    if ($ok) header('Location: ../?success=applied');
    else header('Location: ../?error=failed');
} else {
    header('Location: ../');
}
