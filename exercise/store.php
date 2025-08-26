<?php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../database.php';
require_once __DIR__ . '/../includes/helpers.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !csrf_verify($_POST['csrf_token'] ?? '')) {
    http_response_code(400);
    exit('Invalid request.');
}

$user_id         = (int)($_SESSION['user_id'] ?? 0);
$exercise_type   = trim($_POST['exercise_type'] ?? '');
$duration_min    = (int)($_POST['duration_min'] ?? 0);
$calories_burned = (float)($_POST['calories_burned'] ?? 0);
$date            = input_date($_POST['date'] ?? '');
$notes           = trim($_POST['notes'] ?? '');

// Validation
if ($user_id <= 0 || $exercise_type === '' || !$date || $duration_min <= 0 || $calories_burned < 0) {
    exit('Validation failed. Please go back and check your inputs.');
}

// Ensure user exists in DB
$checkUser = $conn->prepare("SELECT id FROM users WHERE id = ?");
$checkUser->bind_param('i', $user_id);
$checkUser->execute();
$result = $checkUser->get_result();
if ($result->num_rows === 0) {
    exit('Error: User does not exist.');
}
$checkUser->close();

// Insert
$sql = "INSERT INTO exercises (user_id, exercise_type, duration_min, calories_burned, date, notes)
        VALUES (?, ?, ?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param('isidss', $user_id, $exercise_type, $duration_min, $calories_burned, $date, $notes);

if (!$stmt->execute()) {
    exit('Database error: ' . htmlspecialchars($stmt->error));
}

redirect('/exercise/index.php');
