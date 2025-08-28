<?php
header('Content-Type: application/json');
/* 
Set HTTP response header to tell browser or client:
The content I am sending back is in JSON format

header: metadata sent before the actual content (like a label on a package)
*/

// Define root path and include DB connection
define('ROOT_PATH', dirname(__DIR__)); // One directory up from current file
require(ROOT_PATH . "/database.php");

$date = $_GET['date'] ?? '';
$exists = false;

if ($date) {
    $stmt = $con->prepare("SELECT diary_id FROM diary_entry WHERE diary_date = ?");
    $stmt->bind_param("s", $date);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $exists = true;
    }

    $stmt->close();
}

echo json_encode(['exists' => $exists]);
?>