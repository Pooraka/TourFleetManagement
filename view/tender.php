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
            <ul class="list-group">
                <a href="dashboard.php" class="list-group-item">
                    <span class="fa-solid fa-house"></span> &nbsp;
                    Back To Dashboard
                </a>
                <a href="add-supplier.php" class="list-group-item" style="display:<?php echo checkPermissions(61); ?>">
                    <span class="fa-solid fa-plus"></span> &nbsp;
                    Add Supplier
                </a>
                <a href="view-suppliers.php" class="list-group-item" style="display:<?php echo checkPermissions(62); ?>">
                    <span class="fa-solid fa-truck-field"></span> &nbsp;
                    View Suppliers
                </a>
                <a href="add-tender.php" class="list-group-item" style="display:<?php echo checkPermissions(67); ?>">
                    <span class="fa-solid fa-plus"></span> &nbsp;
                    Add Tender
                </a>
                <a href="open-tenders.php" class="list-group-item" style="display:<?php echo checkPermissions(68); ?>">
                    <span class="fa-solid fa-folder-open"></span> &nbsp;
                    View Pending Tenders
                </a>
                <a href="past-tenders.php" class="list-group-item" style="display:<?php echo checkPermissions(164); ?>">
                    <span class="fa-solid fa-scroll"></span> &nbsp;
                    Past Tenders
                </a>
                <a href="tender-status-report.php" class="list-group-item" style="display:<?php echo checkPermissions(150); ?>">
                    <span class="fa-solid fa-file-contract"></span> &nbsp;
                    Tender Status Report
                </a>
            </ul>
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
                    <div id="" style="width:100%; height:400px;"> </div>
                </div>
            </div>
        </div>
    </div>
</body>
<script src="../js/jquery-3.7.1.js"></script>
<script>
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
</script>
</html>