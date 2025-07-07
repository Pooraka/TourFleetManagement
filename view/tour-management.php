<?php

include_once '../commons/session.php';


//get user information from session
$userSession=$_SESSION["user"];
?>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tour Management</title>
    <?php include_once "../includes/bootstrap_css_includes.php"?>
</head>
<body>
    <div class="container">
        <?php $pageName="Tour Management" ?>
        <?php include_once "../includes/header_row_includes.php";?>
        <div class="col-md-3">
            <ul class="list-group">
                <a href="add-tour.php" class="list-group-item">
                    <span class="glyphicon glyphicon-plus"></span> &nbsp;
                    Add Tour
                </a>
                <a href="pending-tours.php" class="list-group-item">
                    <span class="glyphicon glyphicon-search"></span> &nbsp;
                    Pending Tours
                </a>
                <a href="inspection-failed.php" class="list-group-item">
                    <span class="glyphicon glyphicon-search"></span> &nbsp;
                    Pre-Tour Failed Inspections
                </a>
            </ul>
        </div>
        <div class="col-md-9">

        </div>
    </div>
</body>
<script src="../js/jquery-3.7.1.js"></script>
</html>