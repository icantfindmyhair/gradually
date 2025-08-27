<?php
// Go up one level of directory from /gradually/diaryJournal -> /gradually
define('ROOT_PATH', dirname(__DIR__));
include(ROOT_PATH . "/auth.php");
require(ROOT_PATH . "/database.php");

// User Name Logic
if (isset($_SESSION['username'])){
    $username_value = $_SESSION['username'];
}else{
    $username_value = "User";
}
?>