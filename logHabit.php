<?php

session_start();
require 'database.php';

if (!isset($_SESSION['user_id'])) {
    exit('Not logged in');
}

$habit_id = $_POST['habit_id'];
$checked = $_POST['checked'];
$date = date('Y-m-d');

if ($checked == 1) {
    $sql_insert = 'INSERT IGNORE INTO habit_log (habit_id, date) VALUES (?, ?)';
    $stmt = mysqli_prepare($con, $sql_insert);
    mysqli_stmt_bind_param($stmt, 'is', $habit_id, $date);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    echo 'Added habit_log';
} else {
    $sql_delete = 'DELETE FROM habit_log WHERE habit_id = ? AND date = ?';
    $stmt = mysqli_prepare($con, $sql_delete);
    mysqli_stmt_bind_param($stmt, 'is', $habit_id, $date);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    echo 'Deleted habit_log';
}
