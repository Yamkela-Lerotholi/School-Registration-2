<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once 'config.php';


function getLatestNews($limit = 5) {
    global $pdo;
    $limit = (int)$limit;
    $stmt = $pdo->query("SELECT * FROM news WHERE status = 'published' ORDER BY published_at DESC LIMIT $limit");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getGalleryImages($limit = 8) {
    global $pdo;
    $limit = (int)$limit;
    $stmt = $pdo->query("SELECT * FROM gallery ORDER BY uploaded_at DESC LIMIT $limit");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getGrades() {
    global $pdo;
    $stmt = $pdo->query("SELECT * FROM grades ORDER BY id");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getSubjectsByGrade($gradeId) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT s.* FROM subjects s INNER JOIN grade_subjects gs ON s.id = gs.subject_id WHERE gs.grade_id = ?");
    $stmt->execute([(int)$gradeId]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function submitApplication($data, $subjects = []) {
    global $pdo;
    $uploaded = handleFileUploads($_FILES);
    $stmt = $pdo->prepare("INSERT INTO applications (full_name, surname, id_number, date_of_birth, nationality, race, email, applicant_id, grade_id, address, phone, parent_name, parent_surname, parent_id_number, relationship, parent_phone, applicant_id_file, parent_id_file, school_report_file) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $ok = $stmt->execute([
        $data['full_name'] ?? null,
        $data['surname'] ?? null,
        $data['id_number'] ?? null,
        $data['dob'] ?? null,
        $data['nationality'] ?? null,
        $data['race'] ?? null,
        $data['email'] ?? null,
        $data['applicant_id'] ?? null,
        $data['grade'] ?? null,
        $data['address'] ?? null,
        $data['phone'] ?? null,
        $data['parent_name'] ?? null,
        $data['parent_surname'] ?? null,
        $data['parent_id_number'] ?? null,
        $data['relationship'] ?? null,
        $data['parent_phone'] ?? null,
        $uploaded['applicant_id_file'] ?? null,
        $uploaded['parent_id_file'] ?? null,
        $uploaded['school_report_file'] ?? null
    ]);
    if ($ok) {
        $appId = $pdo->lastInsertId();
        if (!empty($subjects) && is_array($subjects)) {
            $ins = $pdo->prepare("INSERT INTO application_subjects (application_id, subject_id) VALUES (?, ?)");
            foreach ($subjects as $s) {
                $ins->execute([$appId, (int)$s]);
            }
        }
    }
    return $ok;
}

function handleFileUploads($files) {
    $uploaded = [];
    if (!is_dir(UPLOAD_PATH)) mkdir(UPLOAD_PATH, 0755, true);
    foreach (['applicant_id_file', 'parent_id_file', 'school_report_file'] as $field) {
        if (isset($files[$field]) && isset($files[$field]['error']) && $files[$field]['error'] === UPLOAD_ERR_OK) {
            $ext = strtolower(pathinfo($files[$field]['name'], PATHINFO_EXTENSION));
            if (!in_array($ext, ALLOWED_FILE_TYPES)) continue;
            $filename = uniqid() . '_' . preg_replace('/[^A-Za-z0-9._-]/', '_', basename($files[$field]['name']));
            $targetPath = UPLOAD_PATH . $filename;
            if (move_uploaded_file($files[$field]['tmp_name'], $targetPath)) {
                $uploaded[$field] = $filename;
            }
        }
    }
    return $uploaded;
}

function acceptApplication($applicationId) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM applications WHERE id = ? LIMIT 1");
    $stmt->execute([(int)$applicationId]);
    $app = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$app) return false;
    $username = $app['email'];
    $password = $app['applicant_id'] ?: 'student123';
    $full_name = $app['full_name'] . ' ' . $app['surname'];
    $grade_id = $app['grade_id'];
    $ins = $pdo->prepare("INSERT IGNORE INTO users (username, password, role, full_name, email, grade_id, status) VALUES (?, ?, 'student', ?, ?, ?, 'active')");
    $ins->execute([$username, $password, $full_name, $app['email'], $grade_id]);
    $upd = $pdo->prepare("UPDATE applications SET status = 'accepted' WHERE id = ?");
    $upd->execute([(int)$applicationId]);
    return true;
}

function rejectApplication($applicationId) {
    global $pdo;
    $upd = $pdo->prepare("UPDATE applications SET status = 'rejected' WHERE id = ?");
    return $upd->execute([(int)$applicationId]);
}

function getAllApplications() {
    global $pdo;
    $stmt = $pdo->query("SELECT a.*, g.grade_name FROM applications a LEFT JOIN grades g ON a.grade_id = g.id ORDER BY applied_at DESC");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function saveMessage($name, $email, $message) {
    global $pdo;
    $stmt = $pdo->prepare("INSERT INTO messages (name, email, message) VALUES (?, ?, ?)");
    return $stmt->execute([$name, $email, $message]);
}
?>
