<?php

include_once '../commons/session.php';
include_once '../model/finance_model.php';

//get user information from session
$userSession=$_SESSION["user"];
$userFunctions=$_SESSION['user_functions'];

$financeObj = new Finance();

// Get the count of pending service payments
$pendingServicePaymentCount = $financeObj->getPendingServicePaymentCount();

// Get the count of pending supplier payments
$pendingSupplierPaymentCount = $financeObj->getPendingSupplierPaymentCount();

// Get the expenses breakdown for the chart
$expensesBreakdownResult = $financeObj->getExpenseBreakdown();

$expenseCategories = array();
$expenseAmounts = array();

if($expensesBreakdownResult->num_rows > 0){
    while($row = $expensesBreakdownResult->fetch_assoc()){
        $expenseCategories[] = $row['txn_description'];
        $expenseAmounts[] = $row['expenses'];
    }
}

$expensesBreakdownData = json_encode(['categories' => $expenseCategories, 'expenses' => $expenseAmounts]);

//Get Revenue By Bus Category
$revenueByCategoryResult = $financeObj->getRevenueByBusCategory();

$busCategories = array();
$revenueAmounts = array();

if($revenueByCategoryResult->num_rows > 0) {
    while ($row = $revenueByCategoryResult->fetch_assoc()) {
        array_push($busCategories, $row['category_name']);
        array_push($revenueAmounts, (int)$row['total_revenue']);
    }
}

$revenueByCategoryData = json_encode(['categories' => $busCategories, 'revenues' => $revenueAmounts]);
?>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Finance Management</title>
    <?php include_once "../includes/bootstrap_css_includes.php"?>
</head>
<body>
    <div class="container">
        <?php $pageName="Finance Management" ?>
        <?php include_once "../includes/header_row_includes.php";?>
        <div class="col-md-3">
            <?php include_once "../includes/finance_functions.php"; ?>
        </div>
        <div class="col-md-9">
            <?php if ($pendingServicePaymentCount > 0 && in_array(134, $userFunctions)) { ?>
            <div class="row">
                <div class="col-md-12">
                    <div class="alert alert-info">
                        <span class="fas fa-check-circle"></span>&nbsp;
                        <strong>Action Needed:</strong> There <?php if($pendingServicePaymentCount==1){echo "is";}else{echo "are";}?> <strong><?php echo $pendingServicePaymentCount; ?></strong> payment(s) pending.
                        <a href="pending-service-payments.php"  class="alert-link">Check 'Pending Service Payments' to Proceed</a>.
                    </div>
                </div>
            </div>
            <?php } ?>
            <?php if ($pendingSupplierPaymentCount > 0 && in_array(136, $userFunctions)) { ?>
            <div class="row">
                <div class="col-md-12">
                    <div class="alert alert-info">
                        <span class="fas fa-check-circle"></span>&nbsp;
                        <strong>Action Needed:</strong> There <?php if($pendingSupplierPaymentCount==1){echo "is";}else{echo "are";}?> <strong><?php echo $pendingSupplierPaymentCount; ?></strong> payment(s) pending.
                        <a href="pending-supplier-payments.php"  class="alert-link">Check 'Pending Supplier Payments' to Proceed</a>.
                    </div>
                </div>
            </div>
            <?php } ?>
            <div class="row">
                <div class="col-md-6">
                    <div id="expensesBreakDownChartDiv" style="width:100%; height:400px;"> </div>
                </div>
                <div class="col-md-6">
                    <div id="revenueByCategoryChartDiv" style="width:100%; height:400px;"> </div>
                </div>
            </div>
        </div>
    </div>
</body>
<script src="../js/jquery-3.7.1.js"></script>
<script>
    // Expenses Breakdown Chart
    var expensesData = <?php echo $expensesBreakdownData;?>;

    if(expensesData.categories.length > 0) {
        var trace1 = {
            labels: expensesData.categories,
            values: expensesData.expenses,
            type: 'pie',
            textinfo: 'label+percent',
            textposition: 'inside',
            marker: {
                colors: ['#4ECDC4', '#FF6B6B', '#694ECD', '#BD35DC', '#FF9F1C'],
                line: { width: 2, color: 'white' } //divides pieces
            },
            hovertemplate: '<b>%{label}</b><br>Amount: %{value}<extra></extra>'
        };

        var layout1 = {
            title: {text: 'Expenses Breakdown'},
            height: 400,
            margin: { t: 60, b: 60, l: 60, r: 40 }
        };

        Plotly.newPlot('expensesBreakDownChartDiv', [trace1], layout1);
    }

    // Revenue by Bus Category Chart
    var revenueByCategoryData = <?php echo $revenueByCategoryData;?>;   

    if(revenueByCategoryData.categories.length > 0) {
        var trace2 = {
            labels: revenueByCategoryData.categories,
            values: revenueByCategoryData.revenues,
            type: 'pie',
            textinfo: 'label+percent',
            textposition: 'inside',
            marker: {
                colors: ['#694ECD', '#BD35DC', '#FF9F1C'],
                line: { width: 2, color: 'white' } //divides pieces
            },
            hovertemplate: '<b>%{label}</b><br>Revenue: LKR %{value}<extra></extra>'
        };

        var layout2 = {
            title: {text: 'Revenue by Bus Category'},
            height: 400,
            margin: { t: 60, b: 60, l: 60, r: 40 }
        };

        Plotly.newPlot('revenueByCategoryChartDiv', [trace2], layout2);
    }
    
</script>
</html>