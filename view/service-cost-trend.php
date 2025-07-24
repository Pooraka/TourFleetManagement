<?php

include_once '../commons/session.php';
include_once '../model/service_station_model.php';

//get user information from session
$userSession=$_SESSION["user"];


$serviceStationObj = new ServiceStation();

$serviceStationResult = $serviceStationObj->getAllServiceStationsIncludingRemoved()

?>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Finance</title>
    <?php include_once "../includes/bootstrap_css_includes.php"?>
</head>
<body>
    <div class="container">
        <?php $pageName="Finance - Service Cost Trend" ?>
        <?php include_once "../includes/header_row_includes.php";?>
        <div class="col-md-3">
            <?php include_once "../includes/finance_functions.php"; ?>
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
                <div class="col-md-3">
                    <label class="control-label">Date From:</label>
                </div>
                <div class="col-md-3">
                    <input type="date" class="form-control" id="dateFrom" max="<?php echo date('Y-m-d'); ?>">
                </div>
                <div class="col-md-3">
                    <label class="control-label">To Date:</label>
                </div>
                <div class="col-md-3">
                    <input type="date" class="form-control" id="dateTo" max="<?php echo date('Y-m-d'); ?>">
                </div>
                
            </div>
            <div class="row">
                &nbsp;
            </div>
            <div class="row">
                <div class="col-md-3">
                    <label class="control-label">Select Service Station</label>
                </div>
                <div class="col-md-3">
                    <select id="service_station_id" class="form-control">
                        <option value="" selected>All Service Stations</option>
                        <?php while($serviceStationRow = $serviceStationResult->fetch_assoc()){?>
                        <option value="<?php echo $serviceStationRow['service_station_id'];?>"><?php echo $serviceStationRow['service_station_name'];?></option>
                        <?php }?>
                    </select>
                </div>
                <div class="col-md-offset-3 col-md-3 text-right">
                    <button type="button" class="btn btn-primary" id="generateChartBtn">Generate</button>
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
            
            var dateFrom = $('#dateFrom').val();
            var dateTo = $('#dateTo').val();
            var serviceStationId = $('#service_station_id').val();
            
            if (dateFrom === "" || dateTo === "") {
                $("#msg").addClass("alert alert-danger");
                $("#msg").html("<b>Please select both dates.</b>");
                return;
            }

            if (dateFrom > dateTo) {

                $("#msg").html("End date should be equal to or greater than start date");
                $("#msg").addClass("alert alert-danger");
                return false;
            }

            var url = "../controller/service_detail_controller.php?status=service_cost_trend";

            $.post(url,{ dateFrom: dateFrom, dateTo: dateTo, serviceStationId: serviceStationId },function(data){

                $('#trend').empty();
                
                if (data.error) {
                    
                    $("#msg").html(data.error);
                    $("#msg").addClass("alert alert-danger");
                    return false;
                }

                if (data.dates && data.dates.length > 0) {

                    var chartData = {
                        x: data.dates,
                        y: data.costs,
                        mode: 'lines+markers',
                        type: 'scatter',
                        name: 'Service Cost',
                        line: { color: '#17A2B8', width: 3 },
                        marker: { color: '#17A2B8', size: 8 },
                        hovertemplate: '<b>Cost</b>:LKR %{y:,.2f}<extra></extra>'
                    };

                    var layout = {
                        title: { text:'Service Cost Trend'},
                        xaxis: {
                            title: { text:'Date'},
                            type:'category'
                        },
                        yaxis: {
                            title: {text:'Total Cost (LKR)'},
                            separatethousands: true
                            //rangemode:'tozero'
                        },
                        margin: { t: 50, b: 100, l: 80, r: 40 }
                    };

                    Plotly.newPlot('trend', [chartData], layout);
                }
                else {
                    $('#trend').html('<div class="alert alert-info text-center">No service cost data available for the selected parameters.</div>');
                }
            
            });
        });
    });
</script>
</html>