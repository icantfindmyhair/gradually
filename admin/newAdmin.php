<?php
define('ROOT_PATH', dirname(__DIR__));
require ROOT_PATH.'/database.php';
session_start();

error_reporting(E_ALL);
ini_set('display_errors', 1);
$status = '';
if (isset($_REQUEST['username'])) {
    $username = stripslashes($_REQUEST['username']);
    $username = mysqli_real_escape_string($con, $username);
    $password = stripslashes($_REQUEST['password']);
    $password = mysqli_real_escape_string($con, $password);
    $create_date_time = date('Y-m-d H:i:s');
    $query = "INSERT into `admin` (username, password, create_date_time) VALUES ('$username', '".md5($password)."', '$create_date_time')";
    mysqli_query($con, $query) or exit(mysqli_error($con));
    $status = 'New admin added successfully!';
    echo "<script>
		alert('$status');
		window.location.href = 'dashboard.php';
		</script>";
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Add New Admin</title>
    <link href="https://fonts.googleapis.com/css2?family=Agbalumo&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200"/>
    <link rel="stylesheet" href="newAdmin.css">
</head>
<body>

<div class="form">
    <h3>Add New Admin</h3>
    <?php if (!empty($error_message)) { ?>
        <div class="error-snackbar"><?php echo $error_message; ?></div>
    <?php } ?>
    <form action="" method="post" name="login">
        <input type="text" name="username" placeholder="Username" required /><br>
        <input type="password" name="password" placeholder="Password" required /><br>
        <div class="wrap">
        </div>
        <input name="submit" type="submit" value="Add Admin" />
    </form>
</div>
</body>
</html>