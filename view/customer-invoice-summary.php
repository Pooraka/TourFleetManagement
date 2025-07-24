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
        <?php $pageName="Finance Management - Customer Invoice Summary" ?>
        <?php include_once "../includes/header_row_includes.php";?>
        <div class="col-md-3">
            <?php include_once "../includes/finance_functions.php"; ?>
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
                <div class="col-md-3">
                    <label class="control-label">Invoice Date From:</label>
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
                    <label class="control-label">Invoice Status</label>
                </div>
                <div class="col-md-3">
                    <select id="invoice_status" class="form-control">
                        <option value="" selected>All Statuses</option>
                        <option value="1">Advance Paid</option>
                        <option value="4">Completed</option>
                        <option value="-1">Cancelled</option>
                    </select>
                </div>
                <div class="col-md-offset-3 col-md-3 text-right">
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
    
    var dateFrom = $('#dateFrom').val();
    var dateTo = $('#dateTo').val();
    
    var invoiceStatus = $('#invoice_status').val();
    
    if(dateFrom !="" || dateTo!=""){
        
        if(dateFrom ==""){
            $("#msg").html("Both Dates Must Be Selected To Get The Report For A Period");
            $("#msg").addClass("alert alert-danger");
            return false;
        }
        if(dateTo ==""){
            $("#msg").html("Both Dates Must Be Selected To Get The Report For A Period");
            $("#msg").addClass("alert alert-danger");
            return false;
        }
        
        if(dateFrom>dateTo){
            $("#msg").html("'From' Date Cannot Be Greater Than 'To' Date");
            $("#msg").addClass("alert alert-danger");
            return false;
        }
    }
    
    var pdfUrl = "../reports/customer-invoice-summary-report-pdf.php?dateFrom="+dateFrom+"&dateTo="+dateTo+"&invoiceStatus="+invoiceStatus;
    
    $('#pdfViewer').attr('data', pdfUrl);
    $('#pdfContainer').show();
    
}
    
</script>
</html>