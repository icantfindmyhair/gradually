<?php

session_start();
require 'database.php';

$user_id = $_SESSION['user_id'];

$sql = '
    SELECT MAX(streak) as longest_streak
    FROM (
        SELECT h.habit_id, MAX(s.streak) as streak
        FROM habit_type h
        LEFT JOIN (
            SELECT habit_id, COUNT(*) as streak
            FROM habit_log
            WHERE status = 1
            GROUP BY habit_id
        ) s ON h.habit_id = s.habit_id
        WHERE h.user_id = ?
        GROUP BY h.habit_id
    ) sub
';

$stmt = $con->prepare($sql);
$stmt->bind_param('i', $user_id);
$stmt->execute();
$result = $stmt->get_result()->fetch_assoc();

echo json_encode([
    'longest_streak' => '🔥'.(int) $result['longest_streak'].'🔥',
]);
