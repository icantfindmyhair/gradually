<?php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../includes/helpers.php';

// CSRF + Method Check
if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !csrf_verify($_POST['csrf_token'] ?? '')) {
    http_response_code(400);
    exit('Invalid request.');
}

$user_id         = (int)$_SESSION['user_id'];
$exercise_type   = trim($_POST['exercise_type'] ?? '');
$duration_min    = filter_var($_POST['duration_min'] ?? 0, FILTER_VALIDATE_INT);
$calories_burned = filter_var($_POST['calories_burned'] ?? 0, FILTER_VALIDATE_FLOAT);
$date            = input_date($_POST['date'] ?? '');
$notes           = trim($_POST['notes'] ?? '');

// ✅ Extra Validation (gives clearer error handling)
$errors = [];
if ($exercise_type === '') $errors[] = 'Exercise type is required.';
if (!$date) $errors[] = 'Invalid or missing date.';
if ($duration_min === false || $duration_min <= 0) $errors[] = 'Duration must be a positive number.';
if ($calories_burned === false || $calories_burned < 0) $errors[] = 'Calories must be zero or higher.';

if ($errors) {
    // You could redirect back with query params OR show inline
    // For now just simple exit
    exit('Validation failed: ' . implode(' ', $errors));
}

// ✅ Safer prepared statement
$sql = "INSERT INTO exercises (user_id, exercise_type, duration_min, calories_burned, date, notes)
        VALUES (?, ?, ?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
if (!$stmt) {
    error_log("DB Prepare failed: " . $conn->error);
    exit('A database error occurred. Please try again.');
}

$stmt->bind_param('isidss', $user_id, $exercise_type, $duration_min, $calories_burned, $date, $notes);
if (!$stmt->execute()) {
    error_log("DB Execute failed: " . $stmt->error);
    exit('A database error occurred while saving. Please try again.');
}

// ✅ Redirect after success
redirect('/exercise/index.php');
