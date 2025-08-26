<?php

session_start();
require 'database.php';
header('Content-Type: application/json');
date_default_timezone_set('Asia/Kuala_Lumpur');

$user_id = $_SESSION['user_id'];
$today = date('Y-m-d');
$startOfWeek = date('Y-m-d', strtotime('monday this week'));
$endOfWeek = date('Y-m-d', strtotime('sunday this week'));
$startOfMonth = date('Y-m-01');
$endOfMonth = date('Y-m-t');

function getStats($con, $user_id, $startDate, $endDate)
{
    $sql = '
        SELECT 
            SUM(CASE WHEN l.status = 1 THEN 1 ELSE 0 END) AS completed,
            SUM(CASE WHEN (l.status = 0 OR l.status IS NULL) THEN 1 ELSE 0 END) AS remaining
        FROM habit_type h
        LEFT JOIN habit_log l 
               ON h.habit_id = l.habit_id 
              AND l.date BETWEEN ? AND ?
        WHERE h.user_id = ?
          AND (
              EXISTS (
                  SELECT 1 FROM habit_repeat r
                  WHERE r.habit_id = h.habit_id
                    AND r.day_of_week BETWEEN DAYOFWEEK(?) - 1 AND DAYOFWEEK(?) - 1
              )
              OR NOT EXISTS (
                  SELECT 1 FROM habit_repeat r
                  WHERE r.habit_id = h.habit_id
              )
          )
    ';
    $stmt = $con->prepare($sql);
    $stmt->bind_param('ssiss', $startDate, $endDate, $user_id, $startDate, $endDate);
    $stmt->execute();
    $result = $stmt->get_result()->fetch_assoc();

    return [
        'completed' => (int) $result['completed'],
        'remaining' => (int) $result['remaining'],
    ];
}

$response = [
    'today' => getStats($con, $user_id, $today, $today),
    'week' => getStats($con, $user_id, $startOfWeek, $endOfWeek),
    'month' => getStats($con, $user_id, $startOfMonth, $endOfMonth),
];

echo json_encode($response);
$con->close();
