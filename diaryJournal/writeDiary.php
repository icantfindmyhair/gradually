<?php
require('functionWriteDiary.php');
// That file is expected to define the functions:
$moodsByCategory = getMoodEmojisGroupedByCategory($con);
$weatherList = getWeatherOptions($con);
// It likely also establishes or uses a database connection via `$con`.

// First category for default display
$firstCategory = array_key_first($moodsByCategory);
?>

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

        <!--Script-->
        <script src="diary.js"></script>

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

            <!--Start of Write Diary Section-->
            <form id="diaryForm" method="POST" action="save_diary.php">
                <!--Date-->
                <label>Date:</label>
                <span class="readonly-date"><?php echo htmlspecialchars($date); ?></span>
                <input type="hidden" name="diary_date" value="<?php echo htmlspecialchars($date); ?>">
                <br><br>

                <div class="moodWeather">
                    <!--Mood Emotion-->
                    <div class="moodWeather-selector">
                        <div id="moodDisplay"> Mood: <span id="selectedEmoji"></span></div>
                        <input type="hidden" name="mood_id" id="mood_id">

                        <div id="moodPicker">
                            <div id="categoryTabs" class="category-tabs active">
                                <?php foreach ($moodsByCategory as $category => $emojis): ?>
                                    <div class="category-tab" data-category="<?= htmlspecialchars($category) ?>">
                                        <?= htmlspecialchars($category) ?>
                                    </div>
                                <?php endforeach; ?>
                            </div>

                            <?php foreach ($moodsByCategory as $category => $emojis): ?>
                                <div class="emoji-list" data-category="<?= htmlspecialchars($category) ?>">
                                    <?php foreach ($emojis as $emoji): ?>
                                        <img src="<?= htmlspecialchars($emoji['emoji_path']) ?>" 
                                            class="emoji-icon" 
                                            data-emoji-id="<?= $emoji['mood_id'] ?>"
                                            data-emoji-src="<?= htmlspecialchars($emoji['emoji_path']) ?>" />
                                    <?php endforeach; ?>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <!--Weather-->
                    <div class="moodWeather-selector">
                        <div id="weatherDisplay">Weather: <span id="selectedWeather"></span></div>
                        <input type="hidden" name="weather_id" id="weather_id">

                        <div id="weatherPicker" class="weather-scroll">
                            <div class="weather-list">
                                <?php foreach ($weatherList as $weather): ?>
                                    <img src="<?= htmlspecialchars($weather['weather_icon']) ?>" 
                                        class="weather-icon" 
                                        data-weather-id="<?= $weather['weather_id'] ?>"
                                        data-weather-name="<?= htmlspecialchars($weather['weather_name']) ?>"
                                        data-weather-src="<?= htmlspecialchars($weather['weather_icon']) ?>" 
                                    />
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                </div>

                <!--Diary Content-->
                <label for="diary_content">Diary Entry:</label><br>
                <textarea name="diary_content" id="diary_content" rows="10" cols="80" placeholder="Write your diary here..."></textarea>

                <br><br>

                <!-- Save Button with HTML Icon -->
                <button type="submit" id="saveBtn" class="save-button">&#128190; Save</button>
            </form>
            <!--End of Write Diary Section-->
            
        </div>
        
    </body>

</html>