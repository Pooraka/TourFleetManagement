<?php

include_once '../commons/session.php';
include_once '../model/purchase_order_model.php';
include_once '../model/bid_model.php';



//get user information from session
$userSession=$_SESSION["user"];
$userFunctions=$_SESSION['user_functions'];

$poObj = new PurchaseOrder();
$bidObj = new Bid();

//Get PO Count Pending Supplier Invoice
$poPendingInvoiceCount = $poObj->getPOCountPendingSupplierInvoice();

//Get Awarded Bids Count
$awardedBidsCount = $bidObj->getAwardedBidsCount();

//Get PO Count Pending Approval
$poPendingApprovalCount = $poObj->getPOCountPendingApproval();

//Get PO Pipeline Chart Data
$poPipelineResult = $poObj->getPurchaseOrderPipelineWithinLast14Days();

$poStatusNames = array();
$poStatusCounts = array();

if($poPipelineResult->num_rows>0){

    while($row = $poPipelineResult->fetch_assoc()){

        array_push($poStatusNames,$row["status_name"]);
        array_push($poStatusCounts,$row["po_count"]);
    }
}

$pipelineData = json_encode(['poStatusName'=>$poStatusNames , 'poStatusCounts'=>$poStatusCounts]);

//Get Top 4 Most Spending By Supplier
$topSpendingSuppliersResult = $poObj->getMostSpendingBySupplier();

$supplierNames = array();
$supplierSpending = array();

if($topSpendingSuppliersResult->num_rows > 0){

    while($row = $topSpendingSuppliersResult->fetch_assoc()){

        array_push($supplierNames, $row["supplier_name"]);
        array_push($supplierSpending, $row["total_spent"]);
    }
}

$topSpendingData = json_encode(['supplierNames' => $supplierNames, 'supplierSpending' => $supplierSpending]);

?>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Purchasing</title>
    <?php include_once "../includes/bootstrap_css_includes.php"?>
</head>
<body>
    <div class="container">
        <?php $pageName="Purchasing" ?>
        <?php include_once "../includes/header_row_includes.php";?>
        <div class="col-md-3">
            <ul class="list-group">
                <a href="dashboard.php" class="list-group-item">
                    <span class="fa-solid fa-house"></span> &nbsp;
                    Back To Dashboard
                </a>
                <a href="awarded-bids.php" class="list-group-item" style="display:<?php echo checkPermissions(89); ?>">
                    <span class="fa-solid fa-gavel"></span> &nbsp;
                    View Awarded Bids
                </a>
                <a href="pending-purchase-orders.php" class="list-group-item" style="display:<?php echo checkPermissions(92); ?>">
                    <span class="fa-solid fa-file-import"></span> &nbsp;
                    View Pending PO
                </a>
                <a href="past-purchase-orders.php" class="list-group-item" style="display:<?php echo checkPermissions(162); ?>">
                    <span class="fa-solid fa-scroll"></span> &nbsp;
                    Past Purchase Orders
                </a>
                <a href="po-status-report.php" class="list-group-item" style="display:<?php echo checkPermissions(97); ?>">
                    <span class="fa-solid fa-chart-gantt"></span> &nbsp;
                    PO Status Report
                </a>
            </ul>
        </div>
        <div class="col-md-9">
            <?php if ($poPendingApprovalCount > 0 && in_array(93, $userFunctions)) { ?>
            <div class="row">
                <div class="col-md-12">
                    <div class="alert alert-info">
                        <span class="fas fa-bell"></span>&nbsp;
                        <strong>Pending Action:</strong> There are <strong><?php echo $poPendingApprovalCount; ?></strong> purchase orders pending approval.
                        <a href="pending-purchase-orders.php" class="alert-link">Review now</a>.
                    </div>
                </div>
            </div>
            <?php } ?>
            <?php if ($poPendingInvoiceCount > 0 && in_array(96, $userFunctions)) { ?>
            <div class="row">
                <div class="col-md-12">
                    <div class="alert alert-info">
                        <span class="fas fa-bell"></span>&nbsp;
                        <strong>Pending Action:</strong> There are <strong><?php echo $poPendingInvoiceCount; ?></strong> purchase orders pending supplier invoices.
                        <a href="pending-purchase-orders.php" class="alert-link">Get Supplier Confirmation & Invoices Now</a>.
                    </div>
                </div>
            </div>
            <?php } ?>
            <?php if ($awardedBidsCount > 0 && in_array(89, $userFunctions)) { ?>
            <div class="row">
                <div class="col-md-12">
                    <div class="alert alert-info">
                        <span class="fas fa-bell"></span>&nbsp;
                        <strong>Pending Action:</strong> There are <strong><?php echo $awardedBidsCount; ?></strong> awarded bids.
                        <a href="awarded-bids.php" class="alert-link">Review now</a>.
                    </div>
                </div>
            </div>
            <?php } ?>
            <div class="row">
                <div class="col-md-6">
                    <div id="poPipelineChartDiv" style="width:100%; height:400px;"> </div>
                </div>
                <div class="col-md-6">
                    <div id="topSpendingChartDiv" style="width:100%; height:400px;"> </div>
                </div>
            </div>
        </div>
    </div>
