<?php 
//include("auth.php");
require("database.php");
session_start();

error_reporting(E_ALL);
ini_set('display_errors', 1);

$status = "";
if(isset($_POST['new_trans']) && $_POST['new_trans'] == 1){
    $date = $_REQUEST['date'];
    $date = date("Y-m-d", strtotime($date));
    $category = $_REQUEST['category'];
    $amount = $_REQUEST['amount'];
    $account_type = $_REQUEST['account_type'];
    $desc = $_REQUEST['desc'] ?? '';
    $type = $_REQUEST['type_handler'];
    $userId = $_SESSION['user_id'] ?? '1';

    $ins_query = "INSERT into transaction(`amount`,`type`,`category`,`account_type`,`date`,`description`,`user_id`)
                VALUES('$amount','$type','$category','$account_type','$date','$desc','$userId')";
    mysqli_query($con,$ins_query)
    or die(mysqli_error($con));

    //Display successfully inserted message and redirect user to dashboard page.
    $status = "Transaction inserted successfully!";
    echo "<script>
            alert('$status');
            window.location.href = 'moneyDashboard.php';
          </script>";    
    }
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
                        </table>            
                    </div>
                    <!--End of input area-->
                </div>
        </section>

        <script>
            //Script for date autofill function
            document.addEventListener("DOMContentLoaded", function() {
                const today = new Date().toISOString().split('T')[0]; 
                document.getElementById("date").value = today;
            });

            //Script for "type" button active
            document.getElementById('type-card').addEventListener('click', (e)=>{
            const btn = e.target.closest('.typeButton');
            if (!btn) return;
            document.querySelectorAll('#type-card .typeButton').forEach(b=>b.classList.remove('active'));
            btn.classList.add('active');
            document.getElementById('type_handler').value = btn.dataset.value; // "expense" or "income"
            });
            
            function confirmMsg() {
                alert($status);
            }
        </script>
    </body>
</html>