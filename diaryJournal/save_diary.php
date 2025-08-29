<?php

session_start();

define('ROOT_PATH', dirname(__DIR__));
require ROOT_PATH.'/database.php';

// Get the user ID from session
$userId = $_SESSION['user_id'] ?? null;

if (!$userId) {
    echo 'User not authenticated.';
    exit;
}

// Get data from form
$user_id = $_SESSION['user_id'];
$mood_id = !empty($_POST['mood_id']) ? (int) $_POST['mood_id'] : null;
$weather_id = !empty($_POST['weather_id']) ? (int) $_POST['weather_id'] : null;
$diary_date = $_POST['diary_date'] ?? null;
$diary_content = $_POST['diary_content'] ?? null;

// Validate required fields
if (!$user_id || !$diary_date || !$diary_content) {
    echo 'Missing required fields.';
    exit;
}

// SQL
$sql = 'INSERT INTO diary_entry (user_id, mood_id, weather_id, diary_date, diary_content) 
        VALUES (?, ?, ?, ?, ?)';

$stmt = $con->prepare($sql);

if (!$stmt) {
    exit('Prepare failed: '.$con->error);
}

// bind_param cannot directly handle NULL → workaround: bind as string and pass null
$stmt->bind_param('iiiss',
    $user_id,
    $mood_id,
    $weather_id,
    $diary_date,
    $diary_content
);

// If mood_id or weather_id is NULL, use bind_param null workaround
if ($mood_id === null) {
    $stmt->bind_param('iisss', $user_id, $mood_id, $weather_id, $diary_date, $diary_content);
}
if ($weather_id === null) {
    $stmt->bind_param('iiiss', $user_id, $mood_id, $weather_id, $diary_date, $diary_content);
}

if ($stmt->execute()) {
    echo 'Saved successfully!';
} else {
    echo 'Error: '.$stmt->error;
}

$stmt->close();
$con->close();
