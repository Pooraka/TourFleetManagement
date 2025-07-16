<?php

include_once '../commons/session.php';
include_once '../model/bus_model.php';

//get user information from session
$userSession=$_SESSION["user"];

$busObj = new Bus();

$categoryResult = $busObj->getAllBusCategories();
?>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bus Management</title>
    <?php include_once "../includes/bootstrap_css_includes.php"?>
</head>
<body>
    <div class="container">
        <?php $pageName="Bus Management" ?>
        <?php include_once "../includes/header_row_includes.php";?>
        <div class="col-md-3">
            <ul class="list-group">
                <a href="add-bus.php" class="list-group-item" style="display:<?php echo checkPermissions(108); ?>">
                    <span class="glyphicon glyphicon-plus"></span> &nbsp;
                    Add Bus
                </a>
                <a href="view-buses.php" class="list-group-item" style="display:<?php echo checkPermissions(109); ?>">
                    <span class="glyphicon glyphicon-search"></span> &nbsp;
                    View Buses
                </a>
                <a href="bus-fleet-filtered-report.php" class="list-group-item" target="_blank" style="display:<?php echo checkPermissions(113); ?>">
                    <span class="glyphicon glyphicon-book"></span> &nbsp;
                    Bus Fleet Details Report
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
                &nbsp;
            </div>
            <div class="row">
                <div class="col-md-2">
                    <label class="control-label">Select Category</label>
                </div>
                <div class="col-md-3">
                    <select name="category_id" id="category_id"class="form-control">
                        <option value="all" selected>All</option>
                        <?php while($categoryRow = $categoryResult->fetch_assoc()){?>
                        <option value="<?php echo $categoryRow['category_id'];?>"><?php echo $categoryRow['category_name'];?></option>
                        <?php }?>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="control-label">Select Status</label>
                </div>
                <div class="col-md-3">
                    <select name="status" id="status" class="form-control">
                        <option value="all" selected>All</option>
                        <option value="1" >Operational</option>
                        <option value="2" >Service Due</option>
                        <option value="3" >In Service</option>
                        <option value="4" >Inspection Failed</option>
                        
                    </select>
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
    function generateReport(){
    
    var categoryId = $('#category_id').val();
    var status = $('#status').val();
    
    if (categoryId == ""){
        
            $("#msg").html("Category Cannot Be Empty!");
            $("#msg").addClass("alert alert-danger");
            return false;
        }
    if (status == ""){
        
            $("#msg").html("Status Cannot Be Empty!");
            $("#msg").addClass("alert alert-danger");
            return false;
        }
    
    var pdfUrl = "../reports/bus-fleet-report-pdf.php?category_id="+categoryId+"&status="+status;
    
    $('#pdfViewer').attr('data', pdfUrl);
    $('#pdfContainer').show();
    
}
</script>
</html>