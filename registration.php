<?php 
require("database.php");
session_start();

error_reporting(E_ALL);
ini_set('display_errors', 1);
$status = "";
if (isset($_REQUEST['username'])) {
	$username = stripslashes($_REQUEST['username']);
	$username = mysqli_real_escape_string($con, $username);
	$email = stripslashes($_REQUEST['email']);
	$email = mysqli_real_escape_string($con, $email);
	$password = stripslashes($_REQUEST['password']);
	$password = mysqli_real_escape_string($con, $password);
	$create_date_time = date('Y-m-d H:i:s');
	$query = "INSERT into `users` (username, password, email, create_date_time) VALUES ('$username', '".md5($password)."', '$email', '$create_date_time')";
	mysqli_query($con, $query) or die(mysqli_error($con));
	$status ="You are registered Successfully!";
	echo "<script>
		alert('$status');
		window.location.href = 'login.php';
		</script>";
}
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Registration</title>

        <!--Bootstrap-->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
        
        <!--Font-->
		<link href="https://fonts.googleapis.com/css2?family=Agbalumo&display=swap" rel="stylesheet">
    	<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
        
		<!--Custom CSS-->
        <link rel="stylesheet" href="registration.css">    
    </head>
	<body>
		<div class="container reg">
			<section class="image"><a href="login.php" class="back material-symbols-outlined">arrow_back_ios</a></section>
			<section class="main">
				<div class="form-card">
					<div class="form justify-content-center align-items-center">
						<h1 id="webName">Gradually</h1>
						<h1 id="signUpLabel">Create new account</h1>
						<table>
							<form name="registration" action="" method="post">
							<tr>
								<td><label for="username">Username</label></td>
							</tr>
							<tr>	
								<td><input type="text" name="username" placeholder="Enter Your Username" required /></td>
							</tr>
							<tr>
								<td><label for="email">Your Email</label></td>
							</tr>
							<tr>
								<td><input type="email" name="email" placeholder="Enter Your Email" required /></td>
							</tr>                    
							<tr>
								<td><label for="password">Password</label></td>
							</tr>
							<tr>
								<td><input type="password" name="password" placeholder="Enter Your Password" required /></td>
							</tr> 
							<tr>
								<td><input type="submit" class="coiny-regular" value="Submit"></td>
							</tr>                                                            
							</form>
						</table>
					</div>
					<!--End of input area-->
				</div>
			</section>
		</div>
	</body>
</html>