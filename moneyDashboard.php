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
        <link rel="stylesheet" href="hamburger.css">
        <link rel="stylesheet" href="header.css">
        <link rel="stylesheet" href="moneyD.css">
        <!--Google Fonts-->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200"/>
        <!--Sidebar-->    
        <?php include 'hamburger.php'; ?>
        <script src="hamburger.js"></script>
        
        <!--Font-->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Coiny&display=swap" rel="stylesheet">
    </head>
    <body>

    <!--Header-->
    <div class="container-fluid">
        <div class="top-bar">
            <button class="hamburger">&#9776;</button>
            <a href="homepage.php" class="title">Gradually</a>
            <a href="logout.php" class="logout-btn">Log out</a>
        </div>
        <!--End of header-->

        <!--Start of content-->
        <!--Top selection-->
        <div class="top-selection">
            <div class="month-bar">
                <button class="material-symbols-outlined">arrow_back_ios</button>
                <div class="Month coiny-regular">August</div>
                <button class="material-symbols-outlined">arrow_forward_ios</button>
            </div>
            <div class= "tabs-bar">
                <button class="tab coiny-regular">Daily</button>
                <button class="tab coiny-regular">Weekly</button>
                <button class="tab active coiny-regular">Monthly</button>
                <button class="tab coiny-regular">Yearly</button>
            </div>
        </div>
        <!-- End of Top selection-->

        <!--Start of Dashboard-->
        <div class="dashboard">

            <!--Left Block-->
            <div class="summary">
                <div class="labelSum coiny-regular">Summary</div>
                <section class="summary-content">
                    <div class="summaryVisual">

                        <div class="moneyStatusBar">
                        </div>

                        <div class="moneyPieChart">  
                        </div>                     

                        <div class="newTransactionButton">
                            <a href="moneyTrans.php"><button class="transactionButton coiny-regular">+ New Transaction</button></a>
                        </div>

                    </div>
                    <div class="calcContent">
                        <div class="rmLabel">
                            <label id="rm" class="coiny-regular">RM</label>
                        </div>
                        <div class="calcIncomeExpenses">
                            
                        </div>
                        <div class="calcType">
                            
                        </div>
                    </div>
                </section>            
            </div>
            <!--End of left block-->

            <!--Right Block-->
            <div class="lastTransaction">
                <div class="labelLastTransaction coiny-regular">Last Transaction</div>
                <section class="trans-content">
                    <div class="commonLabel">
                        <label id="date" class="coiny-regular">Date</label>
                        <label id="rm" class="coiny-regular">RM</label>
                    </div>
                    <div class="history">
                    </div> 
                </section>            
            </div>
            <!--End of right block-->

        </div>
        <!--End of Dashboard-->
    </div>
    </body>
</html>