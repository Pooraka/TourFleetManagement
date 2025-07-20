<?php

include_once '../commons/session.php';
include_once '../model/supplier_model.php';

//get user information from session
$userSession=$_SESSION["user"];

$supplierObj = new Supplier();

$supplierResult = $supplierObj->getAllSuppliersIncludingRemoved();

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
        <?php $pageName="Finance Management- Supplier Cost Trend" ?>
        <?php include_once "../includes/header_row_includes.php";?>
        <div class="col-md-3">
            <ul class="list-group">
                <a href="pending-service-payments.php" class="list-group-item" style="display:<?php echo checkPermissions(134); ?>">
                    <span class="fa-solid fa-file-invoice-dollar"></span> &nbsp;
                    Pending Service Payments
                </a>
                <a href="pending-supplier-payments.php" class="list-group-item" style="display:<?php echo checkPermissions(136); ?>">
                    <span class="fa-solid fa-file-invoice-dollar"></span> &nbsp;
                    Pending Supplier Payments
                </a>
                <a href="customer-invoice-summary.php" class="list-group-item" style="display:<?php echo checkPermissions(145); ?>">
                    <span class="fa-solid fa-file-lines"></span> &nbsp;
                    Customer Invoice Summary
                </a>
                <a href="cash-flow.php" class="list-group-item" style="display:<?php echo checkPermissions(158); ?>">
                    <span class="fa-solid fa-piggy-bank"></span> &nbsp;
                    Cash Flow
                </a>
                <a href="service-cost-trend.php" class="list-group-item" style="display:<?php echo checkPermissions(146); ?>">
                    <span class="fa-solid fa-arrow-trend-up"></span> &nbsp;
                    Service Cost Trend
                </a>
                <a href="supplier-cost-trend.php" class="list-group-item" style="display:<?php echo checkPermissions(146); ?>">
                    <span class="fa-solid fa-arrow-trend-up"></span> &nbsp;
                    Supplier Cost Trend
                </a>
                <a href="tour-income-trend.php" class="list-group-item" style="display:<?php echo checkPermissions(155); ?>">
                    <span class="fa-solid fa-chart-line"></span> &nbsp;
                    Tour Income Trend
                </a>
                <a href="supplier-payment-monthly-chart.php" class="list-group-item" style="display:<?php echo checkPermissions(144); ?>">
                    <span class="fa-solid fa-chart-column"></span> &nbsp;
                    Supplier Monthly Pmt Chart
                </a>
                <a href="service-payment-monthly-chart.php" class="list-group-item" style="display:<?php echo checkPermissions(144); ?>">
                    <span class="fa-solid fa-chart-column"></span> &nbsp;
                    Service Monthly Pmt Chart
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
                    <label class="control-label">Select Supplier</label>
                </div>
                <div class="col-md-3">
                    <select id="supplier_id" class="form-control">
                        <option value="" selected>All Suppliers</option>
                        <?php while($supplierRow = $supplierResult->fetch_assoc()){?>
                        <option value="<?php echo $supplierRow['supplier_id'];?>"><?php echo $supplierRow['supplier_name'];?></option>
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
            var supplierId = $('#supplier_id').val();

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
            
            var url = "../controller/purchase_order_controller.php?status=supplier_cost_trend";

            $.post(url,{ dateFrom: dateFrom, dateTo: dateTo,supplierId:supplierId},function(data){

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
                        name: 'Supplier Cost',
                        line: { color: '#17A2B8', width: 3 },
                        marker: { color: '#17A2B8', size: 8 },
                        hovertemplate: '<b>Cost</b>:LKR %{y:,.2f}<extra></extra>'
                    }

                    var layout = {
                        title: { text:'Supplier Cost Trend'},
                        xaxis: {
                            title: { text:'Date'},
                            type:'category'
                        },
                        yaxis: {
                            title: {text:'Total Cost (LKR)'},
                            separatethousands: true,
                            //rangemode:'tozero'
                        },
                        margin: { t: 50, b: 100, l: 80, r: 40 }
                    };

                    Plotly.newPlot('trend', [chartData], layout);
                }
                else {
                    $('#trend').html('<div class="alert alert-info text-center">No supplier cost data available for the selected parameters.</div>');
                }
            
            });
            
        });
    });
</script>
</html>