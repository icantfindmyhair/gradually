<!DOCTYPE html>
<html lang="en">
<?php
require 'database.php';
require 'auth.php';
?>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gradually</title>

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Agbalumo&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Agbalumo&family=Jua&family=Zen+Maru+Gothic&display=swap" rel="stylesheet">

    <!-- External CSS -->
    <link rel="stylesheet" href="homepage.css">

    <!-- ------------------------COPY HEADER------------------------ -->
    <link rel="stylesheet" href="hamburger.css">
    <?php include 'hamburger.php'; ?>
    <script src="hamburger.js"></script>
    <!-- ------------------------UNTIL HERE HEADER------------------------ -->
    
</head>

<body>

<!-- ------------------------COPY HEADER------------------------ -->
    <div class="top-bar">
        <button class="hamburger">&#9776;</button>
        <a href="homepage.php" class="title">Gradually</a>
        <a href="logout.php" class="logout-btn">Log out</a>
    </div>
<!-- ------------------------UNTIL HERE HEADER------------------------ -->

<div id="clock" style="text-align:center; font-size:2rem; margin-top:10px; color:#2B3C48ff; font-family: 'Agbalumo', sans-serif;"></div>

<script>
function updateClock() {
    let now = new Date();
    let hours = String(now.getHours()).padStart(2, '0');
    let minutes = String(now.getMinutes()).padStart(2, '0');
    let seconds = String(now.getSeconds()).padStart(2, '0');

    let timeStr = hours + ":" + minutes + ":" + seconds;

    let options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
    let dateStr = now.toLocaleDateString('en-MY', options);

    document.getElementById('clock').innerHTML = `
        <div style="font-size:3rem;">${timeStr}</div>
        <div style="font-size:1.2rem; margin-top:5px;">${dateStr}</div>
    `;
}

setInterval(updateClock, 1000);
updateClock();
</script>


    <!-- Welcome message -->
    <?php if (isset($_SESSION['username'])) {
        $cookie_value = $_SESSION['username'];
        echo '<h3 style="text-align:center;color:#2B3C48ff;font-family:Zen Maru Gothic">Welcome, '.$cookie_value.'. Let us improve gradually, every day.</h3>';
    } else {
        echo '<p style="text-align:center;color:#2B3C48ff;font-family:Zen Maru Gothic">Welcome, user. Let us improve gradually, every day.</p>';
    } ?>

    <!-- Grid of 4 squares -->
    <div class="grid-container">
            <div class="card" onclick="location.href='exercise/index.php'">
            <img class="icon-placeholder" src="images/exercise.svg" alt="Exercise Tracker">
            <div class="card-title">Fitness Journey</div>
            <div class="card-subtitle">Exercise Tracker</div>
        </div>

        <div class="card" onclick="location.href='diaryJournal/viewCalendar.php'">
            <img class="icon-placeholder" src="images/diary.svg" alt="Diary Journal">
            <div class="card-title">Dear Diary</div>
            <div class="card-subtitle">Diary Journal</div>
        </div>

        <div class="card" onclick="location.href='moneyDashboard.php'">
            <img class="icon-placeholder" src="images/money.svg" alt="Money Tracker">
            <div class="card-title">Budgeting</div>
            <div class="card-subtitle">Money Tracker</div>
        </div>

        <div class="card" onclick="location.href='habitTracker/habitTracker.php'">
            <img class="icon-placeholder" src="images/habit.svg" alt="Habit Tracker">
            <div class="card-title">Build A Habit</div>
            <div class="card-subtitle">Habit Tracker</div>
        </div>
    </div>

</body>
</html>

