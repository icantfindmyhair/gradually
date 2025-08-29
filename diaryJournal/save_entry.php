<?php
// Sample form POST data
$userId = $_POST['user_id'];
$date = $_POST['entry_date'];
$content = $_POST['content']; // May include [media:1], [media:2], etc.

$pdo = new PDO('mysql:host=localhost;dbname=your_db', 'user', 'pass');
$stmt = $pdo->prepare("INSERT INTO diary_entries (user_id, entry_date, content) VALUES (?, ?, ?)");
$stmt->execute([$userId, $date, $content]);

echo "Diary saved.";
?>
