<?php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/helpers.php';
csrf_generate();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Exercise</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1>Add New Exercise</h1>
    <form action="store.php" method="POST">
        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token']); ?>">

        <label>Exercise Type:</label>
        <input type="text" name="exercise_type" required><br>

        <label>Duration (minutes):</label>
        <input type="number" name="duration_min" min="1" required><br>

        <label>Calories Burned:</label>
        <input type="number" step="0.01" name="calories_burned" min="0" required><br>

        <label>Date:</label>
        <input type="date" name="date" required><br>

        <label>Notes:</label>
        <textarea name="notes"></textarea><br>

        <button type="submit">Save</button>
    </form>
    <a href="index.php">Back to List</a>
</body>
</html>
