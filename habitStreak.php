<?php

session_start();
require 'database.php';

$user_id = $_SESSION['user_id'];

$sql = '
    SELECT COALESCE(MAX(streak), 0) AS longest_streak
FROM (
  SELECT habit_id, streak
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
      WHERE h.user_id = ?      -- bind this
        AND l.status = 1
      ORDER BY l.habit_id, l.date
    ) t
    CROSS JOIN (SELECT @prev_habit := NULL, @prev_date := NULL, @streak := 0) vars
  ) s1
) s2;

';

$stmt = $con->prepare($sql);
$stmt->bind_param('i', $user_id);
$stmt->execute();
$result = $stmt->get_result()->fetch_assoc();

echo json_encode([
    'longest_streak' => '🔥'.(int) $result['longest_streak'].'🔥',
]);
