<?php 
define('ROOT_PATH', dirname(__DIR__));
require(ROOT_PATH . "/database.php");

// Retrieve date from calendar
$date = $_GET['date']; // null; 

if (!$date) { 
    echo "No data provided."; 
    exit; 
}

// Start For Mood Function
function getMoodEmojisGroupedByCategory($con) {
    $query = 
    "SELECT mood_id, mood_emoji, mood_category 
    FROM mood 
    ORDER BY mood_category, mood_id";

    $result = mysqli_query($con, $query);

    $moods = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $category = $row['mood_category'];
        if (!isset($moods[$category])) {
            $moods[$category] = [];
        }
        $moods[$category][] = [
            'mood_id' => $row['mood_id'],
            'emoji_path' => $row['mood_emoji'],
        ];
    }

    return $moods;
}
// End For Mood Function

// Start For Emotion Function
function getWeatherOptions($con) {
    $query = "SELECT weather_id, weather_name, weather_icon FROM weather ORDER BY weather_name";
    $result = mysqli_query($con, $query);

    $weatherList = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $weatherList[] = $row;
    }

    return $weatherList;
}
// End For Emotion Function
?>
