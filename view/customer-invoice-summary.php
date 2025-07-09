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
        <?php $pageName="Finance Management" ?>
        <?php include_once "../includes/header_row_includes.php";?>
        <div class="col-md-3">
            <ul class="list-group">
                <a href="pending-service-payments.php" class="list-group-item">
                    <span class="glyphicon glyphicon-search"></span> &nbsp;
                    Pending Service Payments
                </a>
                <a href="pending-supplier-payments.php" class="list-group-item">
                    <span class="glyphicon glyphicon-search"></span> &nbsp;
                    Pending Supplier Payments
                </a>
                <a href="verify-customer-income.php" class="list-group-item">
                    <span class="glyphicon glyphicon-search"></span> &nbsp;
                    Verify Customer Income
                </a>
                <a href="supplier-payment-monthly-chart.php" class="list-group-item">
                    <span class="fa fa-solid fa-chart-bar"></span> &nbsp;
                    Supplier Monthly Pmt Chart
                </a>
                <a href="customer-invoice-summary.php" class="list-group-item">
                    <span class="fa fa-solid fa-chart-bar"></span> &nbsp;
                    Customer Invoice Summary
                </a>
            </ul>
        </div>
        <div class="col-md-9">
            <div class="row">
                <div class="col-md-6 col-md-offset-3" id="msg">
                    <?php
                    if (isset($_GET["msg"]) && isset($_GET["success"]) && $_GET["success"] == true) {

                        $msg = base64_decode($_GET["msg"]);
                        ?>
                        <div class="row">
                            <div class="alert alert-success" style="text-align:center">
                                <?php echo $msg; ?>
                            </div>
                        </div>
                        <?php
                    } elseif (isset($_GET["msg"])) {

                        $msg = base64_decode($_GET["msg"]);
                        ?>
                        <div class="row">
                            <div class="alert alert-danger" style="text-align:center">
                                <?php echo $msg; ?>
                            </div>
                        </div>
                        <?php
                    }
                    ?>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    Select The Invoice Date Period
                </div>
            </div>
            <div class="row">
                &nbsp;
            </div>
            <div class="row">
                <div class="col-md-2">
                    <label class="control-label">Start Date:</label>
                </div>
                <div class="col-md-3">
                    <input type="date" class="form-control" id="start_date" name="start_date">
                </div>
                <div class="col-md-2">
                    <label class="control-label">End Date:</label>
                </div>
                <div class="col-md-3">
                    <input type="date" class="form-control" id="end_date" name="end_date">
                </div>
                <div class="col-md-2">
                    <a href="#" class="btn btn-primary" id="generateReportLink" onclick="generateReport()">
                        Generate
                    </a>
                </div>
            </div>
            <div class="row">
                &nbsp;
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div id="pdfContainer">
                        <object id="pdfViewer" data="" type="application/pdf" width="100%" height="500px">

                        </object>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
<script src="../js/jquery-3.7.1.js"></script>
<script>
//$(document).ready(function() {
//    
////    $('#pdfContainer').hide();   
//    
//});

function generateReport(){
    
    var startDate = $('#start_date').val();
    var endDate = $('#end_date').val();
    
    if (startDate == ""){
        
            $("#msg").html("Start Date Cannot Be Empty!");
            $("#msg").addClass("alert alert-danger");
            return false;
        }
    if (endDate == ""){
        
            $("#msg").html("End Date Cannot Be Empty!");
            $("#msg").addClass("alert alert-danger");
            return false;
        }
    
    var pdfUrl = "../reports/customer-invoice-summary-report-pdf.php?start_date="+startDate+"&end_date="+endDate;
    
    $('#pdfViewer').attr('data', pdfUrl);
    $('#pdfContainer').show();
    
}
    
</script>
</html>