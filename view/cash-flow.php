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
                    <label class="control-label">Transaction Type</label>
                </div>
                <div class="col-md-3">
                    <select id="txn_type" class="form-control">
                        <option value="" selected>All Transaction Types</option>
                        <option value="1">Service Payments</option>
                        <option value="2">Supplier Payments</option>
                        <option value="3">Tour Income</option>
                    </select>
                </div>
                <div class="col-md-offset-3 col-md-3 text-right">
                    <button type="button" class="btn btn-primary" id="generateReportLink" onclick="generateReport()">Generate</button>
                </div>
            </div>
            <div class="row">
                &nbsp;
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div id="pdfContainer">
                        <object id="pdfViewer" data="" type="application/pdf" width="100%" height="1000px">

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
    
    $("#msg").html("");
    $("#msg").removeClass("alert alert-danger");
    
    var dateFrom = $('#dateFrom').val();
    var dateTo = $('#dateTo').val();
    
    var txnType = $('#txn_type').val();
    
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
    
    var pdfUrl = "../reports/cash-flow-pdf.php?dateFrom="+dateFrom+"&dateTo="+dateTo+"&txnType="+txnType;
    
    $('#pdfViewer').attr('data', pdfUrl);
    $('#pdfContainer').show();
    
}
    
</script>
</html>