<?php

include_once '../commons/session.php';
include_once '../model/purchase_order_model.php';



//get user information from session
$userSession=$_SESSION["user"];
$userFunctions=$_SESSION['user_functions'];

$poObj = new PurchaseOrder();

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
            <div class="row">
                <div class="col-md-6">
                    <div id="poPipelineChartDiv" style="width:100%; height:400px;"> </div>
                </div>
            </div>
        </div>
    </div>
</body>
<script src="../js/jquery-3.7.1.js"></script>
<script>
    var pipelineData = <?php echo $pipelineData; ?>;
    
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
</script>
</html>