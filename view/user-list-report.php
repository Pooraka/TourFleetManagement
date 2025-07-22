<?php

include_once '../commons/session.php';
include_once '../model/user_model.php';

//get user information from session
$userSession=$_SESSION["user"];


?>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Management</title>
    <?php include_once "../includes/bootstrap_css_includes.php"?>
</head>
<body>
    <div class="container">
        <?php $pageName="User Management - User List Report" ?>
        <?php include_once "../includes/header_row_includes.php";?>
        <div class="col-md-3">
            <ul class="list-group">
                <a href="dashboard.php" class="list-group-item">
                    <span class="fa-solid fa-house"></span> &nbsp;
                    Back To Dashboard
                </a>
                <a href="add-user.php" class="list-group-item" style="display:<?php echo checkPermissions(148); ?>">
                    <span class="fa-solid fa-plus"></span> &nbsp;
                    Add User
                </a>
                <a href="view-users.php" class="list-group-item" style="display:<?php echo checkPermissions(54); ?>">
                    <span class="fa-solid fa-users-cog"></span> &nbsp;
                    View Users
                </a>
                <a href="user-list-report.php" target="_blank" class="list-group-item" style="display:<?php echo checkPermissions(55); ?>">
                    <span class="fa-solid fa-address-book"></span> &nbsp;
                    Generate User List
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
                <div class="col-md-3">
                    <label class="control-label">Select User Status</label>
                </div>
                <div class="col-md-3">
                    <select class="form-control" id="userStatus" name="userStatus">
                        <option value="">All</option>
                        <option value="1">Active</option>
                        <option value="0">De-activated</option>
                        <option value="-1">Removed</option>                     
                    </select>
                </div>
                <div class="col-md-offset-4 col-md-2 text-right">
                    <button type="button" class="btn btn-primary" id="generateBtn" onclick="generateReport()">Generate</button>
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

    var userStatus = $("#userStatus").val();
    
    var pdfUrl = "../reports/user-list-report-pdf.php?userStatus="+userStatus;
    
    $('#pdfViewer').attr('data', pdfUrl);
    $('#pdfContainer').show();
    
}
    
</script>
</html>