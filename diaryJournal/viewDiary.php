<?php
define('ROOT_PATH', dirname(__DIR__));
require(ROOT_PATH . "/database.php");

$entryId = $_GET['id'] ?? null;

if (!$entryId) {
    echo "No diary entry specified.";
    exit;
}

// Get diary entry
$stmt = $con->prepare("SELECT * FROM diary_entries WHERE id = ?");
$stmt->bind_param("i", $entryId);
$stmt->execute();
$result = $stmt->get_result();
$entry = $result->fetch_assoc();

if (!$entry) {
    echo "Diary entry not found.";
    exit;
}

// Get media files
$stmt = $con->prepare("SELECT * FROM diary_media WHERE diary_entry_id = ?");
$stmt->bind_param("i", $entryId);
$stmt->execute();
$mediaResult = $stmt->get_result();
$mediaList = [];

while ($row = $mediaResult->fetch_assoc()) {
    $mediaList[] = $row;
}

// Replace placeholders with media HTML
$content = $entry['content'];
foreach ($mediaList as $media) {
    $tag = "[{$media['position_marker']}]";
    $mediaHtml = '';

    switch ($media['media_type']) {
        case 'images':
            $mediaHtml = "<img src='/uploads/images/{$media['media_path']}' style='max-width:300px;' alt='image'>";
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
?>

<!DOCTYPE html>
<html>
<head>
    <title>Diary Entry</title>
</head>
<body>
    <h2>Diary Entry</h2>
    <p><strong>Date:</strong> <?= htmlspecialchars($entry['entry_date']) ?></p>
    <div><?= $content ?></div>
</body>
</html>
