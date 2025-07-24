<?php

include_once '../commons/session.php';
include_once '../model/user_model.php';

//get user information from session
$userSession=$_SESSION["user"];

$userObj = new User();
$userResult = $userObj->getAllUsersIncludingRemoved();
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
        <?php $pageName="Bus Maintenance - Inspection Result Report" ?>
        <?php include_once "../includes/header_row_includes.php";?>
        <div class="col-md-3">
            <?php include_once "../includes/bus_maintenance_functions.php"; ?>
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
                <div class="col-md-2">
                    <label class="control-label">Inspection Result</label>
                </div>
                <div class="col-md-3">
                    <select id="resultId" class="form-control">
                        <option value="" selected>All</option>
                        <option value="1">Passed</option>
                        <option value="0">Failed</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="control-label">Inspected User</label>
                </div>
                <div class="col-md-3">
                    <select id="inspectedId" class="form-control">
                        <option value="" selected>All</option>
                        <?php while($userRow = $userResult->fetch_assoc()){ ?>
                        <option value="<?php echo $userRow["user_id"]?>"><?php echo substr($userRow["user_fname"],0,1).". ".$userRow["user_lname"];?></option>
                        <?php } ?>
                    </select>
                </div>
                <div class="col-md-2 text-right">
                    <button type="button" class="btn btn-primary" id="generateBtn" onclick="generateReport()">Generate</button>
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

    var dateFrom = $("#dateFrom").val();
    var dateTo = $("#dateTo").val();
    var resultId = $("#resultId").val();
    var inspectedId = $("#inspectedId").val();

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

    var pdfUrl = "../reports/inspection-status-report-pdf.php?dateFrom="+dateFrom+"&dateTo="+dateTo+"&resultId="+resultId+"&inspectedId="+inspectedId;

    $('#pdfViewer').attr('data', pdfUrl);
    $('#pdfContainer').show();
    
}
    
</script>
</html>