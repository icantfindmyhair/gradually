<?php 
error_reporting(E_ALL);
ini_set('display_errors', 1);

include("auth.php");
require("database.php");

//Based on month query
$labelsExp = [];
$labelsInc = [];
$valuesInc = [];
$valuesExp = [];

$view = $_GET['view'] ?? 'yearly'; // default year

$date = isset($_GET['date']) ? intval($_GET['date']) : date("d");
$month = isset($_GET['month']) ? intval($_GET['month']) : date("n"); // 1–12
$year  = isset($_GET['year']) ? intval($_GET['year']) : date("Y");

//Convert to word
$monthNames = [
    1=>'January',2=>'February',3=>'March',4=>'April',5=>'May',6=>'June',
    7=>'July',8=>'August',9=>'September',10=>'October',11=>'November',12=>'December'
];


//Handle view type
switch($view){

    case 'monthly':
        $startDate = $year."-".$month."-".date("01"); //start of the month
        $endDate = $year."-".$month."-".date("t"); //end of the month
        break;

    case 'yearly':
        $startDate = $year."-".date('01')."-".date('01'); //start of the year
        $endDate = $year."-".date('12')."-".date('y'); //start of the end
        break;

    default: //Follow monthly 
        $startDate = $year."-".$month."-".date("d");
        $endDate = $year."-".$month."-".date("t");
}


//Month that has 30 or 31
$monthin31 = [1,3,5,7,8,10,12];

$monthin30 = [4,6,9,11];

//Check leap year
function isLeapYear($year) {
    return ($year % 4 == 0);
}

//Condition for datetime
if ($month < 1) { $month = 12; $year--; }
if ($month > 12) { $month = 1; $year++; }
if ($date < 1) {
    $month--;
    if($month < 1) { $month = 12; $year--; }
    if(in_array($month, $monthin31)) {
        $date = 31;
    } elseif(in_array($month, $monthin30)) {
        $date = 30; 
    } elseif ($month == 2){
        $date = isLeapYear($year) ? 29 : 28;
    }
}

if (in_array($month, $monthin31) && $date > 31) {
    $date = 1;
    $month++;

} elseif (in_array($month, $monthin30) && $date > 30) {
    $date = 1;
    $month++;

} else if ($month == 2 ) {

    if($date > 29 && isLeapYear($year)) {//leap year
        $date = 1;
        $month++;
    } elseif(!isLeapYear($year) && $date > 28) {
        $date = 1;
        $month++;
    }
}

$monthName = $monthNames[$month];

//Get user id#
$userId = $_SESSION['user_id'];

