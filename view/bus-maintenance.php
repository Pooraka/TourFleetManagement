<?php

include_once '../commons/session.php';
include_once '../model/inspection_model.php';
include_once '../model/bus_model.php';
include_once '../model/service_detail_model.php';



//get user information from session
$userSession=$_SESSION["user"];
$userFunctions=$_SESSION['user_functions'];

$inspectionObj = new Inspection();
$busObj = new Bus();
$serviceDetailObj = new ServiceDetail();

// Get the count of buses that are due for service
$serviceDueBusResult = $busObj->getServiceDueBuses();
$serviceDueBusCount = $serviceDueBusResult->num_rows;

//Get Pending Inspections Data
$pendingInspectionsCount = $inspectionObj->getPendingInspectionsCount();

//Get Upcoming Services Within next 14 days or next 1000 km
$upComingServicesCount = $busObj->getUpComingServicesBusCount();

//Get Average Service Downtime
$averageDowntime = $serviceDetailObj->getAverageServiceDowntime();

// Get failed checklist items data for the chart
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
            <?php include_once "../includes/bus_maintenance_functions.php"; ?>
        </div>
        <div class="col-md-9">
            <?php if ($pendingInspectionsCount > 0) { ?>
            <div class="row">
                <div class="col-md-12">
                    <div class="alert alert-danger">
                        <span class="fa-solid fa-clock"></span>&nbsp;
                        <strong>Action Required:</strong> There are <strong><?php echo $pendingInspectionsCount; ?></strong> pending pre-tour inspections. 
                        <a id="pendingInspectionsLink" href="" class="alert-link">Complete inspections to clear buses for tours</a>.
                    </div>
                </div>
            </div>
            <?php } ?>
            <?php if ($serviceDueBusCount > 0) { ?>
            <div class="row">
                <div class="col-md-12">
                    <div class="alert alert-warning">
                        <span class="fa-solid fa-triangle-exclamation"></span>&nbsp;
                        <strong>Critical Action:</strong> There are <strong><?php echo $serviceDueBusCount; ?></strong> buses due for service. 
                        <a id="initiateServiceLink" href="" class="alert-link">Initiate services now</a>.
                    </div>
                </div>
            </div>
            <?php } ?>
            <?php if ($upComingServicesCount > 0) { ?>
            <div class="row">
                <div class="col-md-12">
                    <div class="alert alert-info">
                        <span class="fa fa-bell" ></span>&nbsp;
                        <strong>Upcoming Services:</strong> There are <strong><?php echo $upComingServicesCount; ?></strong> buses that have services due soon.
                        <a id="upComingServicesLink" href="" target="_blank" class="alert-link">View the up coming services report for details</a>.
                    </div>
                </div>
            </div>
            <?php } ?>
            <div class="row">
                <div class="col-md-6">
                    <div id="failedCheckListChartDiv" style="width:100%; height:300px;"> </div>
                </div>
                <div class="col-md-6">
                    <div class="panel panel-info" style="text-align:center; height:300px">
                        <div class="panel-heading">
                            <span class="fa-solid fa-clock"></span>&nbsp;
                            Average Service Downtime
                        </div>
                        <div class="panel-body">
                            <h1 class="h1"><?php echo $averageDowntime." day(s)"; ?></h1>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
<script src="../js/jquery-3.7.1.js"></script>
<script>
    
    var userFunctionsArray = <?php echo json_encode($userFunctions); ?>;

    var pendingInspectionsURL = "pending-inspections.php";
    var initiateServiceURL = "initiate-service.php";
    var upComingServicesURL = "../reports/upcoming-services-report.php";

    if( userFunctionsArray.includes(130)) {
        $('#pendingInspectionsLink').attr('href', pendingInspectionsURL);
    }
    if( userFunctionsArray.includes(118)) {
        $('#initiateServiceLink').attr('href', initiateServiceURL);
    }
    if( userFunctionsArray.includes(132)) {
        $('#upComingServicesLink').attr('href', upComingServicesURL);
    }
        
    
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