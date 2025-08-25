<?php

require 'database.php';

date_default_timezone_set('Asia/Kuala_Lumpur');
$date = date('Y-m-d');
$user_id = 1;

$sql = '
    SELECT 
        COUNT(h.habit_id) AS total_habits,
        SUM(CASE WHEN l.status = 1 THEN 1 ELSE 0 END) AS completed
    FROM habit_type h
    LEFT JOIN habit_log l 
           ON h.habit_id = l.habit_id 
           AND l.date = ?
    WHERE h.user_id = ?
';

$stmt = $con->prepare($sql);
$stmt->bind_param('si', $date, $user_id);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

$total = (int) $row['total_habits'];
$completed = (int) $row['completed'];
$remaining = $total - $completed;

$stmt->close();
?>


<canvas id="ringChart" width="200" height="200"></canvas>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const ctx = document.getElementById('ringChart').getContext('2d');

const completed = <?php echo $completed; ?>;
const remaining = <?php echo $remaining; ?>;

new Chart(ctx, {
  type: 'doughnut',
  data: {
    labels: ['Completed', 'Remaining'],
    datasets: [{
      data: [completed, remaining],
      backgroundColor: ['#4caf50', '#ddd'],
      borderWidth: 0
    }]
  },
  options: {
    cutout: '70%',
    plugins: {
      legend: { position: 'bottom' }
    }
  }
});
</script>
