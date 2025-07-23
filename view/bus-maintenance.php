<?php

include_once '../commons/session.php';
include_once '../model/inspection_model.php';

//get user information from session
$userSession=$_SESSION["user"];

// Get failed checklist items data for the chart
$inspectionObj = new Inspection();
$failedChecklistResult = $inspectionObj->getFailedCheckListItemCount();

$checklistItemNames = array();
$failureCounts = array();

if($failedChecklistResult->num_rows > 0) {
    while ($checklistRow = $failedChecklistResult->fetch_assoc()) {
        array_push($checklistItemNames, $checklistRow['checklist_item_name']);
        array_push($failureCounts, (int)$checklistRow['failure_count']);
    }
}

$failedChecklistData = json_encode(['itemNames' => $checklistItemNames, 'failureCounts' => $failureCounts]);
?>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bus Maintenance</title>
    <?php include_once "../includes/bootstrap_css_includes.php"?>
</head>
<body>
    <div class="container">
        <?php $pageName="Bus Maintenance" ?>
        <?php include_once "../includes/header_row_includes.php";?>
        <div class="col-md-3">
            <ul class="list-group">
                <a href="dashboard.php" class="list-group-item">
                    <span class="fa-solid fa-house"></span> &nbsp;
                    Back To Dashboard
                </a>
                <a href="add-service-station.php" class="list-group-item" style="display:<?php echo checkPermissions(114); ?>">
                    <span class="fa-solid fa-plus"></span> &nbsp;
                    Add Service Station
                </a>
                <a href="view-service-stations.php" class="list-group-item" style="display:<?php echo checkPermissions(115); ?>">
                    <span class="fa-solid fa-shop"></span> &nbsp;
                    View Service Stations
                </a>
                <a href="initiate-service.php" class="list-group-item" style="display:<?php echo checkPermissions(118); ?>">
                    <span class="fa-solid fa-wrench"></span> &nbsp;
                    Initiate Service
                </a>
                <a href="view-ongoing-services.php" class="list-group-item" style="display:<?php echo checkPermissions(119); ?>">
                    <span class="fa-solid fa-gear fa-spin"></span> &nbsp;
                    View Ongoing Services
                </a>
                <a href="service-history.php" class="list-group-item" style="display:<?php echo checkPermissions(122); ?>">
                    <span class="fa-solid fa-history"></span> &nbsp;
                    Service History
                </a>
                <a href="manage-checklist-items.php" class="list-group-item" style="display:<?php echo checkPermissions(125); ?>">
                    <span class="fa-solid fa-list-check"></span> &nbsp;
                    Manage Checklist Items
                </a>
                <a href="manage-checklist-template.php" class="list-group-item" style="display:<?php echo checkPermissions(129); ?>">
                    <span class="fa-solid fa-file-lines"></span> &nbsp;
                    Manage Checklist Template
                </a>
                <a href="pending-inspections.php" class="list-group-item" style="display:<?php echo checkPermissions(130); ?>">
                    <span class="fa-solid fa-car-on"></span> &nbsp;
                    Pending Inspections
                </a>
                <a href="past-inspections.php" class="list-group-item" style="display:<?php echo checkPermissions(152); ?>">
                    <span class="fa-solid fa-check-double"></span> &nbsp;
                    View Past Inspections
                </a>
                <a href="../reports/upcoming-services-report.php" class="list-group-item" target="_blank" style="display:<?php echo checkPermissions(132); ?>">
                    <span class="fa-solid fa-calendar-days"></span> &nbsp;
                    Upcoming Services Report
                </a>
                <a href="inspection-status-report.php" class="list-group-item" style="display:<?php echo checkPermissions(133); ?>">
                    <span class="fa-solid fa-clipboard-check"></span> &nbsp;
                    Inspection Result Report
                </a>
            </ul>
        </div>
        <div class="col-md-9">
            <div class="row">
                <div class="col-md-6">
                    <div id="failedCheckListChartDiv" style="width:100%; height:400px;"> </div>
                </div>
                <div class="col-md-6">
                    <div id="busStatusChartDiv" style="width:100%; height:400px;"> </div>
                </div>
            </div>
        </div>
    </div>
</body>
<script src="../js/jquery-3.7.1.js"></script>
<script>
    
    var failedChecklistData = <?php echo $failedChecklistData;?>;
    
    // Failed Checklist Items Bar Chart
    var trace1 = {
        x: failedChecklistData.itemNames,
        y: failedChecklistData.failureCounts,
        type: 'bar',
        marker: {
            color: '#DC3545',
            opacity: 0.8
        },
        hovertemplate: '<b>%{x}</b><br>Failures: %{y}<extra></extra>'
    };

    var layout1 = {
        title: { 
            text: 'Top 5 Most Failed Inspection Checklist Items',
            font: { size: 15 }
        },
        xaxis: {
            title: { text: 'Checklist Item' },
            tickangle: -45
        },
        yaxis: {
            title: { text: 'Number of Failures' },
            dtick: 1
        },
        margin: { t: 60, b: 170, l: 60, r: 40 }
    };

    Plotly.newPlot('failedCheckListChartDiv', [trace1], layout1);

</script>
</html>