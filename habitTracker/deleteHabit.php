<?php

define('ROOT_PATH', dirname(__DIR__));
require ROOT_PATH.'/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['habit_id'])) {
    $habitId = intval($_POST['habit_id']);

    try {
        $stmt = $con->prepare('DELETE FROM habit_repeat WHERE habit_id = ?');
        $stmt->execute([$habitId]);

        $stmt = $con->prepare('DELETE FROM habit_log WHERE habit_id = ?');
        $stmt->execute([$habitId]);

        $stmt = $con->prepare('DELETE FROM habit_type WHERE habit_id = ?');
        $stmt->execute([$habitId]);

        if ($stmt->rowCount() > 0) {
            echo 'success';
        } else {
            echo 'not_found';
        }
    } catch (PDOException $e) {
        http_response_code(500);
        echo 'error: '.$e->getMessage();
    }
} else {
    http_response_code(400);
    echo 'invalid_request';
}
