<!DOCTYPE html>
<html lang="en">

<?php
session_start();
define('ROOT_PATH', dirname(__DIR__));
require ROOT_PATH.'/database.php';
?>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Habit Tracker</title>

    <link href="https://fonts.googleapis.com/css2?family=Agbalumo&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Agbalumo&family=Jua&family=Zen+Maru+Gothic&display=swap"
        rel="stylesheet">

    <link rel="stylesheet" href="habitTracker.css">

    <link rel="stylesheet" href="../hamburger.css">
    <?php include ROOT_PATH.'/hamburger.php'; ?>
    <script src="../hamburger.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

</head>

<body>
    <div class="top-bar">
        <button class="hamburger">&#9776;</button>
        <a href="../homepage.php" class="title">Gradually</a>
        <a href="../logout.php" class="logout-btn">Log out</a>
    </div>

    <div class="main-content">
        <div class="todo-list">
            <div class="todo-header">
                <h2 id="today-date">Saturday, Aug 16</h2>
                <button class="add-btn" id="open-form">+</button>
            </div>
    <button class="viewHabitsBtn" id="viewHabitsBtn">View all habits</button>

    <div id="habitModal" class="modal">
  <div class="modal-content">
    <span class="close">&times;</span>
    <h3>All Active Habits</h3>
    <div id="allHabitsContainer">
      Loading habits...
    </div>
  </div>
</div>

  <?php
    date_default_timezone_set('Asia/Kuala_Lumpur');
$today_date = date('Y-m-d');
$today_day = strtolower(date('l', strtotime($today_date)));
$user_id = $_SESSION['user_id'];

$sql = '
    SELECT h.habit_id, h.habit_name, h.description,
          COALESCE(l.status, 0) AS status
    FROM habit_type h
    LEFT JOIN habit_log l 
          ON h.habit_id = l.habit_id AND l.date = ?
    WHERE h.user_id = ?
    AND (
        EXISTS (
            SELECT 1 FROM habit_repeat r 
            WHERE r.habit_id = h.habit_id 
              AND r.day_of_week = ?
        )
        OR NOT EXISTS (
            SELECT 1 FROM habit_repeat r 
            WHERE r.habit_id = h.habit_id
        )
    )
';
$stmt = $con->prepare($sql);
$stmt->bind_param('sis', $today_date, $user_id, $today_day);
$stmt->execute();
$result = $stmt->get_result();

if (mysqli_num_rows($result) === 0) {
    echo 'No habits planned for today.';
}