?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Money Tracker</title>

        <!--Bootstrap-->
        <link rel="stylesheet" href="hamburger.css">
        <link rel="stylesheet" href="header.css">
        <link rel="stylesheet" href="moneyL.css">

        <!--Chart.js-->
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        
        <!--Google Fonts-->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200"/>
        <link href="https://fonts.googleapis.com/css2?family=Agbalumo&display=swap" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css2?family=Agbalumo&family=Jua&family=Zen+Maru+Gothic&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
        <link href="https://fonts.googleapis.com/css2?family=Agbalumo&display=swap" rel="stylesheet">
        
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
                <?php
                //Monthly summary
                if($view == 'monthly') :?>

                    <a href="?view=monthly&month=<?= $month-1 ?>&year=<?= $year ?>"><button class="material-symbols-outlined">arrow_back_ios</button></a>
                    <div class="Month coiny-regular">
                        <?php 
                            echo $monthName. " ". $year;
                        ?>
                    </div>
                    <a href="?view=monthly&month=<?= $month+1 ?>&year=<?= $year ?>"><button class="material-symbols-outlined">arrow_forward_ios</button></a>
                
                <?php else :?>
                    <a href="?view=yearly&year=<?= $year-1 ?>"><button class="material-symbols-outlined">arrow_back_ios</button></a>
                    <div class="Month coiny-regular">
                        <?php 
                            echo $year; 
                        ?>
                    </div>
                    <a href="?view=yearly&year=<?= $year+1 ?>"><button class="material-symbols-outlined">arrow_forward_ios</button></a>
                <?php endif;?>

            </div>
            <div id="selectionButtons" class= "tabs-bar">
                <?php
                $views = ['monthly' => 'Monthly', 'yearly' => 'Yearly'];
                foreach ($views as $key => $label) {
                    $activeClass = ($view == $key) ? 'active' : '';
                    echo '<button class="tab selectionButton ' . $activeClass . ' coiny-regular">
                            <a href="moneyLineChart.php?view=' . $key . '">' . $label . '</a>
                        </button>';
                }
                ?>
            </div>
        </div>
        <!-- End of Top selection-->



        <!-- Start of content-->
         <div class="moneyLineChart">  
            <canvas id="moneyLine"></canvas>
        </div>

        <!--Switch View-->
        <div class="linkLineChart">
            <a href="moneyDashboard.php"><button class="LineChartButton coiny-regular">Switch View</button></a>
        </div>
                                
        <?php
        switch($view){

    case 'monthly':
        //1. Query based on monthly type, draw a line chart
        $queryExpMonth = "SELECT DAY(date) AS Day, SUM(amount) AS total
                        FROM  transaction
                        WHERE user_id = $userId
                        AND type = 'expense'
                        AND date BETWEEN '$startDate' AND '$endDate'
                        GROUP BY Day 
                        ORDER BY date;
                    ";

        $result1 = mysqli_query($con, $queryExpMonth);
        if(!$result1) {
            die(mysqli_error($con));
        }
        else {
            while($row = mysqli_fetch_assoc($result1)) {
                $labelsExp[] = $row['Day'];
                $valuesExp[] = (float)$row['total'];//Correct
            }}

        $incomeExpMonth = "SELECT DAY(date) AS Day, MONTH(date) AS Month, SUM(amount) AS total
                        FROM  transaction
                        WHERE user_id = $userId
                        AND type = 'income'
                        AND date BETWEEN '$startDate' AND '$endDate'
                        GROUP BY Day, Month
                        ORDER BY date desc;
                    ";

        $result2 = mysqli_query($con, $incomeExpMonth);
        if(!$result2) {
            die(mysqli_error($con));
        }
        else {
            while($row = mysqli_fetch_assoc($result2)) {
                $labelsInc[] = $row['Day'];
                $valuesInc[] = (float)$row['total'];//Correct
            }}
        break;

    case 'yearly':
        //1. Query based on yearly type, draw a line chart
        $queryExpYear = "SELECT MONTH(date) AS Month, SUM(amount) AS total
                        FROM  transaction
                        WHERE user_id = $userId
                        AND type = 'expense'
                        AND date BETWEEN '$startDate' AND '$endDate'
                        GROUP BY Month
                        ORDER BY date;
                    ";

        $result1 = mysqli_query($con, $queryExpYear);
        if(!$result1) {
            die(mysqli_error($con));
        }
        else {
            while($row = mysqli_fetch_assoc($result1)) {
                $labelsExp[] = $row['Month'];
                $valuesExp[] = (float)$row['total'];//Correct
            }}

        $incomeExpYear = "SELECT MONTH(date) AS Month, SUM(amount) AS total
                        FROM  transaction
                        WHERE user_id = $userId
                        AND type = 'income'
                        AND date BETWEEN '$startDate' AND '$endDate'
                        GROUP BY Month
                        ORDER BY date;
                    ";

        $result2 = mysqli_query($con, $incomeExpYear);
        if(!$result2) {
            die(mysqli_error($con));
        }
        else {
            while($row = mysqli_fetch_assoc($result2)) {
                $labelsInc[] = $row['Month'];
                $valuesInc[] = (float)$row['total'];//Correct
            }}
        break;

    default: //Follow yearly 
        //1. Query based on yearly type, draw a line chart
        $queryExpYear = "SELECT MONTH(date) AS Month, SUM(amount) AS total
                        FROM  transaction
                        WHERE user_id = $userId
                        AND type = 'expense'
                        AND date BETWEEN '$startDate' AND '$endDate'
                        GROUP BY Month
                        ORDER BY Month;
                    ";

        $result1 = mysqli_query($con, $queryExpYear);
        if(!$result1) {
            die(mysqli_error($con));
        }
        else {
            while($row = mysqli_fetch_assoc($result1)) {
                $labelsExp[] = $row['Month'];
                $valuesExp[] = (float)$row['total'];//Correct
            }}

        $incomeExpYear = "SELECT MONTH(date) AS Month, SUM(amount) AS total
                        FROM  transaction
                        WHERE user_id = $userId
                        AND type = 'income'
                        AND date BETWEEN '$startDate' AND '$endDate'
                        GROUP BY Month
                        ORDER BY Month;
                    ";

        $result2 = mysqli_query($con, $incomeExpYear);
        if(!$result2) {
            die(mysqli_error($con));
        }
        else {
            while($row = mysqli_fetch_assoc($result2)) {
                $labelsInc[] = $row['Month'];
                $valuesInc[] = (float)$row['total'];//Correct
            }}
}        
        ?>
    </div>

    <script>
        //Convert PHP to JavScript, https://www.w3schools.com/php/func_json_encode.asp
        let lbsExp = <?php echo json_encode($labelsExp) ?>;
        let valExp = <?php echo json_encode($valuesExp) ?>;
        let lbsInc = <?php echo json_encode($labelsInc) ?>;
        let valInc = <?php echo json_encode($valuesInc) ?>;

        // Combine all unique days
        let combineDay = [...lbsExp, ...lbsInc];
        const setUniqueDay = new Set(combineDay);
        let uniqueDay = [...setUniqueDay];

        // Ensure consistency
        let filledValExp = uniqueDay.map(day => {
            let index = lbsExp.indexOf(day);
            return index !== -1 ? valExp[index] : 0;
        });

        let filledValInc = uniqueDay.map(day => {
            let index = lbsInc.indexOf(day);
            return index !== -1 ? valInc[index] : 0;
        })

        console.log(uniqueDay);
        console.log(filledValExp);
        console.log(filledValInc);
        //Month name
        const MONTHS = [
           'January', 'February', 'March', 'April', 'May', 'June','July', 'August', 'September', 'October', 'November', 'December' 
        ];
        //Convert month from number to text
        <?php if($view == 'yearly'){ ?>
            uniqueDay = uniqueDay.map(num => MONTHS[num - 1]);
        <?php }?>

        //Define parameters for line chart
        const data = {
            labels: uniqueDay,
            datasets: [
                {
                    label: 'Expenses',
                    data: filledValExp,
                    borderColor: 'rgb(246, 167, 167)',
                    backgroundColor: 'rgb(246, 167, 167)',
                },
                {
                    label: 'Income',
                    data: filledValInc,
                    borderColor: 'rgb(167, 246, 209)',
                    backgroundColor: 'rgb(167, 246, 209)',
                }
            ]
        };
        const options = {
            responsive: true,
            plugins: {
            legend: {
                position: 'top',
            },
            title: {
                display: false,
            }
            }
        };

        //draw line with chart.js
        const line = document.getElementById('moneyLine');
        const moneyLineChart = new Chart(line, {
            type: 'line',
            data: data,
            options: options
        });
        //End of money line chart

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