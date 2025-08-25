<?php session_start(); 
require 'database.php';
$error = '';

if (isset($_GET['token'])): ?>
<script>
  document.addEventListener('DOMContentLoaded', function () {
    document.getElementById('popup2').classList.add('open-popup');
  });
</script>
<?php endif;

if (isset($_POST['email']) && isset($_POST['requestReset'])&& $_POST['requestReset'] == 1) {
    $email = mysqli_real_escape_string($con, $_POST['email']);

    // Check if email exists
    $check_user = mysqli_query($con, "SELECT * FROM users WHERE email='$email'");
    if (mysqli_num_rows($check_user) > 0) {
        $token = bin2hex(random_bytes(50)); // Generate unique token

        // Store token in database
        mysqli_query($con, "INSERT INTO password_resets (email, token) VALUES ('$email', '$token')");

        header("Location: login.php?token=$token");
        exit();
                    
    } else {
        $error = 'Email not found.';
        echo "<script>
            alert('$error');
            window.location.href = 'login.php';
		</script>";
    }
}

if (!empty($error)) { ?>

<script>
  document.addEventListener('DOMContentLoaded', function () {
    document.getElementById('popup').classList.add('open-popup');
    document.getElementById('err'),innerText = "<?php echo $error; ?>";
  });
</script>
<?php } ?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>User Login</title>
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
        exit();
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
        <div class="wrap">
            <p>
                <label>Remember Me</label>
                <input type="checkbox" name="remember_me" id="remember_me">
            </p>
            <button type="button" onclick="openPopup()">Forget Password?</button>
        </div>
        <input name="submit" type="submit" value="Login" />
    </form>
    <p>Not registered yet? <a href='registration.php'>Register Here</a></p>

    <div class="popup" id="popup">
        <button type="button" class="close" onclick="closePopup()"><span class="material-symbols-outlined">close</span></button>
        <p>Reset Password</p>
        <form method="post">
            <input type="hidden" name="requestReset" value="1"/>
            <p>Your Email:</p>
            <input type="email" name="email" placeholder="Enter Your Email" required/>
            <input type="submit" value="Confirm">
        </form>
    </div>

    <?php 
    if ((isset($_GET['token'])) && $_POST['renewPwd'] == 1) {
    $token = mysqli_real_escape_string($con, $_GET['token']);

    // Verify token
        $query = mysqli_query($con, "SELECT * FROM password_resets WHERE token='$token' LIMIT 1");
        if (mysqli_num_rows($query) == 1) {
            $row = mysqli_fetch_assoc($query);
            $email = $row['email'];

            if ($_SERVER["REQUEST_METHOD"] == "POST" && !empty($_POST['password'])) {
                $password = mysqli_real_escape_string($con, $_POST['password']);
                $hashed_password = md5($password);

                // Update password
                mysqli_query($con, "UPDATE users SET password='$hashed_password' WHERE email='$email'");

                // Delete reset request
                mysqli_query($con, "DELETE FROM password_resets WHERE email='$email'");

                // Redirect to login page with success message
                header("Location: login.php?reset_success=1");
                exit();
            }
        } else {
            echo "<p>Invalid or expired token.</p>";
        }
    } else {
    ?>
    <div class="popup" id="popup2">
        <p>Reset Password</p>
        <form method="post">
            <input type="hidden" name="renewPwd" value="1"/>
            <p>New Password:</p>
            <input type="password" name="password" placeholder="Enter new password" required>
            <input type="submit" value="Reset Password">
        </form>
    </div>
    <?php }?>
</div>
<script>
    let popup = document.getElementById("popup");
    function openPopup(trans_id){
        popup.classList.add("open-popup");
    }
    function closePopup(){
        popup.classList.remove("open-popup");
    }
</script>
</body>
</html>
