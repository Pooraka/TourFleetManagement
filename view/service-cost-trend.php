<?php

include_once '../commons/session.php';

//get user information from session
$userSession=$_SESSION["user"];

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
        <?php $pageName="Finance - Service Cost Trend" ?>
        <?php include_once "../includes/header_row_includes.php";?>
        <div class="col-md-3">
            <ul class="list-group">
                <a href="add-service-station.php" class="list-group-item">
                    <span class="glyphicon glyphicon-plus"></span> &nbsp;
                    Add Service Station
                </a>
                <a href="view-service-stations.php" class="list-group-item">
                    <span class="glyphicon glyphicon-search"></span> &nbsp;
                    View Service Stations
                </a>
                <a href="initiate-service.php" class="list-group-item">
                    <span class="fa-solid fa-wrench"></span> &nbsp;
                    Initiate Service
                </a>
                <a href="view-ongoing-services.php" class="list-group-item">
                    <span class="fa-solid fa-gear fa-spin"></span> &nbsp;
                    View Ongoing Services
                </a>
                <a href="service-history.php" class="list-group-item">
                    <span class="fa fa-list-alt"></span> &nbsp;
                    Service History
                </a>
                <a href="service-cost-trend.php" class="list-group-item">
                    <span class="fas fa-chart-line"></span> &nbsp;
                    Service cost trend
                </a>
            </ul>
        </div>
        <div class="col-md-9">
            <div class="row">
                <div id="msg" class="col-md-offset-3 col-md-6" style="text-align:center;">
                    <?php if (isset($_GET["msg"])) { ?>

                        <script>
                            var msgElement = document.getElementById("msg");
                            msgElement.classList.add("alert", "alert-danger");
                        </script>

                        <b> <p> <?php echo base64_decode($_GET["msg"]); ?></p></b>
                        <?php
                    }
                    ?>
                </div>
            </div>
            <div class="row">
                <div class="col-md-2">
                    <label class="control-label">Start Month</label>
                </div>
                <div class="col-md-3">
                    <input type="month" class="form-control" name="start_month" id="start_month"/>
                </div>
                <div class="col-md-2">
                    <label class="control-label">End Month</label>
                </div>
                <div class="col-md-3">
                    <input type="month" class="form-control" name="end_month" id="end_month"/>
                </div>
                <div class="col-md-2">
                    <input type="button" value="Generate" class="btn btn-primary" id="generateChartBtn"/>
                </div>
            </div>
            <div class="row">
                &nbsp;
            </div>
            <div class="row">
                <div class="col-md-10 col-md-offset-1">
                    <div id="trend" style="width:100%; height:400px;"> </div>
                </div>
            </div>
        </div>
    </div>
</body>
<script src="../js/jquery-3.7.1.js"></script>
<script src="../bootstrap/js/bootstrap.min.js"></script>
<script>
    $(document).ready(function() {

        $('#generateChartBtn').on('click', function() {
            
            $("#msg").html("");
            $("#msg").removeClass("alert alert-danger");
            
            var startMonth = $('#start_month').val();
            var endMonth = $('#end_month').val();
            
            if (startMonth == ""){
        
                $("#msg").html("Start Month Cannot Be Empty!");
                $("#msg").addClass("alert alert-danger");
                return false;
            }
            if (endMonth == ""){

                $("#msg").html("End Month Cannot Be Empty!");
                $("#msg").addClass("alert alert-danger");
                return false;
            }
            
            if (startMonth > endMonth) {
                
                $("#msg").html("End month should be greater than start month");
                $("#msg").addClass("alert alert-danger");
                return false;
            }
            
            var url = "../controller/service_detail_controller.php?status=service_cost_trend";
            
            $.post(url,{ startMonth: startMonth,endMonth: endMonth},function(data){
                
                $('#trend').empty();
                
                if (data.error) {
                    
                    $("#msg").html(data.error);
                    $("#msg").addClass("alert alert-danger");
                    return false;
                }
                
                if (data.months && data.months.length > 0) {
                    
                    var chartData = {
                        x: data.months,
                        y: data.costs,
                        mode: 'lines+markers',
                        type: 'scatter',
                        name: 'Maintenance Cost',
                        line: { color: '#17A2B8', width: 3 },
                        marker: { color: '#17A2B8', size: 8 },
                        hovertemplate: '<b>Cost</b>:LKR %{y:,.2f}<extra></extra>'
                    };
                    
                    var layout = {
                        title: { text:'Monthly Maintenance Cost Trend'},
                        xaxis: {
                            title: { text:'Month'},
                            type:'category'
                        },
                        yaxis: {
                            title: {text:'Total Cost (LKR)'},
                            separatethousands: true,
                            rangemode:'tozero'
                        },
                        margin: { t: 50, b: 100, l: 80, r: 40 }
                    };

                    Plotly.newPlot('trend', [chartData], layout);
                } 
                else{
                        
                    $('#trend').html('<div class="alert alert-info text-center">No service cost data available for the selected period.</div>');
                }
            
            });
            
        });
    });
</script>
</html>