</body>
<script src="../js/jquery-3.7.1.js"></script>
<script>
    var pipelineData = <?php echo $pipelineData; ?>;
    
    if (pipelineData.poStatusName.length === 0) {

        $('#poPipelineChartDiv').html('<div class="alert alert-warning">No Purchase Order pipeline data available for the last 14 days.</div>');
    } else {

        // Purchase Order Pipeline Pie Chart
        var trace1 = {
            labels: pipelineData.poStatusName,
            values: pipelineData.poStatusCounts,
            type: 'pie',
            textinfo: 'value',
            //textinfo: 'label+percent+value',
            textposition: 'inside',
            hovertemplate: '<b>%{label}</b><br>Count: %{value}<br>Percentage: %{percent}<extra></extra>',
            marker: {
                colors: ['#FF6B6B', '#4ECDC4', '#45B7D1', '#96CEB4', '#FFEAA7', '#DDA0DD']
            },
            domain: {
                x: [0, 1.0],
                y: [0, 1.0]
            }
        };

        var layout1 = {
            title: { 
                text: 'Purchase Order Pipeline Status (Last 14 Days)',
                font: { size: 16 }
            },
            showlegend: true,
            legend: {
                orientation: 'v',
                x: 1,
                y: 0.5
            },
            margin: { t: 60, b: 50, l: 50, r: 150 }
        };

        Plotly.newPlot('poPipelineChartDiv', [trace1], layout1, {responsive: true});
    }
    
    var topSpendingData = <?php echo $topSpendingData;?>;

    if (topSpendingData.supplierNames.length === 0) {

        $('#topSpendingChartDiv').html('<div class="alert alert-warning">No spending data available for suppliers.</div>');
    } else {

        // Top 4 Most Spending By Supplier Pie Chart
        var trace2 = {
            labels: topSpendingData.supplierNames,
            values: topSpendingData.supplierSpending,
            type: 'pie',
            //textinfo: 'value', //This is not needed as i use a texttemplate
            texttemplate: 'LKR<br> %{value:,.2f}',
            textposition: 'inside',
            hovertemplate: '<b>%{label}</b><br>Total Spent:LKR %{value:,.2f}<br>Percentage: %{percent}<extra></extra>',
            marker: {
            opacity: 0.8
            },
            domain: {
                x: [0, 1.0],
                y: [0, 1.0]
            }
        };

        var layout2 = {
            title: { 
                text: 'Top Most Spending By Supplier',
                font: { size: 16 }
            },
            showlegend: true,
            legend: {
                orientation: 'v',
                x: 1,
                y: 0.5
            },
            margin: { t: 60, b: 50, l: 50, r: 150 }
        };

        Plotly.newPlot('topSpendingChartDiv', [trace2], layout2, {responsive: true});
    }
</script>
</html>