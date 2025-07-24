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
        <?php $pageName="Finance - Tour Income Trend" ?>
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
                <div class="col-md-2">
                    <label class="control-label">Date From:</label>
                </div>
                <div class="col-md-3">
                    <input type="date" class="form-control" id="dateFrom" max="<?php echo date('Y-m-d'); ?>">
                </div>
                <div class="col-md-2">
                    <label class="control-label">To Date:</label>
                </div>
                <div class="col-md-3">
                    <input type="date" class="form-control" id="dateTo" max="<?php echo date('Y-m-d'); ?>">
                </div>
                <div class="col-md-2">
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
            
            var url = "../controller/finance_controller.php?status=tour_income_trend";

            $.post(url,{dateFrom: dateFrom, dateTo: dateTo},function(data){

                $('#trend').empty();
                
                if (data.error) {
                    
                    $("#msg").html(data.error);
                    $("#msg").addClass("alert alert-danger");
                    return false;
                }
                
                if (data.dates && data.dates.length > 0) {
                    
                    var chartData = {
                        x: data.dates,
                        y: data.income,
                        mode: 'lines+markers',
                        type: 'scatter',
                        name: 'Tour Income',
                        line: { color: '#17b875ff', width: 3 },
                        marker: { color: '#17b875ff', size: 8 },
                        hovertemplate: '<b>Income</b>:LKR %{y:,.2f}<extra></extra>'
                    };
                    
                    var layout = {
                        title: { text:'Tour Income Trend'},
                        xaxis: {
                            title: { text:'Date'},
                            type:'category'
                        },
                        yaxis: {
                            title: {text:'Total Income (LKR)'},
                            separatethousands: true,
                            //rangemode:'tozero'
                        },
                        margin: { t: 50, b: 100, l: 80, r: 40 }
                    };

                    Plotly.newPlot('trend', [chartData], layout);
                } 
                else{
                        
                    $('#trend').html('<div class="alert alert-info text-center">No tour income data available for the selected parameters.</div>');
                }
            
            });
            
        });
    });
</script>
</html>