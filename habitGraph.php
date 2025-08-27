<?php

include 'database.php';

$daysBack = isset($_GET['days']) ? (int) $_GET['days'] : 30;

$endDate = new DateTime();
$startDate = (clone $endDate)->modify('-'.($daysBack - 1).' days');

$dateRange = [];
for ($d = clone $startDate; $d <= $endDate; $d->modify('+1 day')) {
    $dateRange[] = $d->format('Y-m-d');
}

$habits = [];
$habitsResult = mysqli_query($con, 'SELECT habit_id, habit_name FROM habit_type ORDER BY habit_name ASC');
while ($row = mysqli_fetch_assoc($habitsResult)) {
    $habits[$row['habit_id']] = [
        'id' => $row['habit_id'],
        'name' => $row['habit_name'],
        'records' => [],
    ];
}

$logSql = sprintf(
    "SELECT habit_id, date, status 
     FROM habit_log 
     WHERE date BETWEEN '%s' AND '%s'",
    $startDate->format('Y-m-d'),
    $endDate->format('Y-m-d')
);

$logsResult = mysqli_query($con, $logSql);
while ($row = mysqli_fetch_assoc($logsResult)) {
    $habits[$row['habit_id']]['records'][$row['date']] = (int) $row['status'];
}

echo json_encode([
    'dates' => $dateRange,
    'habits' => array_values($habits),
]);
