<?php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../database.php';
require_once __DIR__ . '/../includes/helpers.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !csrf_verify($_POST['csrf_token'] ?? '')) {
    http_response_code(400);
    exit('Invalid request.');
}

$user_id        = (int)($_SESSION['user_id'] ?? 0);
$exercise_type  = trim($_POST['exercise_type'] ?? '');
$duration_min   = (int)($_POST['duration_minutes'] ?? 0);
$calories_burnt = (float)($_POST['calories_burnt'] ?? 0);
$exercise_date  = $_POST['exercise_date'] ?? '';
$notes          = trim($_POST['notes'] ?? '');

// Validate inputs
if ($user_id <= 0 || $exercise_type === '' || !$exercise_date || $duration_min <= 0 || $calories_burnt < 0) {
    exit('Validation failed. Please go back and check your inputs.');
}

// Validate date format
if (!DateTime::createFromFormat('Y-m-d', $exercise_date)) {
    exit('Invalid date format.');
}

// Ensure user exists
$checkUser = $conn->prepare("SELECT id FROM users WHERE id = ?");
$checkUser->bind_param('i', $user_id);
$checkUser->execute();
$result = $checkUser->get_result();
if ($result->num_rows === 0) {
    exit('Error: User does not exist.');
}
$checkUser->close();

// Insert record
$sql = "INSERT INTO exercises 
        (user_id, exercise_type, duration_minutes, calories_burnt, exercise_date, notes, created_at, updated_at)
        VALUES (?, ?, ?, ?, ?, ?, NOW(), NOW())";
$stmt = $conn->prepare($sql);
$stmt->bind_param('isidds', $user_id, $exercise_type, $duration_min, $calories_burnt, $exercise_date, $notes);

if (!$stmt->execute()) {
    exit('Database error: ' . htmlspecialchars($stmt->error));
}

// Optional flash message
$_SESSION['flash'] = ['type' => 'success', 'text' => 'Exercise record added successfully.'];

redirect('/exercise/index.php');
