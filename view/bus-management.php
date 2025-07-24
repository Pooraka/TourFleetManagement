<?php

include_once '../commons/session.php';
include_once '../model/bus_model.php';

//get user information from session
$userSession=$_SESSION["user"];

$busObj = new Bus();

$busCategoryCountResult = $busObj->getEntireBusFleetCategoryCount();
        
$categoryNames = array();
$categoryCounts = array();

if ($busCategoryCountResult->num_rows > 0) {
    while ($busCategoryRow = $busCategoryCountResult->fetch_assoc()) {
        array_push($categoryNames, $busCategoryRow['category_name']);
        array_push($categoryCounts, (int)$busCategoryRow['count']);
    }
}

// Store the data for JS Use
$busCategoryData = json_encode(['categoryNames' => $categoryNames, 'categoryCounts' => $categoryCounts]);

$busFleetStatusCountResult = $busObj->getBusFleetStatusBreakdown();

$statusNames = array();
$statusCounts = array();

if($busFleetStatusCountResult->num_rows > 0) {
    while ($busStatusRow = $busFleetStatusCountResult->fetch_assoc()) {
        array_push($statusNames, $busStatusRow['status_name']);
        array_push($statusCounts, (int)$busStatusRow['count']);
    }
}

$busStatusData = json_encode(['statusNames' => $statusNames, 'statusCounts' => $statusCounts]);
?>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bus Management</title>
    <?php include_once "../includes/bootstrap_css_includes.php"?>
</head>
<body>
    <div class="container">
        <?php $pageName="Bus Management" ?>
        <?php include_once "../includes/header_row_includes.php";?>
        <div class="col-md-3">
            <?php include_once "../includes/bus_management_functions.php"; ?>
        </div>
        <div class="col-md-9">
            <div class="row">
                <div class="col-md-6">
                    <div id="busCategoryChartDiv" style="width:100%; height:400px;"> </div>
                </div>
                <div class="col-md-6">
                    <div id="busStatusChartDiv" style="width:100%; height:400px;"> </div>
                </div>
            </div>
        </div>
    </div>
</body>
<script src="../js/jquery-3.7.1.js"></script>
<script src="../bootstrap/js/bootstrap.min.js"></script>
<script>
    
    var busCategoryData = <?php echo $busCategoryData;?>;
    var busStatusData = <?php echo $busStatusData;?>;
    
    var trace1 = {
                    labels: busCategoryData.categoryNames,
                    values: busCategoryData.categoryCounts,
                    type: 'pie',
                    textinfo: 'label+percent+value',
                    textposition: 'inside',
                    hovertemplate: '<b>%{label}</b><br>Count: %{value}<br>Percentage: %{percent}<extra></extra>',
                    marker: {
                        //colors: ['#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF', '#FF9F40', '#FF6384', '#C9CBCF']
                    }
                    };

    var layout1 = {
        title: { 
            text: 'Bus Fleet Category Distribution',
            font: { size: 18 }
        },
        showlegend: true,
        legend: {
            orientation: 'v',
            x: 1,
            y: 0.5
        },
        margin: { t: 50, b: 50, l: 50, r: 150 }
    };

    var trace2 = {
                    labels: busStatusData.statusNames,
                    values: busStatusData.statusCounts,
                    type: 'pie',
                    textinfo: 'label+percent+value',
                    textposition: 'inside',
                    hovertemplate: '<b>%{label}</b><br>Count: %{value}<br>Percentage: %{percent}<extra></extra>',
                    marker: {
                        colors: ['#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF', '#FF9F40', '#FF6384', '#C9CBCF']
                    }
                    };

    var layout2 = {
        title: { 
            text: 'Bus Fleet Status Distribution',
            font: { size: 18 }
        },
        showlegend: true,
        legend: {
            orientation: 'v',
            x: 1,
            y: 0.5
        },
        margin: { t: 50, b: 50, l: 50, r: 150 }
    };  

    Plotly.newPlot('busCategoryChartDiv', [trace1], layout1);
    Plotly.newPlot('busStatusChartDiv', [trace2], layout2);

</script>
</html>