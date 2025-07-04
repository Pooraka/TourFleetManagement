<?php

include_once '../commons/session.php';

//get user information from session
$userSession=$_SESSION["user"];
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
        <?php $pageName="Finance Management - Monthly Supplier Payments" ?>
        <?php include_once "../includes/header_row_includes.php";?>
        <div class="col-md-3">
            <ul class="list-group">
                <a href="pending-service-payments.php" class="list-group-item">
                    <span class="glyphicon glyphicon-search"></span> &nbsp;
                    Pending Service Payments
                </a>
                <a href="pending-service-payments.php" class="list-group-item">
                    <span class="glyphicon glyphicon-search"></span> &nbsp;
                    Pending Supplier Payments
                </a>
                <a href="supplier-payment-monthly-chart.php" class="list-group-item">
                    <span class="fa fa-solid fa-chart-bar"></span> &nbsp;
                    Generate Reports
                </a>
            </ul>
        </div>
        <div class="col-md-9">
            <div class="row">
                <div class="col-md-10 col-md-offset-1">
                    <div id="trend">

                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
<script src="../js/jquery-3.7.1.js"></script>
<script src="../bootstrap/js/bootstrap.min.js"></script>
<script>
    
 
    
  
        var data = {
            x:['2025-03','2025-04', '2025-05', '2025-06'],
            y:[12123.58,20120.96, 14785.78, 23369.14],
            mode: 'lines+markers',  
            type: 'bar',
            name: 'Supplier Payments',
            line: { color: '#17A2B8', width: 3 },
            marker: { color: '#17A2B8', size: 8 },
            hovertemplate:'<b>Cost</b>:LKR %{y:,.2f}<extra></extra>'
        };

        var layout = {
                title: { text:'Supplier Payments Monthly'},
                xaxis: {
                    title: { text:'Month'},
                    type:'category' // put this to force to get the x axis the way author want
                },
                yaxis: {
                    title: {text:'Total Cost (LKR)'},
                    // Add a 'LKR ' prefix to the y-axis ticks
                    // tickprefix: 'LKR ',
                    separatethousands: true,
                    rangemode:'tozero'
                },
                margin: { t: 50, b: 100, l: 80, r: 40 } // Adjust margins to prevent labels from being cut off
        };

        Plotly.newPlot('trend', [data], layout);
  
</script>
</html>