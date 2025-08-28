<?php
require __DIR__.'database.php';
$trans_id=$_GET['trans_id'];
$query = "DELETE FROM `transaction` WHERE trans_id=$trans_id";
$result = mysqli_query($con,$query) or die ( mysqli_error($con));
header("Location: moneyDashboard.php");
exit();
?>