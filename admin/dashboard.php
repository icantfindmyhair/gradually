<?php
define('ROOT_PATH', dirname(__DIR__));
require ROOT_PATH.'/database.php';

$sql = '
SELECT 
    u.id, 
    u.username,
    COALESCE(h.habit_count, 0) AS habits,
    COALESCE(e.exercise_count, 0) AS exercises,
    COALESCE(d.diary_count, 0) AS diaries,
    COALESCE(t.trans_count, 0) AS transactions,
    COALESCE(hl.habit_logged, 0) AS habit_logged,
    (
        COALESCE(h.habit_count, 0) +
        COALESCE(e.exercise_count, 0) +
        COALESCE(d.diary_count, 0) +
        COALESCE(t.trans_count, 0) +
        COALESCE(hl.habit_logged, 0)
    ) AS total_activities
FROM users u
LEFT JOIN (
    SELECT user_id, COUNT(*) AS habit_count
    FROM habit_type
    GROUP BY user_id
) h ON u.id = h.user_id
LEFT JOIN (
    SELECT user_id, COUNT(*) AS exercise_count
    FROM exercises
    GROUP BY user_id
) e ON u.id = e.user_id
LEFT JOIN (
    SELECT user_id, COUNT(*) AS diary_count
    FROM diary_entry
    GROUP BY user_id
) d ON u.id = d.user_id
LEFT JOIN (
    SELECT user_id, COUNT(*) AS trans_count
    FROM transaction
    GROUP BY user_id
) t ON u.id = t.user_id
LEFT JOIN (
    SELECT ht.user_id, COUNT(*) AS habit_logged
    FROM habit_log hl
    INNER JOIN habit_type ht ON hl.habit_id = ht.habit_id
    GROUP BY ht.user_id
) hl ON u.id = hl.user_id

';

$result = mysqli_query($con, $sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>User Activity Summary</title>
    <link href="https://fonts.googleapis.com/css2?family=Agbalumo&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Agbalumo&family=Jua&family=Zen+Maru+Gothic&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="dashboard.css">
</head>
<body>
<a href="newAdmin.php" class="newAdmin-btn">Add new admin</a>
<a href="logout.php" class="logout-btn">Log out</a>
<h1>User Activity Summary</h1>

<table>
    <thead>
        <tr>
            <th>User ID</th>
            <th>Username</th>
            <th>Habits</th>
            <th>Exercises</th>
            <th>Diaries</th>
            <th>Transactions</th>
            <th>Total Activities</th>
        </tr>
    </thead>
    <tbody>
        <?php while ($row = mysqli_fetch_assoc($result)) { ?>
            <tr>
                <td><?php echo htmlspecialchars($row['id']); ?></td>
                <td><?php echo htmlspecialchars($row['username']); ?></td>
                <td><?php echo $row['habits'].' habit types, '.$row['habit_logged'].' logs'; ?></td>
                <td><?php echo $row['exercises']; ?></td>
                <td><?php echo $row['diaries']; ?></td>
                <td><?php echo $row['transactions']; ?></td>
                <td class="highlight"><?php echo $row['total_activities']; ?></td>
            </tr>
        <?php } ?>
    </tbody>
</table>



</body>
</html>
