<?php

include_once '../commons/session.php';
include_once '../model/customer_model.php';

//get user information from session
$userSession=$_SESSION["user"];
$userFunctions=$_SESSION['user_functions'];

$customerObj = new Customer();

// Get the count of active customers
$activeCustomerCount = $customerObj->getActiveCustomerCount();

// Get the count of customers who had tours within the last 7 days
$customerCountWithToursWithinLast7Days = $customerObj->getCustomerCountWithToursWithinLast7Days();

// Get the new customer growth data
$newCustomerGrowth = $customerObj->getNewCustomerGrowth();

$acquisitionDates = array();
$newCustomerCounts = array();

if($newCustomerGrowth) {
    while($row = $newCustomerGrowth->fetch_assoc()) {
        $acquisitionDates[] = $row['acquisition_date'];
        $newCustomerCounts[] = $row['new_customer_count'];
    }
}

$newCustomerGrowthData = json_encode(['acquisitionDates' => $acquisitionDates, 
'newCustomerCounts' => $newCustomerCounts]);
?>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer</title>
    <?php include_once "../includes/bootstrap_css_includes.php"?>
</head>
<body>
    <div class="container">
        <?php $pageName="Customer" ?>
        <?php include_once "../includes/header_row_includes.php";?>
        <div class="col-md-3">
            <ul class="list-group">
                <a href="dashboard.php" class="list-group-item">
                    <span class="fa-solid fa-house"></span> &nbsp;
                    Back To Dashboard
                </a>
                <a href="add-customer.php" class="list-group-item" style="display:<?php echo checkPermissions(49); ?>">
                    <span class="fa-solid fa-plus"></span> &nbsp;
                    Add Customer
                </a>
                <a href="view-customers.php" class="list-group-item" style="display:<?php echo checkPermissions(50); ?>">
                    <span class="fa-solid fa-users"></span> &nbsp;
                    View Customers
                </a>
                <a href="revenue-by-customers.php" class="list-group-item" style="display:<?php echo checkPermissions(147); ?>">
                    <span class="fa-solid fa-file-invoice-dollar"></span> &nbsp;
                    Revenue By Customers
                </a>
            </ul>
        </div>
        <div class="col-md-9">
            <div class="row">
                <div class="col-md-12">
                    <div id="newCustomerGrowthChartDiv" style="width:100%; height:370px;"> </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="panel panel-info" style="height:120px; text-align:center;">
                    <div class="panel-heading" style="height:35px;">
                        <p>No of Customers</p>
                    </div>
                    <div class="panel-body">
                        <h1 class="h1"><?php echo $activeCustomerCount;?></h1>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="panel panel-success" style="height:120px;text-align:center;">
                    <div class="panel-heading" style="height:35px;">
                        <p>No of Customers Had Tours Within Last 7 Days</p>
                    </div>
                    <div class="panel-body">
                        <h1 class="h1"><?php echo $customerCountWithToursWithinLast7Days;?></h1>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
<script src="../js/jquery-3.7.1.js"></script>
<script>

    //New Customer Growth Chart
    var newCustomerGrowthData = <?php echo $newCustomerGrowthData;?>;

    // Check if there is data to plot
    if(newCustomerGrowthData.acquisitionDates.length > 0) {
        var trace1 = {
            x: newCustomerGrowthData.acquisitionDates, // X-axis: acquisition dates
            y: newCustomerGrowthData.newCustomerCounts, // Y-axis: new customer counts
            type: 'scatter', // Chart type: scatter plot
            mode: 'lines+markers', // Show both lines and markers
            line: {
                color: '#4ECDC4', // Line color
                width: 3 // Line width
            },
            marker: {
                size: 8, // Marker size
                color: '#4ECDC4', // Marker color
                opacity: 0.8 // Marker opacity
            },
            hovertemplate: '<b>%{x}</b><br>New Customers: %{y}<extra></extra>' // Custom hover text
            // text: [], // Add custom text labels for each point
            // name: 'New Customers', // Legend name for this trace
            // fill: 'tozeroy', //  Fill area under the curve
        };

        var layout1 = {
            title: {text: 'New Customer Growth Over Time'}, // Chart title
            xaxis: {
                title: { text: 'Acquisition Date' }, // X-axis label
                tickangle: -45 
                // type: 'date', //  Specify axis type as date
                // tickformat: '%b %d', //  Custom date format
                // showgrid: true, //  Show grid lines
            },
            yaxis: {
                title: { text: 'Number of New Customers' }, // Y-axis label
                rangemode: 'tozero', // Y-axis starts at zero
                tickformat: ',d', // Format ticks as integers
                tick0: 0, // Start ticks at 0
                dtick: 1 // Tick step size
                // showline: true, // (future) Show axis line
                // gridcolor: '#eee', // Grid line color
            },
            margin: { t: 60, b: 100, l: 60, r: 40 } // Chart margins
            //, legend: { orientation: 'h' } //  Horizontal legend
            , plot_bgcolor: '#ffffffff' //  Plot background color
            , paper_bgcolor: '#ffffffff' //  Outer background color
        };


        Plotly.newPlot('newCustomerGrowthChartDiv', [trace1], layout1);
    } else {
        $('#newCustomerGrowthChartDiv').html('<div class="alert alert-warning">No new customer growth data available.</div>');
    }
</script>
</html>