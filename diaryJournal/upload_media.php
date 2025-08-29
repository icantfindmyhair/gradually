<?php
$diaryEntryId = $_POST['diary_entry_id'];
$positionMarker = $_POST['position_marker']; // e.g. media:1

$allowedTypes = [
    'image/jpeg' => 'images',
    'image/png'  => 'images',
    'audio/mpeg' => 'audio',
    'video/mp4'  => 'video',
    'application/pdf' => 'docs'
];

if (isset($_FILES['media_file'])) {
    $file = $_FILES['media_file'];
    $type = $file['type'];

    if (array_key_exists($type, $allowedTypes)) {
        $folder = $allowedTypes[$type];
        $uploadDir = "uploads/$folder/";
        $fileName = time() . '_' . basename($file['name']);
        $targetPath = $uploadDir . $fileName;

        if (move_uploaded_file($file['tmp_name'], $targetPath)) {
            // Save to DB
            $pdo = new PDO('mysql:host=localhost;dbname=your_db', 'user', 'pass');
            $stmt = $pdo->prepare("INSERT INTO diary_media (diary_entry_id, media_type, media_path, position_marker) VALUES (?, ?, ?, ?)");
            $stmt->execute([$diaryEntryId, $folder, $fileName, $positionMarker]);

            echo "Upload successful!";
        } else {
            echo "Error uploading file.";
        }
    } else {
        echo "Invalid file type.";
    }
}
?>
