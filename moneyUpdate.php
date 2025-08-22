<?php

//include("auth.php");
require('database.php');

$id=$_REQUEST['id'];
$query = "SELECT * FROM products where id='".$id."'";
$result = mysqli_query($con, $query) or die ( mysqli_error($con));
$row = mysqli_fetch_assoc($result);
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Money Tracker</title>

        <!--Bootstrap-->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
        
        <!--Font-->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Coiny&display=swap" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css2?family=Agbalumo&display=swap" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css2?family=Agbalumo&family=Jua&family=Zen+Maru+Gothic&display=swap" rel="stylesheet">

        <!--Custom CSS-->
        <link rel="stylesheet" href="hamburger.css">    
        <link rel="stylesheet" href="header.css">
        <link rel="stylesheet" href="moneyT.css">

        <!--Sidebar-->
        <?php include 'hamburger.php'; ?>

        <!--Script for side bar-->
        <script src="hamburger.js"></script>
    </head>
    <body>
        <!--Header-->
        <div class="top-bar">
            <button class="hamburger" style="color: #ffffff;">&#9776;</button>
            <a href="homepage.php" style="color: #ffffff;" class="title">Gradually</a>
            <a href="logout.php" class="logout-btn">Log out</a>
        </div>
        <!--End of header-->

        <!--Start of Content-->
        <!--Title-->
        <header>
            <div class="subTitle coiny-regular mb-3">New Transaction</div>
        </header>

        <section class="main container">
                <div class="form-card">
                    <!--Type selection button-->
                    <div class= "type-card" id="type-card">
                        <button id="typeButton" class="coiny-regular typeButton active" data-value="expense">Expense(-)</button>
                        <button id="typeButton" class="coiny-regular typeButton" data-value="income">Income(+)</button> <!-- can do error handling here-->            
                    </div>
                    <!--End of type selection button-->
                    <?php
                        $status = "";
                        if(isset($_POST['new']) && $_POST['new']==1)
                        {
                            $trans_id=$_REQUEST['trans_id'];
                            $category =$_REQUEST['category'];
                            $amount = str_replace('RM ', '', $_REQUEST['amount']);
                            $account_type =$_REQUEST['account_type'];
                            $date = date("Y-m-d H:i:s");
                            $type = $_REQUEST['type'];
                            $update="UPDATE transaction set category='".$category."',
                            amount='".$amount."', account_type='".$account_type."', date='".$date."',
                            type='".$type."' where trans_id='".$trans_id."'";
                            mysqli_query($con, $update) or die(mysqli_error($con));
                            $status = "Transaction Record Updated Successfully. </br></br>
                            <a href='moneyDashboard.php'>View Updated Record</a>";
                            echo '<p style="color:#008000;">'.$status.'</p>';
                        } else {
                    ?>
                    <!--Input area-->
                    <div class="d-flex justify-content-center align-items-center">
                        <table>
                            <form action="" method="post">
                            <input type="hidden" name="type_handler" id="type_handler" value="expense"/>
                            <input type="hidden" name="new_trans" value="1"/>
                            <tr>
                                <td><label class="coiny-regular" for="date">Date</label></td>
                                <td><input id="date" name="date" type="date" required></td>
                            </tr>
                            <tr>
                                <td><label class="coiny-regular" for="category">Category</label></td>
                                <td><input id="category" name="category" type="text" required></td>
                            </tr>                    
                            <tr>
                                <td><label class="coiny-regular" for="amount">Amount</label></td>
                                <td><input id="amount" name="amount" type="number" step="0.01" min="0" placeholder="Enter Price (RM)" required></td>
                            </tr> 
                            <tr>
                                <td><label class="coiny-regular" for="account_type">Account</label></td>
                                <td><select name="account_type" name="account_type" id="account_type">
                                    <option value="Cash">Cash</option>
                                    <option value="E-wallet">E-wallet</option>
                                    <option value="Card">Card</option>
                                    <option value="BankAccount">Back Account</option>
                                </select></td>
                            </tr>
                            <tr>
                                <td style="vertical-align: top; padding-top: 6px;"><label class="coiny-regular" for="Desc">Description</label></td>
                                <td><textarea id="desc" class="form-control" id="desc" name="desc" rows="4"></textarea></td>
                            </tr>
                            <tr>
                                <td></td>
                                <td><input type="submit" class="coiny-regular" value="Submit" onclick="confirmMsg()"></td>
                            </tr>                                                            
                            </form>
                            <?php } ?>
                        </table>            
                    </div>
                    <!--End of input area-->
                </div>
        </section>
    </body>
    
    <body>
        <?php
        $status = "";
        if(isset($_POST['new']) && $_POST['new']==1)
        {
            $id=$_REQUEST['id'];
            $product_name =$_REQUEST['product_name'];
            $price = str_replace('RM ', '', $_REQUEST['price']);
            $quantity =$_REQUEST['quantity'];
            $date_record = date("Y-m-d H:i:s");
            $submittedby = $_SESSION["username"];
            $update="UPDATE products set date_record='".$date_record."',
            product_name='".$product_name."', price='".$price."', quantity='".$quantity."',
            submittedby='".$submittedby."' where id='".$id."'";
            mysqli_query($con, $update) or die(mysqli_error($con));
            $status = "Product Record Updated Successfully. </br></br>
            <a href='view.php'>View Updated Record</a>";
            echo '<p style="color:#008000;">'.$status.'</p>';
        } else {
        ?>

        <form name="form" method="post" action="">
        <input type="hidden" name="new" value="1" />
        <input name="id" type="hidden" value="<?php echo $row['id'];?>" />
        <p><input type="text" name="product_name" placeholder="Update Product Name" required value="<?php echo $row['product_name'];?>" /></p>
        <p><input type="text" name="price" placeholder="Update Product Price" required value="RM <?php echo $row['price'];?>" /></p>
        <p><input type="text" name="quantity" placeholder="Update Product Quantity" required value="<?php echo $row['quantity'];?>" /></p>
        <p><input name="submit" type="submit" value="Update" /></p>
        </form>

        <?php } ?>
    </body>