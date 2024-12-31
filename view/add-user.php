<?php

include_once '../commons/session.php';
include_once '../model/module_model.php';

//get user information from session
$userRow=$_SESSION["user"];

$moduleObj = new Module();

$moduleResult = $moduleObj->getAllModules();

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
    <?php $pageName="User Management" ?>
    <?php include_once "../includes/header_row_includes.php";?>
        <div class="col-md-3">
            <ul class="list-group">
                <a href="add-user.php" class="list-group-item">
                    <span class="glyphicon glyphicon-plus"></span> &nbsp;
                    Add user
                </a>
                <a href="view-users.php" class="list-group-item">
                    <span class="glyphicon glyphicon-search"></span> &nbsp;
                    View users
                </a>
                <a href="generate-user-reports.php" class="list-group-item">
                    <span class="glyphicon glyphicon-book"></span> &nbsp;
                    Generate user reports
                </a>
            </ul>
        </div>
        <div class="col-md-9">
            <div class="row">
                <div class="col-md-3">
                    <label class="control-label">First Name</label>
                </div>
                <div class="col-md-3">
                    <input type="text" class="form-control" name="fname" id="fname"/>
                </div>
                <div class="col-md-3">
                    <label class="control-label">Last Name</label>
                </div>
                <div class="col-md-3">
                    <input type="text" class="form-control" name="lname" id="lname"/>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    &nbsp;
                </div>
            </div>
            <div class="row">
                <div class="col-md-3">
                    <label class="control-label">Email</label>
                </div>
                <div class="col-md-3">
                    <input type="email" class="form-control" name="email" id="email"/>
                </div>
                <div class="col-md-3">
                    <label class="control-label">Date of Birth</label>
                </div>
                <div class="col-md-3">
                    <input type="date" class="form-control" name="dob" id="dob"/>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    &nbsp;
                </div>
            </div>
            <div class="row">
                <div class="col-md-3">
                    <label class="control-label">NIC</label>
                </div>
                <div class="col-md-3">
                    <input type="text" class="form-control" name="nic" id="nic"/>
                </div>
                <div class="col-md-3">
                    <label class="control-label">Image</label>
                </div>
                <div class="col-md-3">
                    <input type="file" class="form-control" name="user_image" id="user_image"/>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    &nbsp;
                </div>
            </div>
            <div class="row">
                <div class="col-md-3">
                    <label class="control-label">Contact No 1</label>
                </div>
                <div class="col-md-3">
                    <input type="text" class="form-control" name="cno1" id="cno1"/>
                </div>
                <div class="col-md-3">
                    <label class="control-label">Contact No 2</label>
                </div>
                <div class="col-md-3">
                    <input type="text" class="form-control" name="cno2" id="cno2"/>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    &nbsp;
                </div>
            </div>
            <div class="row">
                <div class="col-md-3">
                    <label class="control-label">User Role</label>
                </div>
                <div class="col-md-3">
                    <input type="text" class="form-control" name="userrole" id="userrole"/>
                </div>
            </div>
        </div>
    </div>
    <script src="../js/jquery-3.7.1.js"></script>
</body>
</html>