echo '<ul class="todo-items">';
while ($row = mysqli_fetch_assoc($result)) {
    $checked = $row['status'] ? 'checked' : '';
    $completedClass = $row['status'] ? ' class="completed"' : '';
    echo '<li'.$completedClass.'>';
    echo '<div class="habit-left">';
    echo '<input type="checkbox" class="habit-checkbox" data-habitid="'.$row['habit_id'].'" id="habit'.$row['habit_id'].'" '.$checked.'>';
    echo '<label for="habit'.$row['habit_id'].'">'.htmlspecialchars($row['habit_name']).'</label>';
    echo '</div>';

    echo '<div class="habit-menu">';
    echo '<button class="menu-btn">⋮</button>';
    echo '<div class="menu-dropdown">';
    echo '<button class="edit-btn" data-habitid="'.$row['habit_id'].'">Edit</button>';
    echo '<button class="delete-btn" data-habitid="'.$row['habit_id'].'">Delete</button>';
    echo '</div>';
    echo '</div>';
    echo '</li>';
}
echo '</ul>';
?>
        </div>

        <!-- Popup Form -->
        <div class="popup-form" id="popup-form">
            <div class="popup-content">
                <div class="popup-header">
                    <h3>Add A New Habit</h3>
                    <span class="close-btn" id="close-form">&times;</span>
                   </div>
                   <form action="addHabit.php" method="POST" id="habit-form">
                       <input type="hidden" id="habit_id" name="habit_id">

                       <label for="habit" class="field-label">Habit Name: (Required)</label>
                       <input type="text" id="habit" name="habit" required><br>

                       <label for="remarks" class="field-label">Remarks: (Optional)</label>
                       <input type="text" id="remarks" name="remarks"><br>

                       <label class="field-label">Repeat: (Default repeats everyday)</label>
                       <ul class="repeat-list">
                           <li>
                               <label class="repeat-row">
                                <input type="checkbox" name="repeat[]" value="monday">
                                <span class="repeat-text">Every Monday</span>
                                <span class="repeat-tick" aria-hidden="true"></span>
                            </label>
                        </li>
                        <li>
                            <label class="repeat-row">
                                <input type="checkbox" name="repeat[]" value="tuesday">
                                <span class="repeat-text">Every Tuesday</span>
                                <span class="repeat-tick" aria-hidden="true"></span>
                            </label>
                        </li>
                        <li>
                            <label class="repeat-row">
                                <input type="checkbox" name="repeat[]" value="wednesday">
                                <span class="repeat-text">Every Wednesday</span>
                                <span class="repeat-tick" aria-hidden="true"></span>
                            </label>
                        </li>
                        <li>
                            <label class="repeat-row">
                                <input type="checkbox" name="repeat[]" value="thursday">
                                <span class="repeat-text">Every Thursday</span>
                                <span class="repeat-tick" aria-hidden="true"></span>
                            </label>
                        </li>
                        <li>
                            <label class="repeat-row">
                                <input type="checkbox" name="repeat[]" value="friday">
                                <span class="repeat-text">Every Friday</span>
                                <span class="repeat-tick" aria-hidden="true"></span>
                            </label>
                        </li>
                        <li>
                            <label class="repeat-row">
                                <input type="checkbox" name="repeat[]" value="saturday">
                                <span class="repeat-text">Every Saturday</span>
                                <span class="repeat-tick" aria-hidden="true"></span>
                            </label>
                        </li>
                        <li>
                            <label class="repeat-row">
                                <input type="checkbox" name="repeat[]" value="sunday">
                                <span class="repeat-text">Every Sunday</span>
                                <span class="repeat-tick" aria-hidden="true"></span>
                            </label>
                        </li>
                    </ul>

                    <div class="popup-buttons">
                        <button type="submit">Add</button>
                    </div>
                </form>

            </div>
        </div>

        <?php
date_default_timezone_set('Asia/Kuala_Lumpur');
$date = date('Y-m-d');
$user_id = $_SESSION['user_id'];

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
$total = (int) $row['total_habits'];
$completed = (int) $row['completed'];
$remaining = $total - $completed;

$percentage = $total > 0 ? round(($completed / $total) * 100) : 0;
?>


<div class="overview">
  <h1>Habit Overview</h1>
<div id="quoteBox" style="
    background-color: #ffffff80;
    border-top: 2px solid #2B3C48ff;
    border-bottom: 2px solid #2B3C48ff;
    border-left: 5px solid #2B3C48ff;
    border-right: 5px solid #2B3C48ff;
    padding: 15px;
    margin-bottom: 20px;
    border-radius: 6px;
    font-family: 'Zen Maru Gothic', sans-serif;
    text-align: center;
">
  <div style="font-weight: bold; font-size: 18px; margin-bottom: 5px;">
    Current Longest Streak
  </div>
  <div id="streakNumber" style="
      font-size: 32px;
      font-weight: bold;
      color: #2B3C48;
      margin-bottom: 10px;
  ">
    0
  </div>
  <div id="quoteText" style="
      font-style: italic;
      font-size: 16px;
  ">
    Loading motivational quote...
  </div>
</div>

  <div style="display: flex; gap: 20px; justify-content: center;">
    <div style="width: 150px; height: 150px;">
      <canvas id="habitChart1"></canvas>
    </div>
    <div style="width: 150px; height: 150px;">
      <canvas id="habitChart2"></canvas>
    </div>
    <div style="width: 150px; height: 150px;">
      <canvas id="habitChart3"></canvas>
    </div>
  </div>
  <div id="graphContainer"></div>
</div>

    </div>
</body>

<script src="habitDate.js"></script>
<script src="addHabit.js"></script>
<script src="habitMenu.js"></script>
<script src="habitForm.js"></script>
<script src="habitLog.js"></script>
<script src="motivationalQuotes.js"></script>
<script src="habitListAll.js"></script>
<script src="habitGraph.js"></script>
<script src="chart.js"></script>

</html>