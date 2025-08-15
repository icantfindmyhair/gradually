<?php session_start()?>

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
        <link href="https://fonts.googleapis.com/css2?family=Moirai+One&family=Pacifico&display=swap" rel="stylesheet">
        
        <!--Custom CSS-->
        <link rel="stylesheet" href="hamburger.css">    
        <link rel="stylesheet" href="header.css">
        <link rel="stylesheet" href="money.css">

        <!--Sidebar-->
        <?php include 'hamburger.php'; ?>

        <!--Script for side bar-->
        <script src="hamburger.js"></script>
    </head>
    
    <body>
        <div class="top-bar">
            <button class="hamburger">&#9776;</button>
            <a href="homepage.php" class="title">Gradually</a>
            <a href="logout.php" class="logout-btn">Log out</a>
        </div>

        <!--Start of Content-->
        <!--Title-->
        <header>
            <div class="subTitle pacifico-regular mb-3">New Transaction</div>
        </header>

        <section class="main container-fluid ">
            <div class="form-card d-flex justify-content-center align-items-center">
                <table>
                    <form action="moneyDashboard.php" method="post">
                    <tr>
                        <td><label for="date">Date</label></td>
                        <td><input type="text"></td>
                    </tr>
                    <tr>
                        <td><label for="amount">Amount</label></td>
                        <td><input type="text"></td>
                    </tr> 
                    <tr>
                        <td><label for="category">Category</label></td>
                        <td><input type="text"></td>
                    </tr>
                    <tr>
                        <td><label for="account_type">Account</label></td>
                        <td><input type="text"></td>
                    </tr>
                    <tr>
                        <td><label for="Desc">Description</label></td>
                        <td><input type="text"></td>
                    </tr>
                    <tr>
                        <td></td>
                        <td colspan="2" style="text-align:center"><input type="submit" value="Submit"></td>
                    </tr>                                                            
                    </form>
                </table>            
            </div>
        </section>
    








    </body>
</html>