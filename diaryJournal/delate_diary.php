<?php
require('../database.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $diary_id = $_POST['diary_id'] ?? null;

    if (!$diary_id || !is_numeric($diary_id)) {
        echo "Invalid diary ID.";
        exit;
    }

    $stmt = $con->prepare("DELETE FROM diary WHERE diary_id = ?");
    $stmt->bind_param("i", $diary_id);

    if ($stmt->execute()) {
        header("Location: ../homepage.php?message=Diary+deleted");
        exit;
    } else {
        echo "Failed to delete diary.";
    }
}
?>
