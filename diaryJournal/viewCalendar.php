<!DOCTYPE html>

<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Calender Diary Journal</title>

        <!--Style-->
        <link rel="stylesheet" type="text/css" href="../hamburger.css">
        <link rel="stylesheet" type="text/css" href="../header.css">
        <link rel="stylesheet" type="text/css" href="calendar.css">

        <!-- Google Fonts -->
        <!--Top Bar-->
        <link href="https://fonts.googleapis.com/css2?family=Agbalumo&display=swap" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css2?family=Agbalumo&family=Jua&family=Zen+Maru+Gothic&display=swap" rel="stylesheet">
        <!--Calendar-->
        <link href="https://fonts.googleapis.com/css2?family=Edu+NSW+ACT+Cursive:wght@400..700&display=swap" rel="stylesheet">


        <!--Sidebar-->
        <?php 
        define('BASE_PATH', dirname(__DIR__));
        include BASE_PATH . '/hamburger.php'; ?>
        <script src="../hamburger.js"></script>

        <!--Script-->
        <script src="calendar.js"></script>

        <!--PHP Function-->
        <?php include 'functionCalendar.php'; ?>
    </head>

    <body>
        <div class="container-fluid">
            <!--Header-->
            <div class="top-bar">
                <button class="hamburger">&#9776;</button>
                <a href="homepage.php" class="title">Gradually</a>
                <a href="logout.php" class="logout-btn">Log out</a>
            </div>
            <!--End of Header-->

            <!--Start of Hero Section (Top)-->
            <div class="top-hero">
                <h2 class="hero-salutation"><span id="greeting"></span>, <?= $username_value ?></h2>
                <h1 class="hero-heading">Welcome to personal Diary & Journal corner</h1>
                <h3 class="hero-subheading">This is your journal - your thoughts, your voice, your space.</h3>
            </div>
            <!--End of Hero Section (Top)-->

            <!--Start of Calendar System-->
            <div class="content-calendar">
                <!--Display, move to previous or next month, year-->
                <div class="monthYear-control">
                    <button onclick="changeMonth(-1)" class="arrow-button">&lt;</button>
                    <span id="monthYear"></span>
                    <button onclick="changeMonth(1)" class="arrow-button">&gt;</button>
                </div>

                <div id="calendar"></div>
            </div>
            <!--End of Calendar System-->
            
        </div>
        
    </body>

</html>