<?php session_start(); ?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>User Login</title>
    <link href="https://fonts.googleapis.com/css2?family=Agbalumo&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="login.css">
</head>
<body>
<?php
require 'database.php';
$error_message = '';
if (isset($_POST['username'])) {
    $username = mysqli_real_escape_string($con, stripslashes($_REQUEST['username']));
    $password = mysqli_real_escape_string($con, stripslashes($_REQUEST['password']));
    $query = "SELECT * FROM `users` WHERE username='$username' AND password='".md5($password)."'";
    $result = mysqli_query($con, $query) or exit(mysqli_error($con));
    if (mysqli_num_rows($result) == 1) {
        $userData = mysqli_fetch_assoc($result);
        $_SESSION['username'] = $userData['username'];
        $_SESSION['user_id'] = $userData['id'];
        if (isset($_POST['remember_me'])) {
            setcookie('user', $username, time() + 60 * 60 * 24 * 30, '/');
        }
        header('Location: homepage.php');
        exit;
    } else {
        $error_message = 'Username/password is incorrect.';
    }
}
?>
<div class="form">
    <h1>Gradually</h1>
    <h3>Log in</h3>
    <?php if (!empty($error_message)) { ?>
        <div class="error-snackbar"><?php echo $error_message; ?></div>
    <?php } ?>
    <form action="" method="post" name="login">
        <input type="text" name="username" placeholder="Username" required /><br>
        <input type="password" name="password" placeholder="Password" required /><br>
        <p>
            <label>Remember Me</label>
            <input type="checkbox" name="remember_me" id="remember_me">
        </p>
        <input name="submit" type="submit" value="Login" />
    </form>
    <p>Not registered yet? <a href='registration.php'>Register Here</a></p>
</div>
</body>
</html>
