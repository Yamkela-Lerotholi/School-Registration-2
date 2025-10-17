<?php
require_once 'config.php';
require_once 'functions.php';
$grade = isset($_GET['grade_id']) ? (int)$_GET['grade_id'] : 0;
$subjects = [];
if ($grade) $subjects = getSubjectsByGrade($grade);
header('Content-Type: application/json');
echo json_encode($subjects);
