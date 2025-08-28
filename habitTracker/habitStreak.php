<?php

// calculate longest consecutive streak
session_start();
define('ROOT_PATH', dirname(__DIR__));
require ROOT_PATH.'/database.php';

$user_id = $_SESSION['user_id'];

$sql = '
    SELECT h.habit_name, s2.max_streak
    FROM habit_type h
    JOIN (
        SELECT habit_id, MAX(streak) AS max_streak
        FROM (
            SELECT
              t.habit_id,
              t.date,
              @streak := IF(@prev_habit = t.habit_id AND DATEDIFF(t.date, @prev_date) = 1,
                            @streak + 1, 1) AS streak,
              @prev_date := t.date,
              @prev_habit := t.habit_id
            FROM (
              SELECT l.habit_id, l.date
              FROM habit_log l
              JOIN habit_type h ON h.habit_id = l.habit_id
              WHERE h.user_id = ?        -- bind this
                AND l.status = 1
              ORDER BY l.habit_id, l.date
            ) t
            CROSS JOIN (SELECT @prev_habit := NULL, @prev_date := NULL, @streak := 0) vars
        ) s1
        GROUP BY habit_id
    ) s2 ON s2.habit_id = h.habit_id
    ORDER BY s2.max_streak DESC
    LIMIT 1
';

$stmt = $con->prepare($sql);
$stmt->bind_param('i', $user_id);
$stmt->execute();
$result = $stmt->get_result()->fetch_assoc();

echo json_encode([
    'longest_streak' => '🔥'.($result['habit_name'] ?? 'No habit').' - '.(int) $result['max_streak'].' days🔥',
]);
