<!DOCTYPE html>

<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Write New Diary</title>

        <!--Style-->
        <link rel="stylesheet" type="text/css" href="../hamburger.css">
        <link rel="stylesheet" type="text/css" href="../header.css">
        <link rel="stylesheet" type="text/css" href="diary.css">

        <!-- Google Fonts -->
        <!--Top Bar-->
        <link href="https://fonts.googleapis.com/css2?family=Agbalumo&display=swap" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css2?family=Agbalumo&family=Jua&family=Zen+Maru+Gothic&display=swap" rel="stylesheet">

        <link href="https://fonts.googleapis.com/css2?family=Edu+NSW+ACT+Cursive:wght@400..700&display=swap" rel="stylesheet">



        <!--Sidebar-->
        <?php
        define('BASE_PATH', dirname(__DIR__));
        include BASE_PATH.'/hamburger.php'; 
        ?>
        <script src="../hamburger.js"></script>

        <!--PHP Function-->
        <?php include 'update_diary.php'; ?>
    </head>

    <body>
        <div class="container-fluid">
            <!--Header-->
            <div class="top-bar">
                <button class="hamburger">&#9776;</button>
                <a href="../homepage.php" class="title">Gradually</a>
                <a href="../logout.php" class="logout-btn">Log out</a>
            </div>
            <!--End of Header-->

            <!--Start of Hero Section (Top)-->
            <div class="top-hero">
                <h1 class="hero-heading">Write A New Diary</h1>
            </div>
            <!--End of Hero Section (Top)-->

            <!--Start Of Content-->
            <form method="POST" action="updateDiary.php" enctype="multipart/form-data">
    <input type="hidden" name="user_id" value="1">
    <input type="date" name="entry_date" value="2025-08-29">
    <textarea name="content" placeholder="Write your diary here... Include [media:1], [media:2] in text."></textarea>

    <div id="mediaFields">
        <div>
            <input type="file" name="media_files[]">
            <input type="text" name="position_markers[]" placeholder="media:1">
        </div>
    </div>

    <button type="button" onclick="addMediaField()">+ Add More Media</button>
    <button type="submit">Save Entry</button>
</form>

<script>
function addMediaField() {
    const container = document.getElementById('mediaFields');
    const div = document.createElement('div');
    div.innerHTML = `<input type="file" name="media_files[]">
                     <input type="text" name="position_markers[]" placeholder="media:X">`;
    container.appendChild(div);
}
</script>
            <!--End Of Content-->
            
        </div>
        
    </body>

</html>