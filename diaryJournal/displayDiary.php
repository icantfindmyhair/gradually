<!DOCTYPE html>
<html>
<head>
    <title>View / Edit Diary</title>
    <link rel="stylesheet" href="diary.css">
    <script src="diary.js"></script>
</head>
<body>
    <h1>Edit Diary for <?= htmlspecialchars($date) ?></h1>

    <form method="POST" action="update_diary.php">
        <input type="hidden" name="diary_id" value="<?= $diary_id ?>">
        <input type="hidden" name="diary_date" value="<?= htmlspecialchars($date) ?>">

        <!-- Mood Picker -->
        <div id="moodDisplay">Mood: <span id="selectedEmoji">
            <?php
            foreach ($moodsByCategory as $category => $emojis) {
                foreach ($emojis as $emoji) {
                    if ($emoji['mood_id'] == $selectedMoodId) {
                        echo '<img src="' . htmlspecialchars($emoji['emoji_path']) . '" width="48" height="48">';
                    }
                }
            }
            ?>
        </span></div>
        <input type="hidden" name="mood_id" id="mood_id" value="<?= $selectedMoodId ?>">

        <!-- Mood Picker UI (same structure as your write page) -->
        <div id="moodPicker">
            <div id="categoryTabs" class="category-tabs">
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

        <!-- Weather Picker -->
        <div id="weatherDisplay">Weather: <span id="selectedWeather">
            <?php
            foreach ($weatherList as $weather) {
                if ($weather['weather_id'] == $selectedWeatherId) {
                    echo '<img src="' . htmlspecialchars($weather['weather_icon']) . '" width="48" height="48"> ' . htmlspecialchars($weather['weather_name']);
                }
            }
            ?>
        </span></div>
        <input type="hidden" name="weather_id" id="weather_id" value="<?= $selectedWeatherId ?>">

        <div id="weatherPicker" class="weather-scroll">
            <div class="weather-list">
                <?php foreach ($weatherList as $weather): ?>
                    <img src="<?= htmlspecialchars($weather['weather_icon']) ?>" 
                        class="weather-icon" 
                        data-weather-id="<?= $weather['weather_id'] ?>"
                        data-weather-name="<?= htmlspecialchars($weather['weather_name']) ?>"
                        data-weather-src="<?= htmlspecialchars($weather['weather_icon']) ?>" />
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Diary Content -->
        <label>Diary Content:</label><br>
        <textarea name="diary_content" id="diary_content" rows="10" cols="80"><?= htmlspecialchars($diaryContent) ?></textarea><br><br>

        <button type="submit" class="save-button">&#128190; Update</button>
    </form>

    <!-- Delete Button -->
    <form method="POST" action="delete_diary.php" onsubmit="return confirm('Are you sure you want to delete this diary entry?');">
        <input type="hidden" name="diary_id" value="<?= $diary_id ?>">
        <button type="submit" class="delete-button">&#128465; Delete</button>
    </form>
</body>
</html>