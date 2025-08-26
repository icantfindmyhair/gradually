<?php

session_start();
require 'database.php';

$habitId = intval($_GET['habit_id']);
$userId = $_SESSION['user_id'];

$sql = "SELECT h.habit_id, h.habit_name, h.description, 
               COALESCE(GROUP_CONCAT(r.day_of_week), '') AS repeat_days
        FROM habit_type h
        LEFT JOIN habit_repeat r ON h.habit_id = r.habit_id
        WHERE h.habit_id = ? AND h.user_id = ?
        GROUP BY h.habit_id";

$stmt = $con->prepare($sql);
$stmt->bind_param('ii', $habitId, $userId);
$stmt->execute();
$result = $stmt->get_result();
$data = $result->fetch_assoc();

if ($data) {
    $data['repeat_days'] = $data['repeat_days'] !== ''
        ? explode(',', $data['repeat_days'])
        : [];
    echo json_encode($data);
} else {
    echo json_encode(['error' => 'Habit not found']);
}
