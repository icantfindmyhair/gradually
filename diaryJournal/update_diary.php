<?php
define('ROOT_PATH', dirname(__DIR__));
require(ROOT_PATH . "/database.php");

$diaryId = $_POST['diary_id'] ?? null;
$userId = $_POST['user_id'];
$date = $_POST['entry_date'];
$content = $_POST['content'];

// 1. Insert or Update diary entry
if ($diaryId) {
    $stmt = $con->prepare("UPDATE diary_entries SET entry_date = ?, content = ? WHERE id = ?");
    $stmt->bind_param("ssi", $date, $content, $diaryId);
    $stmt->execute();
} else {
    $stmt = $con->prepare("INSERT INTO diary_entries (user_id, entry_date, content) VALUES (?, ?, ?)");
    $stmt->bind_param("iss", $userId, $date, $content);
    $stmt->execute();
    $diaryId = $stmt->insert_id;
}

// 2. Media upload
$allowedTypes = [
    'image/jpeg' => 'images',
    'image/png'  => 'images',
    'audio/mpeg' => 'audio',
    'video/mp4'  => 'video',
    'application/pdf' => 'docs'
];

if (!empty($_FILES['media_files']) && isset($_POST['position_markers'])) {
    foreach ($_FILES['media_files']['tmp_name'] as $index => $tmpName) {
        $type = $_FILES['media_files']['type'][$index];
        $name = $_FILES['media_files']['name'][$index];
        $positionMarker = $_POST['position_markers'][$index];

        if (array_key_exists($type, $allowedTypes)) {
            $folder = $allowedTypes[$type];
            $uploadDir = ROOT_PATH . "/uploads/$folder/";
            $fileName = time() . '_' . basename($name);
            $targetPath = $uploadDir . $fileName;

            if (move_uploaded_file($tmpName, $targetPath)) {
                $stmt = $con->prepare("INSERT INTO diary_media (diary_entry_id, media_type, media_path, position_marker) VALUES (?, ?, ?, ?)");
                $stmt->bind_param("isss", $diaryId, $folder, $fileName, $positionMarker);
                $stmt->execute();
            }
        }
    }
}

header("Location: viewDiary.php?id=$diaryId");
exit;
?>
