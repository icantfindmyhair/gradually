<?php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../includes/helpers.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !csrf_verify($_POST['csrf_token'] ?? '')) {
    http_response_code(400);
    exit('Invalid request.');
}

$id              = (int)($_POST['id'] ?? 0);
$exercise_type   = trim($_POST['exercise_type'] ?? '');
$duration_min    = (int)($_POST['duration_min'] ?? 0);
$calories_burned = (float)($_POST['calories_burned'] ?? 0);
$date            = input_date($_POST['date'] ?? '');
$notes           = trim($_POST['notes'] ?? '');

if ($id <= 0 || $exercise_type === '' || !$date || $duration_min <= 0 || $calories_burned < 0) {
    exit('Validation failed. Please go back and check your inputs.');
}

$sql = "UPDATE exercises 
        SET exercise_type = ?, duration_min = ?, calories_burned = ?, date = ?, notes = ?
        WHERE exercise_id = ? AND user_id = ?";
$stmt = $conn->prepare($sql);

if (!$stmt) {
    error_log("Prepare failed: " . $conn->error);
    exit('Something went wrong. Please try again later.');
}

$stmt->bind_param('sidssii', $exercise_type, $duration_min, $calories_burned, $date, $notes, $id, $_SESSION['user_id']);
$stmt->execute();

if ($stmt->affected_rows === 0) {
    // Covers two cases: no permission (wrong user_id) OR no change in values
    // For UX, we just redirect quietly
    redirect('/exercise/index.php');
}

redirect('/exercise/index.php');
