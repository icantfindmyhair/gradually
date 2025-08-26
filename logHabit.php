<?php

session_start();
require 'database.php';

if (!isset($_SESSION['user_id'])) {
    exit('Not logged in');
}

$habit_id = $_POST['habit_id'];
$checked = $_POST['checked'];
date_default_timezone_set('Asia/Kuala_Lumpur');
$date = date('Y-m-d');

if ($checked == 1) {
    $sql_insert = 'INSERT INTO habit_log (habit_id, date, status) VALUES (?, ?, 1)
                   ON DUPLICATE KEY UPDATE status = 1';
    $stmt = mysqli_prepare($con, $sql_insert);
    mysqli_stmt_bind_param($stmt, 'is', $habit_id, $date);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    echo 'Habit marked done';
} else {
    $sql_update = 'UPDATE habit_log SET status = 0 WHERE habit_id = ? AND date = ?';
    $stmt = mysqli_prepare($con, $sql_update);
    mysqli_stmt_bind_param($stmt, 'is', $habit_id, $date);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    echo 'Habit marked undone';
}
