<?php

session_start();
require 'database.php';

date_default_timezone_set('Asia/Kuala_Lumpur');

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$user_id = intval($_SESSION['user_id']);

$habit_id = isset($_POST['habit_id']) && $_POST['habit_id'] !== '' ? intval($_POST['habit_id']) : 0;
$habit_name = isset($_POST['habit']) ? trim($_POST['habit']) : '';
$remarks = isset($_POST['remarks']) ? trim($_POST['remarks']) : '';
$repeat_days = isset($_POST['repeat']) && is_array($_POST['repeat']) ? $_POST['repeat'] : [];

if ($habit_name === '') {
    header('Location: habitTracker.php');
    exit;
}

$today = date('Y-m-d');

if ($habit_id) {
    $sql = 'UPDATE habit_type SET habit_name = ?, description = ? WHERE habit_id = ? AND user_id = ?';
    $stmt = mysqli_prepare($con, $sql);
    if ($stmt === false) {
        exit('Prepare failed: '.mysqli_error($con));
    }
    mysqli_stmt_bind_param($stmt, 'ssii', $habit_name, $remarks, $habit_id, $user_id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    $sql = 'DELETE FROM habit_repeat WHERE habit_id = ?';
    $stmt = mysqli_prepare($con, $sql);
    mysqli_stmt_bind_param($stmt, 'i', $habit_id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    if (!empty($repeat_days)) {
        $sql = 'INSERT INTO habit_repeat (habit_id, day_of_week) VALUES (?, ?)';
        $stmt = mysqli_prepare($con, $sql);
        foreach ($repeat_days as $day) {
            $d = trim($day);
            mysqli_stmt_bind_param($stmt, 'is', $habit_id, $d);
            mysqli_stmt_execute($stmt);
        }
        mysqli_stmt_close($stmt);
    }
} else {
    $sql = 'INSERT INTO habit_type (habit_name, description, create_date, user_id) VALUES (?, ?, ?, ?)';
    $stmt = mysqli_prepare($con, $sql);
    if ($stmt === false) {
        exit('Prepare failed: '.mysqli_error($con));
    }
    mysqli_stmt_bind_param($stmt, 'sssi', $habit_name, $remarks, $today, $user_id);
    mysqli_stmt_execute($stmt);
    $habit_id = mysqli_insert_id($con);
    mysqli_stmt_close($stmt);

    if (!empty($repeat_days)) {
        $sql = 'INSERT INTO habit_repeat (habit_id, day_of_week) VALUES (?, ?)';
        $stmt = mysqli_prepare($con, $sql);
        foreach ($repeat_days as $day) {
            $d = trim($day);
            mysqli_stmt_bind_param($stmt, 'is', $habit_id, $d);
            mysqli_stmt_execute($stmt);
        }
        mysqli_stmt_close($stmt);
    }
}

header('Location: habitTracker.php');
exit;
