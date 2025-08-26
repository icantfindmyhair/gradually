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

$sql = 'INSERT INTO habit_log (habit_id, date, status)
        VALUES (?, ?, ?)
        ON DUPLICATE KEY UPDATE status = VALUES(status)';

$stmt = mysqli_prepare($con, $sql);
mysqli_stmt_bind_param($stmt, 'isi', $habit_id, $date, $checked);
mysqli_stmt_execute($stmt);
mysqli_stmt_close($stmt);

echo $checked == 1 ? 'Habit marked done' : 'Habit marked undone';
