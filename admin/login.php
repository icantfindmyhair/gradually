<?php session_start();
define('ROOT_PATH', dirname(__DIR__));
require ROOT_PATH.'/database.php';
$error = '';

if (isset($_GET['token'])) { ?>
<script>
  document.addEventListener('DOMContentLoaded', function () {
    document.getElementById('popup2').classList.add('open-popup');
  });
</script>
<?php }

if (!empty($error)) { ?>

<script>
  document.addEventListener('DOMContentLoaded', function () {
    document.getElementById('popup').classList.add('open-popup');
    document.getElementById('err').innerText = "<?php echo $error; ?>";
  });
</script>
<?php } ?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Admin Login</title>
    <link href="https://fonts.googleapis.com/css2?family=Agbalumo&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200"/>
    <link rel="stylesheet" href="login.css">
</head>
<body>
<?php
$error_message = '';
if (isset($_POST['username'])) {
    $username = mysqli_real_escape_string($con, stripslashes($_REQUEST['username']));
    $password = mysqli_real_escape_string($con, stripslashes($_REQUEST['password']));
    $query = "SELECT * FROM `admin` WHERE username='$username' AND password='".md5($password)."'";
    $result = mysqli_query($con, $query) or exit(mysqli_error($con));
    if (mysqli_num_rows($result) == 1) {
        $userData = mysqli_fetch_assoc($result);
        $_SESSION['username'] = $userData['username'];
        $_SESSION['user_id'] = $userData['id'];
        header('Location: dashboard.php');
        exit;
    } else {
        $error_message = 'Username/password is incorrect.';
    }
}
?>
<div class="form">
    <h1>Gradually</h1>
    <h3>Admin Log in</h3>
    <?php if (!empty($error_message)) { ?>
        <div class="error-snackbar"><?php echo $error_message; ?></div>
    <?php } ?>
    <form action="" method="post" name="login">
        <input type="text" name="username" placeholder="Username" required /><br>
        <input type="password" name="password" placeholder="Password" required /><br>
        <div class="wrap">
        </div>
        <input name="submit" type="submit" value="Login" />
    </form>
</div>
</body>
</html>
