<?php
$entryId = $_GET['id'];

$pdo = new PDO('mysql:host=localhost;dbname=your_db', 'user', 'pass');

// Get diary entry
$stmt = $pdo->prepare("SELECT * FROM diary_entries WHERE id = ?");
$stmt->execute([$entryId]);
$entry = $stmt->fetch(PDO::FETCH_ASSOC);

// Get associated media
$stmt = $pdo->prepare("SELECT * FROM diary_media WHERE diary_entry_id = ?");
$stmt->execute([$entryId]);
$mediaList = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Replace placeholders in content
$content = $entry['content'];
foreach ($mediaList as $media) {
    $tag = "[{$media['position_marker']}]";
    $mediaHtml = '';

    switch ($media['media_type']) {
        case 'images':
            $mediaHtml = "<img src='/uploads/images/{$media['media_path']}' alt='' style='max-width:300px'>";
            break;
        case 'audio':
            $mediaHtml = "<audio controls><source src='/uploads/audio/{$media['media_path']}'></audio>";
            break;
        case 'video':
            $mediaHtml = "<video controls width='300'><source src='/uploads/video/{$media['media_path']}'></video>";
            break;
        case 'docs':
            $mediaHtml = "<a href='/uploads/docs/{$media['media_path']}' target='_blank'>View Document</a>";
            break;
    }

    $content = str_replace("[$tag]", $mediaHtml, $content);
}

echo "<h2>Diary Entry</h2>";
echo "<p>Date: " . $entry['entry_date'] . "</p>";
echo "<div>" . $content . "</div>";
?>
