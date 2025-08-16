<?php session_start()?>

<!DOCTYPE html>
<html>
    <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Money Tracker</title>

    <!--Bootstrap-->
    <link rel="stylesheet" href="hamburger.css">
    <link rel="stylesheet" href="header.css">
    
    <!--Sidebar-->    
    <?php include 'hamburger.php'; ?>
    <script src="hamburger.js"></script>

    <!--Custom CSS-->
    <link rel="stylesheet" href="hamburger.css">    
    <link rel="stylesheet" href="header.css">
    <link rel="stylesheet" href="moneyD.css">

    </head>
    <body>

    <!--Header-->
    <div class="top-bar">
        <button class="hamburger">&#9776;</button>
        <a href="homepage.php" class="title">Gradually</a>
        <a href="logout.php" class="logout-btn">Log out</a>
    </div>

    </body>
</html>