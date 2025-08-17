<?php 
session_start();
//include("auth.php");
require("database.php");

//Based on month query
$labels = [];
$values = [];

$month = isset($_GET['month']) ? intval($_GET['month']) : date("n"); // 1–12
$year  = isset($_GET['year']) ? intval($_GET['year']) : date("Y");
if ($month < 1) { $month = 12; $year--; }
if ($month > 12) { $month = 1; $year++; }
//Convert to word
$monthName = date("F", mktime(0,0,0,$month,1,$year));
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
        <link rel="stylesheet" href="hamburger.css">
        <link rel="stylesheet" href="header.css">
        <link rel="stylesheet" href="moneyD.css">

        <!--Chart.js-->
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        
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

    <div class="container-fluid">
        <!--Header-->
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
                <a href="?month=<?= $month-1 ?>&year=<?= $year ?>"><button class="material-symbols-outlined">arrow_back_ios</button></a>
                <div class="Month coiny-regular"><?php echo $monthName. " ". $year ?></div>
                <a href="?month=<?= $month+1 ?>&year=<?= $year ?>"><button class="material-symbols-outlined">arrow_forward_ios</button></a>
            </div>
            <div id="selectionButtons" class= "tabs-bar">
                <button class="tab selectionButton coiny-regular">Daily</button>
                <button class="tab selectionButton coiny-regular">Weekly</button>
                <button class="tab selectionButton active coiny-regular">Monthly</button>
                <button class="tab selectionButton coiny-regular">Yearly</button>
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
                            <canvas id="moneyBar"></canvas>
                        </div>

                        <div class="moneyPieChart">  
                            <canvas id="moneyDoughnut"></canvas>
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

    <?php
    //Query based on category, draw a doughnut chart and count expenses 
    $queryMon = "SELECT category, SUM(amount) AS total
                    FROM  transaction
                    WHERE MONTH(`date`) = $month
                    AND YEAR(`date`) = $year
                    GROUP BY category
                    ORDER BY total desc;
                ";

    $result = mysqli_query($con, $queryMon);
    if(!$result) {
        die(mysqli_error($con));
    }
    if(mysqli_num_rows($result)=== 0 ) {
        echo '<div> No data. </div>';
    } else {
        while($row = mysqli_fetch_assoc($result)) {
            $labels[] = $row['category'];
            $values[] = $row['total'];//Correct
        }
    }
    ?>
    
    <script>
    //Convert PHP to JavScript, https://www.w3schools.com/php/func_json_encode.asp
    let lbs = <?php echo json_encode($labels) ?>;
    let vals = <?php echo json_encode($values) ?>;

    //Convert to float data type
    vals = vals.map(v => parseFloat(v));
   
    const colorPalette = ['#D5DFE5','#7F9172','#567568','#B49594','#C9B1BD'];
    const bgColor = colorPalette.slice(0,lbs.length);

    //Limit the doughnut has at most 5 viariables, https://www.w3schools.com/js/js_array_reference.asp
    if(lbs.length > 5) {
        //Keep the first 4 variables
        const tempLBS = lbs.slice(0,4);
        const tempVALS = vals.slice(0,4);
        
        const genCat = 'Others';
        const sumTot = vals.reduce((accu, curr) => accu + curr, 0);
        const sumLeft = tempVALS.reduce((accu, curr) => accu + curr, 0);
        const leftVals = sumTot - sumLeft;

        tempLBS.push(genCat);
        tempVALS.push(leftVals);

        //Assign back
        lbs = tempLBS;
        vals = tempVALS;
    } 

    //draw donut with chart.js
    const dnt = document.getElementById('moneyDoughnut');
    const myDoughnutChart = new Chart(dnt, {
        type: 'doughnut',
        data:{
            labels: lbs,
            datasets: [{
            data: vals,
            backgroundColor: bgColor,
            borderWidth:0,
            hoverOffset:4
            }]
        },
        options: {
            plugins: {
                legend: {
                    labels: {
                        font: {
                            size: 16
                        },
                        color: '#000'
                    }
                }
            }
        }
    });

    //Script for "selection" button active
    document.getElementById('selectionButtons').addEventListener('click', (e)=>{
        const btn = e.target.closest('.selectionButton');
        if (!btn) return;
            document.querySelectorAll('#selectionButtons .selectionButton').forEach(b=>b.classList.remove('active'));
            btn.classList.add('active');
        });

    </script>
    </body>
</html>