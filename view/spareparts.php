<?php

include_once '../commons/session.php';
include_once '../model/sparepart_model.php';

//get user information from session
$userSession=$_SESSION["user"];
$userFunctions=$_SESSION['user_functions'];

$sparePartObj = new SparePart();

//Get zero stock spare parts count
$zeroStockCount = $sparePartObj->getSparePartCountWith0Stock();

//Get low stock spare parts count
$lowStockCount = $sparePartObj->getSparePartCountWithLowStock();

//Get top 5 most stocked spare parts
$top5MostStockedPartsResult = $sparePartObj->getTop5MostStockedParts();

$partNames = array();
$partQuantities = array();

if($top5MostStockedPartsResult->num_rows > 0) {
    while ($partRow = $top5MostStockedPartsResult->fetch_assoc()) {
        array_push($partNames, $partRow['part_name']);
        array_push($partQuantities, (int)$partRow['quantity_on_hand']);
    }
}

$top5PartsData = json_encode(['partNames' => $partNames, 'partQuantities' => $partQuantities]);

//Get Spare Parts Yet To Receive and Issue GRN
$sparePartYetToReceiveResult = $sparePartObj->getSparePartsYetToReceive();

$sparePartNames = array();
$partsPendingToReceive = array();

if($sparePartYetToReceiveResult->num_rows > 0) {
    while ($partRow = $sparePartYetToReceiveResult->fetch_assoc()) {
        array_push($sparePartNames, $partRow['part_name']);
        array_push($partsPendingToReceive, (int)$partRow['pending_count']);
    }
}

$sparePartsPendingData = json_encode(['sparePartNames'=>$sparePartNames, 'pendingCounts'=>$partsPendingToReceive]);
?>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Spare Parts</title>
    <?php include_once "../includes/bootstrap_css_includes.php"?>
</head>
<body>
    <div class="container">
        <?php $pageName="Spare Part Management" ?>
        <?php include_once "../includes/header_row_includes.php";?>
        <div class="col-md-3">
            <ul class="list-group">
                <a href="dashboard.php" class="list-group-item">
                    <span class="fa-solid fa-house"></span> &nbsp;
                    Back To Dashboard
                </a>
                <a href="register-spareparts.php" class="list-group-item" style="display:<?php echo checkPermissions(98); ?>">
                    <span class="fa-solid fa-gears"></span> &nbsp;
                    Register Spare Parts
                </a>
                <a href="spare-part-types.php" class="list-group-item" style="display:<?php echo checkPermissions(99); ?>">
                    <span class="fas fa-eye"></span> &nbsp;
                    View Spare Part Types
                </a>
                <a href="add-spare-parts.php" class="list-group-item" style="display:<?php echo checkPermissions(101); ?>">
                    <span class="fa-solid fa-cart-plus"></span> &nbsp;
                    Add Spare Parts
                </a>
                <a href="view-grns.php" class="list-group-item" style="display:<?php echo checkPermissions(102); ?>">
                    <span class="fa-solid fa-truck-ramp-box"></span> &nbsp;
                    View GRNs
                </a>
                <a href="view-spare-parts.php" class="list-group-item" style="display:<?php echo checkPermissions(103); ?>">
                    <span class="fa-solid fa-boxes-stacked"></span> &nbsp;
                    View Spare Parts
                </a>
                <a href="../reports/part-inventory-report.php" class="list-group-item" target="_blank" style="display:<?php echo checkPermissions(106); ?>">
                    <span class="fa-solid fa-warehouse"></span> &nbsp;
                    Spare Part Inventory Report
                </a>
                <a href="spare-part-transaction-history.php" class="list-group-item" style="display:<?php echo checkPermissions(107); ?>">
                    <span class="fa-solid fa-right-left"></span> &nbsp;
                    Spare Part Transactions
                </a>
            </ul>
        </div>
        <div class="col-md-9">
            <?php if($zeroStockCount > 0) { ?>
            <div class="row">
                <div class="col-md-12">
                    <div class="alert alert-danger">
                        <span class="fa-solid fa-triangle-exclamation"></span> &nbsp;
                        <strong>Action Required:</strong> There are <?php echo $zeroStockCount; ?> spare parts with zero stock. Please consult Procurement Officer.
                    </div>
                </div>
            </div>
            <?php } ?>
            <?php if($lowStockCount > 0) { ?>
            <div class="row">
                <div class="col-md-12">
                    <div class="alert alert-warning">
                        <span class="fa-solid fa-triangle-exclamation"></span> &nbsp;
                        <strong>Action Required:</strong> There are <?php echo $lowStockCount; ?> spare parts with low stock. Please consult Procurement Officer.
                    </div>
                </div>
            </div>
            <?php } ?>
            <div class="row">
                <div class="col-md-6">
                    <div id="top5MostStockedPartsDiv" style="width:100%; height:400px;"> </div>
                </div>
                <div class="col-md-6">
                    <div id="pendingToReceivePartsDiv" style="width:100%; height:400px;"> </div>
                </div>
            </div>
        </div>
    </div>
</body>
<script src="../js/jquery-3.7.1.js"></script>
<script>

    var top5PartsData = <?php echo $top5PartsData;?>;

    // Top 5 Most Stocked Parts Bar Chart
    var trace1 = {
        x: top5PartsData.partNames,
        y: top5PartsData.partQuantities,
        type: 'bar',
        marker: {
            color: '#bd35dcff',
            opacity: 0.8
        },
        hovertemplate: '<b>%{x}</b><br>Stock: %{y}<extra></extra>'
    };

    var layout1 = {
        title: { 
            text: 'Top 5 Most Stocked Spare Parts',
            font: { size: 15 }
        },
        xaxis: {
            title: { text: 'Spare Part Name' },
            tickangle: -45
        },
        yaxis: {
            title: { text: 'Stock Quantity' },
            //dtick: 1, commented as this will show all integer values on y-axis
            
        },
        margin: { t: 60, b: 170, l: 60, r: 40 }
    };

    Plotly.newPlot('top5MostStockedPartsDiv', [trace1], layout1);

    var sparePartsPendingData = <?php echo $sparePartsPendingData;?>;

    // Spare Parts Pending to Receive Bar Chart
    var trace2 = {
        x: sparePartsPendingData.sparePartNames,
        y: sparePartsPendingData.pendingCounts,
        type: 'bar',
        marker: {
            color: '#3538dcff',
            opacity: 0.8
        },
        hovertemplate: '<b>%{x}</b><br>Pending Count: %{y}<extra></extra>'
    };

    var layout2 = {
        title: { 
            text: 'Parts Pending to Receive At Warehouse',
            font: { size: 15 }
        },
        xaxis: {
            title: { text: 'Spare Part Name' },
            tickangle: -45
        },
        yaxis: {
            title: { text: 'Pending Count' },
            //dtick: 1, commented as this will show all integer values on y-axis
        },
        margin: { t: 60, b: 170, l: 60, r: 40 }
    };

    Plotly.newPlot('pendingToReceivePartsDiv', [trace2], layout2);

</script>
</html>