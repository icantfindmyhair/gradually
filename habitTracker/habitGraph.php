<?php

session_start();
define('ROOT_PATH', dirname(__DIR__));
require ROOT_PATH.'/database.php';
header('Content-Type: application/json');
date_default_timezone_set('Asia/Kuala_Lumpur');

$user_id = isset($_SESSION['user_id']) ? (int) $_SESSION['user_id'] : 0;
$daysBack = isset($_GET['days']) ? (int) $_GET['days'] : 30;

if ($daysBack < 7) {
    $daysBack = 7;
}
if ($daysBack > 365) {
    $daysBack = 365;
}

$endDateTime = new DateTime();
$endDateTime->setTime(23, 59, 59);
$startDateTime = (clone $endDateTime)->modify('-'.($daysBack - 1).' days');

$startYmd = $startDateTime->format('Y-m-d');
$endYmd = $endDateTime->format('Y-m-d');

$dateRange = [];
for ($d = clone $startDateTime; $d <= $endDateTime; $d->modify('+1 day')) {
    $dateRange[] = $d->format('Y-m-d');
}

$habits = [];
$q = 'SELECT habit_id, habit_name FROM habit_type WHERE user_id = '.intval($user_id).' ORDER BY habit_name ASC';
$res = mysqli_query($con, $q);
while ($r = mysqli_fetch_assoc($res)) {
    $habits[$r['habit_id']] = [
        'id' => (int) $r['habit_id'],
        'name' => $r['habit_name'],
        'records' => [],
    ];
}

if (count($habits) === 0) {
    echo json_encode(['dates' => $dateRange, 'habits' => []]);
    exit;
}

$ids = implode(',', array_map('intval', array_keys($habits)));
$logSql = sprintf(
    "SELECT habit_id, DATE(`date`) AS ddate, status
     FROM habit_log
     WHERE habit_id IN (%s)
       AND DATE(`date`) BETWEEN '%s' AND '%s'",
    $ids,
    mysqli_real_escape_string($con, $startYmd),
    mysqli_real_escape_string($con, $endYmd)
);
$logsResult = mysqli_query($con, $logSql);
while ($row = mysqli_fetch_assoc($logsResult)) {
    $hid = (int) $row['habit_id'];
    $dd = $row['ddate'];
    $status = (int) $row['status'];
    if (isset($habits[$hid])) {
        if (!isset($habits[$hid]['records'][$dd]) || $habits[$hid]['records'][$dd] < $status) {
            $habits[$hid]['records'][$dd] = $status ? 1 : 0;
        }
    }
}

foreach ($habits as &$h) {
    foreach ($dateRange as $d) {
        if (!isset($h['records'][$d])) {
            $h['records'][$d] = 0;
        }
    }
    ksort($h['records']);
}
unset($h);

echo json_encode([
    'dates' => $dateRange,
    'habits' => array_values($habits),
]);
