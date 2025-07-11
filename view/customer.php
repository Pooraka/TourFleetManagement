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
    <title>Customer</title>
    <?php include_once "../includes/bootstrap_css_includes.php"?>
</head>
<body>
    <div class="container">
        <?php $pageName="Customer" ?>
        <?php include_once "../includes/header_row_includes.php";?>
        <div class="col-md-3">
            <ul class="list-group">
                <a href="add-customer.php" class="list-group-item" style="display:<?php echo checkPermissions(49); ?>">
                    <span class="glyphicon glyphicon-plus"></span> &nbsp;
                    Add Customer
                </a>
                <a href="view-customers.php" class="list-group-item" style="display:<?php echo checkPermissions(50); ?>">
                    <span class="glyphicon glyphicon-search"></span> &nbsp;
                    View Customers
                </a>
                <a href="revenue-by-customers.php" class="list-group-item" style="display:<?php echo checkPermissions(147); ?>">
                    <span class="glyphicon glyphicon-search"></span> &nbsp;
                    Revenue By Customers
                </a>
            </ul>
        </div>
        <div class="col-md-9">

        </div>
    </div>
</body>
<script src="../js/jquery-3.7.1.js"></script>
</html>