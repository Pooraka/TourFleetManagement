<?php

include_once '../commons/session.php';
include_once '../model/module_model.php';

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
        <?php $pageName="User Management" ?>
        <?php include_once "../includes/header_row_includes.php";?>
        <div class="col-md-3">
            <ul class="list-group">
                <a href="add-user.php" class="list-group-item" style="display:<?php echo checkPermissions(46); ?>">
                    <span class="glyphicon glyphicon-plus"></span> &nbsp;
                    Add User
                </a>
                <a href="view-users.php" class="list-group-item">
                    <span class="glyphicon glyphicon-search"></span> &nbsp;
                    View Users
                </a>
                <a href="user-list-report.php" target="_blank" class="list-group-item">
                    <span class="glyphicon glyphicon-book"></span> &nbsp;
                    Generate User List
                </a>
            </ul>
        </div>
        <div class="col-md-9">
            <div class="col-md-6">
                <div class="panel panel-info" style="height:180px">
                    <div class="panel-heading">
                        <p align="center">No of Active Users</p>
                    </div>
                    <div class="panel-body">
                        <h1 class="h1" align="center">5</h1>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="panel panel-info" style="height:180px">
                    <div class="panel-heading">
                        <p align="center">No of De-active Users</p>
                    </div>
                    <div class="panel-body">
                        <h1 class="h1" align="center">3</h1>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
<script src="../js/jquery-3.7.1.js"></script>
</html>