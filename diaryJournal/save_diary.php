<?php
// Get user_id from session
session_start();

define('ROOT_PATH', dirname(__DIR__));
require(ROOT_PATH . "/database.php");

// Get the user ID from session
$userId = $_SESSION['user_id'] ?? null;

if (!$userId) {
    echo "User not authenticated.";
    exit;
}

// Start For Save Diary
// Get data from form
$user_id = $_SESSION['user_id']; // assuming login system
$mood_id = $_POST['mood_id'] ?? null;
$weather_id = $_POST['weather_id'] ?? null;
$diary_date = $_POST['diary_date'];
$diary_content = $_POST['diary_content'];

// Validate required fields
if (!$user_id || !$diary_date || !$diary_content) {
    echo "Missing required fields.";
    exit;
}

// SQL
$sql = "INSERT INTO diary_entry (user_id, mood_id, weather_id, diary_date, diary_content) 
        VALUES (?, ?, ?, ?, ?)";

$stmt = $con->prepare($sql);

if (!$stmt) {
    die("Prepare failed: " . $conn->error);
}

// Correct bind_param:
//  - i: integer (user_id)
//  - i: integer (mood_id)
//  - i: integer (weather_id)
//  - s: string (diary_date)
//  - s: string (diary_content)

$stmt->bind_param("iiiss", $user_id, $mood_id, $weather_id, $diary_date, $diary_content);

$stmt->execute();

if ($stmt->execute()) {
    echo "Saved successfully!";
} else {
    echo "Error: " . $stmt->error;
}

// End For Save Diary
?>