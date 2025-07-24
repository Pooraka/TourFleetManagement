<?php

include_once '../commons/session.php';
include_once '../model/tender_model.php';
include_once '../model/bid_model.php';

$tenderObj = new Tender();
$bidObj = new Bid();

//get user information from session
$userSession=$_SESSION["user"];
$userFunctions=$_SESSION['user_functions'];

// Get the count of tenders that are closing tomorrow
$tendersClosingTomorrowCount = $tenderObj->getTenderCountClosingTomorrow();

//Get the count of tenders that are closed
$tendersClosedCount = $tenderObj->getClosedTendersCount();

//Get Most Active Bidders
$mostActiveBiddersResult = $bidObj->getMostActiveBidders();

$bidderNames = array();
$bidCounts = array();

if($mostActiveBiddersResult->num_rows > 0) {
    while ($bidderRow = $mostActiveBiddersResult->fetch_assoc()) {
        array_push($bidderNames, $bidderRow['supplier_name']);
        array_push($bidCounts, (int)$bidderRow['bid_count']);
    }
}

$mostActiveBiddersData = json_encode(['bidderNames' => $bidderNames, 'bidCounts' => $bidCounts]);

//Get Bid Performance By Suppliers
$bidPerformanceResult = $bidObj->getBidPerformanceBySuppliers();

$performanceSupplierNames = array();
$performanceTotalBids = array();
$performanceWonBids = array();

if($bidPerformanceResult->num_rows > 0) {
    while ($performanceRow = $bidPerformanceResult->fetch_assoc()) {
        array_push($performanceSupplierNames, $performanceRow['supplier_name']);
        array_push($performanceTotalBids, (int)$performanceRow['total_bids']);
        array_push($performanceWonBids, (int)$performanceRow['won_bids']);
    }
}

$bidPerformanceData = json_encode(['supplierNames' => $performanceSupplierNames, 'totalBids' => $performanceTotalBids, 'wonBids' => $performanceWonBids]);
?>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tender</title>
    <?php include_once "../includes/bootstrap_css_includes.php"?>
</head>
<body>
    <div class="container">
        <?php $pageName="Tender Management" ?>
        <?php include_once "../includes/header_row_includes.php";?>
        <div class="col-md-3">
            <?php include_once "../includes/tender_functions.php"; ?>
        </div>
        <div class="col-md-9">
            <?php if ($tendersClosingTomorrowCount > 0 && in_array(70, $userFunctions)) { ?>
            <div class="row">
                <div class="col-md-12">
                    <div class="alert alert-warning">
                        <span class="fas fa-exclamation-triangle"></span>&nbsp;
                        <strong>Closing Tenders:</strong> There are <strong><?php echo $tendersClosingTomorrowCount; ?></strong> tenders that are closing tomorrow.
                        <a href="open-tenders.php" class="alert-link">Add all bids before tender closure</a>.
                    </div>
                </div>
            </div>
            <?php } ?>
            <?php if ($tendersClosedCount > 0) { ?>
            <div class="row">
                <div class="col-md-12">
                    <div class="alert alert-info">
                        <span class="fas fa-check-circle"></span>&nbsp;
                        <strong>Closed Tenders:</strong> There <?php if($tendersClosedCount==1){echo "is";}else{echo "are";}?> <strong><?php echo $tendersClosedCount; ?></strong> tender(s) closed.
                        <a href="open-tenders.php"  class="alert-link">Check 'View Pending Tenders' for evaluation</a>.
                    </div>
                </div>
            </div>
            <?php } ?>
            <div class="row">
                <div class="col-md-6">
                    <div id="mostActiveBiddersChartDiv" style="width:100%; height:400px;"> </div>
                </div>
                <div class="col-md-6">
                    <div id="bidPerformanceChartDiv" style="width:100%; height:400px;"> </div>
                </div>
            </div>
        </div>
    </div>
</body>
<script src="../js/jquery-3.7.1.js"></script>
<script>

    //Most Active Bidders Chart
    var $mostActiveBiddersData = <?php echo $mostActiveBiddersData;?>;

    if($mostActiveBiddersData.bidderNames.length > 0) {
        var trace1 = {
            x: $mostActiveBiddersData.bidderNames,
            y: $mostActiveBiddersData.bidCounts,
            type: 'bar',
            marker: {
                color: '#4ECDC4',
                opacity: 1
            },
            hovertemplate: '<b>%{x}</b><br>Bids: %{y}<extra></extra>'
        };

        var layout1 = {
            title: {
                text: 'Most Active Bidders',
                font: { size: 15 }
            },
            xaxis: {
                title: { text: 'Bidder' },
                showticklabels: false, //Hide x axis names as it takes lot of space
                tickangle: -45
            },
            yaxis: {
                title: { text: 'Number of Bids' }
                //,dtick: 1, commented as this will show all integer values on y-axis
            },
            margin: { t: 60, b: 170, l: 60, r: 40 }
        };

        Plotly.newPlot('mostActiveBiddersChartDiv',[trace1], layout1);
    } else {
       $('#mostActiveBiddersChartDiv').html('<div class="alert alert-warning">No active bidders data available.</div>');
    }

    //Bid Performance By Suppliers Chart
    var $bidPerformanceData = <?php echo $bidPerformanceData;?>;

    if($bidPerformanceData.supplierNames.length > 0) {
        var trace2 = {
            x: $bidPerformanceData.supplierNames,
            y: $bidPerformanceData.totalBids,
            type: 'bar',
            name: 'Total Bids',
            marker: {
                color: '#694ecdff',
                opacity: 1
            },
            hovertemplate: '<b>%{x}</b><br>Total Bids: %{y}<extra></extra>'
        };

        var trace3 = {
            x: $bidPerformanceData.supplierNames,
            y: $bidPerformanceData.wonBids,
            type: 'bar',
            name: 'Won Bids',
            marker: {
                color: '#6bff9cff',
                opacity: 1
            },
            hovertemplate: '<b>%{x}</b><br>Won Bids: %{y}<extra></extra>'
        };

        var layout2 = {
            title: {
                text: 'Bid Performance By Suppliers',
                font: { size: 15 }
            },
            xaxis: {
                title: { text: 'Supplier' },
                showticklabels: false, //Hide x axis names as it takes lot of space
                tickangle: -45
            },
            yaxis: {
                title: { text: 'Number of Bids' }
                //,dtick: 1, commented as this will show all integer values on y-axis
            },
            margin: { t: 60, b: 170, l: 60, r: 40 }
        };

        Plotly.newPlot('bidPerformanceChartDiv',[trace2, trace3], layout2);
    } else {
       $('#bidPerformanceChartDiv').html('<div class="alert alert-warning">No bid performance data available.</div>');
    }
</script>
</html>