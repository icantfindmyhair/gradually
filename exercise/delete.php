<?php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../includes/helpers.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !csrf_verify($_POST['csrf_token'] ?? '')) {
    http_response_code(400);
    exit('Invalid request.');
}

$id = filter_var($_POST['id'] ?? 0, FILTER_VALIDATE_INT);

if (!$id || $id <= 0) {
    redirect('/exercise/index.php');
}

$sql = "DELETE FROM exercises WHERE exercise_id = ? AND user_id = ?";
$stmt = $conn->prepare($sql);

if (!$stmt) {
    error_log("Delete prepare failed: " . $conn->error);
    exit('Database error. Please try again later.');
}

$stmt->bind_param('ii', $id, $_SESSION['user_id']);

if (!$stmt->execute()) {
    error_log("Delete execute failed: " . $stmt->error);
    exit('Could not delete record. Please try again.');
}

$stmt->close();

redirect('/exercise/index.php');
