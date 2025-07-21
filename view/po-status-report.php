<?php

include_once '../commons/session.php';
include_once '../model/sparepart_model.php';


//get user information from session
$userSession=$_SESSION["user"];

$sparePartObj = new SparePart();
$sparePartResult = $sparePartObj->getAllSparePartsIncludingRemoved();
?>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Purchasing</title>
    <?php include_once "../includes/bootstrap_css_includes.php"?>
</head>
<body>
    <div class="container">
        <?php $pageName="Purchasing - PO Status Report" ?>
        <?php include_once "../includes/header_row_includes.php";?>
        <div class="col-md-3">
            <ul class="list-group">
                <a href="awarded-bids.php" class="list-group-item">
                    <span class="glyphicon glyphicon-plus"></span> &nbsp;
                    Awarded Bids
                </a>
                <a href="pending-purchase-orders.php" class="list-group-item">
                    <span class="glyphicon glyphicon-plus"></span> &nbsp;
                    Pending PO
                </a>
                <a href="po-status-report.php" class="list-group-item">
                    <span class="glyphicon glyphicon-plus"></span> &nbsp;
                    PO Status Report
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
                <div class="col-md-8">
                    <label class="control-label">Select Purchase Order Date Range To Filter (Keep Blank for All)</label>
                </div>
                <div class="col-md-4 text-right">
                    <button type="button" class="btn btn-primary" id="generateBtn" onclick="generateReport()">Generate</button>
                </div>
            </div>
            <div class="row">
                &nbsp;
            </div>
            <div class="row">
                <div class="col-md-3">
                    <label class="control-label">From Date</label>
                </div>
                <div class="col-md-3">
                    <input type="date" class="form-control" id="dateFrom" name="dateFrom" max="<?php echo date("Y-m-d"); ?>"/>
                </div>
                <div class="col-md-3">
                    <label class="control-label">To Date</label>
                </div>
                <div class="col-md-3">
                    <input type="date" class="form-control" id="dateTo" name="dateTo" max="<?php echo date("Y-m-d"); ?>"/>
                </div>
            </div>
            <div class="row">
                &nbsp;
            </div>
            <div class="row">
                <div class="col-md-3">
                    <label class="control-label">Select PO Status</label>
                </div>
                <div class="col-md-3">
                    <select class="form-control" id="poStatus" name="poStatus">
                        <option value="">All</option>
                        <option value="1">Pending Approval</option>
                        <option value="2">Approved</option>
                        <option value="4">Partially Received</option>
                        <option value="5">All Parts Received</option>
                        <option value="6">Paid</option>
                        <option value="-1">Rejected</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="control-label">Select Spare Part</label>
                </div>
                <div class="col-md-3">
                    <select class="form-control" id="sparePart" name="sparePart">
                        <option value="">All</option>
                        <?php while($sparePartRow = $sparePartResult->fetch_assoc()) { ?>
                            <option value="<?php echo $sparePartRow["part_id"];?>"><?php echo $sparePartRow["part_name"];?></option>
                        <?php } ?>
                    </select>
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

    $("#msg").html("");
    $("#msg").removeClass("alert alert-danger");
    
    var dateFrom = $('#dateFrom').val();
    var dateTo = $('#dateTo').val();
    var poStatus = $('#poStatus').val();
    var partId = $('#sparePart').val();

    if(dateFrom!="" || dateTo!=""){

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

    var pdfUrl = "../reports/po-status-pdf.php?dateFrom="+dateFrom+"&dateTo="+dateTo+"&poStatus="+poStatus+"&partId="+partId;

    $('#pdfViewer').attr('data', pdfUrl);
    $('#pdfContainer').show();
    
}
    
</script>
</html>