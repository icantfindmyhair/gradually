<?php

session_start();
require 'database.php';

$habit_name = $_POST['habit'];
$remarks = $_POST['remarks'];
$repeat_days = isset($_POST['repeat']) ? $_POST['repeat'] : [];
date_default_timezone_set('Asia/Kuala_Lumpur');
$created_date = date('Y-m-d');
$user_id = $_SESSION['user_id'];

$sql = 'INSERT INTO habit_type (habit_name, description, create_date, user_id) VALUES (?, ?, ?, ?)';
$stmt = mysqli_prepare($con, $sql);
mysqli_stmt_bind_param($stmt, 'sssi', $habit_name, $remarks, $created_date, $user_id);
mysqli_stmt_execute($stmt);
$habit_id = mysqli_insert_id($con);

foreach ($repeat_days as $day) {
    $sql2 = 'INSERT INTO habit_repeat (habit_id, day_of_week) VALUES (?, ?)';
    $stmt2 = mysqli_prepare($con, $sql2);
    mysqli_stmt_bind_param($stmt2, 'is', $habit_id, $day);
    mysqli_stmt_execute($stmt2);
}

mysqli_stmt_close($stmt);
mysqli_close($con);

header('Location: habitTracker.php');
exit;
