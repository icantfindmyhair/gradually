<?php 
session_start();

error_reporting(E_ALL);
ini_set('display_errors', 1);

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

//Get user id#
$userId = $_SESSION['user_id'];

if (isset($_GET['trans_id']) && ctype_digit($_GET['trans_id'])): ?>
<script>
  document.addEventListener('DOMContentLoaded', function () {
    document.getElementById('popup').classList.add('open-popup');
  });
</script>
<?php endif; ?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Money Tracker</title>

        <!--Bootstrap-->
        <link rel="stylesheet" href="hamburger.css">
        <link rel="stylesheet" href="header.css">
        <link rel="stylesheet" href="moneyD.css">

        <!--Chart.js-->
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        
        <!--Google Fonts-->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200"/>
        <link href="https://fonts.googleapis.com/css2?family=Agbalumo&display=swap" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css2?family=Agbalumo&family=Jua&family=Zen+Maru+Gothic&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
        
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
                <button class="tab selectionButton coiny-regular" data-value="daily">Daily</button>
                <button class="tab selectionButton coiny-regular" data-value="weekly">Weekly</button>
                <button class="tab selectionButton active coiny-regular" data-value="monthly">Monthly</button>
                <button class="tab selectionButton coiny-regular" data-value="yearly">Yearly</button>
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
                        <?php 
                        //1. moneyBar- Month
                        $queryTotInc = "SELECT SUM(amount) AS total
                                        FROM transaction
                                        WHERE MONTH(`date`) = $month
                                        AND YEAR(`date`) = $year
                                        AND user_id = $userId
                                        AND type = 'income';
                                        ";

                        $result1 = mysqli_query($con, $queryTotInc);
                        if(!$result1) {
                            die(mysqli_error($con));
                        }
                        if(mysqli_num_rows($result1) === 0 ) {
                            echo '<script> alert("Remember to create transaction for your expenses!"); </script>';
                        } else {
                            while($row = mysqli_fetch_assoc($result1)) {
                                $totalIncome = (float)($row['total'] ?? 0);
                            }
                        }

                        $queryTotExp = "SELECT SUM(amount) AS total
                                        FROM transaction
                                        WHERE MONTH(`date`) = $month
                                        AND YEAR(`date`) = $year
                                        AND user_id = $userId
                                        AND type = 'expense';
                                        ";

                        $result2 = mysqli_query($con, $queryTotExp);
                        if(!$result2) {
                            die(mysqli_error($con));
                        }
                        else {
                            while($row = mysqli_fetch_assoc($result2)) {
                                $totalExpenses = (float)($row['total'] ?? 0);
                            }
                        }
                        //Start of money bar chart
                        //Width for the overall bar: 359px
                        $BAR_W = 359;
                        $den = max(1,$totalIncome + $totalExpenses); //To avoid divided by 0
                        $widthForIncBox = $BAR_W * ($totalIncome  / $den);
                        $widthForExpBox = $BAR_W * ($totalExpenses / $den);
                        ?>
                        <div class="moneyStatusBar">
                            <span class="box incomebox" style="width : <?php echo $widthForIncBox?>px;"></span>
                            <span class="box expensebox" style="width: <?php echo $widthForExpBox?>px;"></span> 
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
                            <div class="Income coiny-regular">
                                <div class="labelIncome">Income</div>
                                <div class="amountIncome" style="color:#A7F6D1"><?php echo number_format($totalIncome,2) ?></div>
                            </div>
                            <div class="Expenses coiny-regular">
                                <div class="labelExpenses">Expenses</div>
                                <div class="amountExpenses" style="color:#F6A7A7;"><?php echo number_format($totalExpenses, 2) ?></div>
                            </div>
                            <!-- Details about exp-->
                            <div class="detailExp coiny-regular">
                                <?php
                                //2. Query based on category, draw a doughnut chart and count expenses 
                                $queryExpMon = "SELECT category, SUM(amount) AS total
                                                FROM  transaction
                                                WHERE MONTH(`date`) = $month
                                                AND YEAR(`date`) = $year
                                                AND user_id = $userId
                                                AND type = 'expense'
                                                GROUP BY category
                                                ORDER BY total desc;
                                            ";

                                $result2 = mysqli_query($con, $queryExpMon);
                                if(!$result2) {
                                    die(mysqli_error($con));
                                }
                                else {
                                    while($row = mysqli_fetch_assoc($result2)) {
                                        $labels[] = $row['category'];
                                        $values[] = (float)$row['total'];//Correct
                                ?>
                                <div class="wrapthem">
                                    <div class="detailCat"><?php echo $row['category']?></div>
                                    <div class="detailTot"><?php echo number_format((float)$row['total'],2)?></div>
                                </div>     
                                <?php }} ?>       
                            </div>
                            <div class="divider"></div>
                            <div class="Balance coiny-regular">
                                <div class="labelBalance">Balance</div>
                                <div class="amountBalance"><?php $balance = $totalIncome - $totalExpenses; echo number_format($balance,2) ?></div>
                            </div>
                            <div class="divider"></div>

                        </div>
                        <div class="calcType coiny-regular">
                            <div class="labelAccount">Account Type</div>
                            <div class="calcTypedetail coiny-regular">
                                <?php
                                //3. Query based on payment type
                                $queryExpMon = "SELECT account_type, SUM(amount) AS total
                                                FROM  transaction
                                                WHERE MONTH(`date`) = $month
                                                AND YEAR(`date`) = $year
                                                AND user_id = $userId
                                                AND type = 'expense'
                                                GROUP BY account_type
                                                ORDER BY total desc;
                                            ";

                                $result3 = mysqli_query($con, $queryExpMon);
                                if(!$result3) {
                                    die(mysqli_error($con));
                                }
                                if(mysqli_num_rows($result3) === 0 ) {
                                    echo '<script> alert("Remember to create transaction for your expenses!"); </script>';
                                } else {
                                    while($row = mysqli_fetch_assoc($result3)) {
                                ?>
                                <div class="wrapthemAccount">
                                    <div class="detailType"><?php echo $row['account_type']?></div>
                                    <div class="detailAccTot"><?php echo number_format((float)$row['total'],2)?></div>
                                </div>     
                                <?php }} ?>       
                            </div>
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
                        <label id="datelabel" class="coiny-regular">Date</label>
                        <label id="rm" class="coiny-regular">RM</label>
                    </div>
                    <div class="transhistory coiny-regular">
                        <?php
                        //4. Query all transaction made
                        $queryEachTran = "SELECT trans_id, amount, type, category, account_type, type, DAY(`date`) AS day, description
                                        FROM  transaction
                                        WHERE MONTH(`date`) = $month
                                        AND YEAR(`date`) = $year
                                        AND user_id = $userId
                                        ORDER BY day desc;
                                    ";

                        $result4 = mysqli_query($con, $queryEachTran);
                        if(!$result4) {
                            die(mysqli_error($con));
                        }
                        else {
                            while($row = mysqli_fetch_assoc($result4)) {
                        ?>
                        <div class="wraphistory coiny-regular" onclick="openPopup(<?php echo $row['trans_id']; ?>)" oncontextmenu="if(confirm('Delete this transaction?')) {window.location.href='moneyTransDelete.php?trans_id=<?php echo $row['trans_id']; ?>'; } return false;">
                            <div class="date"><?php echo $row['day'] ?></div>
                            <div class="wrapName">
                                <div class="name"><?php echo $row['description'] ?></div>
                                <div class="cat" style="font-size: 20px;"><?php echo $row['category'] ?></div>
                            </div>   
                            <div class="amount" style="color: <?php echo ($row['type'] === 'income') ? '#A7F6D1' : '#F6A7A7'; ?>"><?php echo number_format((float)$row['amount']) ?></div>
                        </div>
                        <?php }} ?>
                        <div class="popup" id="popup">
                            <?php
                                $trans_id=$_GET['trans_id'];
                                $userId = $_SESSION['user_id'];
                                $query = "SELECT * FROM transaction where trans_id='".$trans_id."'";
                                $result = mysqli_query($con, $query) or die ( mysqli_error($con));
                                $row = mysqli_fetch_assoc($result);
                                $status = "";

                                if(isset($_POST['new']) && $_POST['new']==1)
                                {
                                    $date = $_REQUEST['date'];
                                    $date = date("Y-m-d", strtotime($date));
                                    $category = $_REQUEST['category'];
                                    $amount = $_REQUEST['amount'];
                                    $account_type = $_REQUEST['account_type'];
                                    $desc = $_REQUEST['desc'] ?? '';
                                    $type = $row['type'];

                                    $update="UPDATE transaction set date='".$date."',
                                            category='".$category."', amount='".$amount."', account_type='".$account_type."',
                                            description='".$desc."', type='".$type."' where trans_id='".$trans_id."' and user_id='".$userId."'";
                                    mysqli_query($con, $update) or die(mysqli_error($con));

                                    $status = "Transaction Record Updated Successfully.";
                                    echo "<script>
                                            alert('$status');
                                            window.location.href = 'moneyDashboard.php';
                                        </script>";
                                } else {
                                ?>
                                <table>
                                    <form action="" method="post">
                                    <input name="user_id" type="hidden" value="<?php echo $userId;?>" />
                                    <div class="tableWrap">
                                        <input type="hidden" name="new" value="1"/><p>Edit for transaction <span class="material-symbols-outlined">edit</span></p>
                                        <button type="button" class="close" onclick="window.location.href='moneyDashboard.php'"><span class="material-symbols-outlined">close</span></button>
                                    </div>
                                    <tr>
                                        <td><label class="coiny-regular" for="date">Date</label></td>
                                        <td><input id="date" name="date" type="date" placeholder="<?php echo $row['date'];?>" value="<?php echo $row['date'];?>" required></td>
                                    </tr>
                                    <tr>
                                        <td><label class="coiny-regular" for="category">Category</label></td>
                                        <td><input id="category" name="category" type="text" placeholder="<?php echo $row['category'];?>" value="<?php echo $row['category'];?>" required></td>
                                    </tr>                    
                                    <tr>
                                        <td><label class="coiny-regular" for="amount">Amount</label></td>
                                        <td><input id="amount" name="amount" type="number" step="0.01" min="0"  placeholder="<?php echo $row['amount'];?>" value="<?php echo $row['amount'];?>" required></td>
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
                                        <td><textarea id="desc" class="form-control" id="desc" name="desc" rows="4"><?php echo $row['description'];?></textarea></td>
                                    </tr>
                                    <tr>
                                        <td colspan="2" style="text-align:center;"><input type="submit" class="coiny-regular" value="Update" onclick="closePopup()"></td>
                                    </tr>                                                            
                                    </form>
                                </table>
                            <?php } ?>
                        </div>   
                    </div>
                </section>            
            </div>
            <!--End of right block-->

        </div>
        <!--End of Dashboard-->
    </div>  
    <script>
    //Convert PHP to JavScript, https://www.w3schools.com/php/func_json_encode.asp
    let lbs = <?php echo json_encode($labels) ?>;
    let vals = <?php echo json_encode($values) ?>;

    //Convert to float data type
    //vals = vals.map(v => parseFloat(v));

    //Start of money pie chart
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
    //End of money pie chart

    //Script for "selection" button active
    document.getElementById('selectionButtons').addEventListener('click', (e)=>{
        const btn = e.target.closest('.selectionButton');
        if (!btn) return;
            document.querySelectorAll('#selectionButtons .selectionButton').forEach(b=>b.classList.remove('active'));
            btn.classList.add('active');
        });

    let popup = document.getElementById("popup");
    function openPopup(trans_id){
        window.location.href = 'moneyDashboard.php?trans_id=' + trans_id;
        popup.classList.add("open-popup");
    }
    function closePopup(){
        popup.classList.remove("open-popup");
    }
    </script>
    </body>
</html>