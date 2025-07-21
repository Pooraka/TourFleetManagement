<?php

include_once '../commons/session.php';
include_once '../model/customer_model.php';

//get user information from session
$userSession=$_SESSION["user"];

$customerObj = new Customer();

$activeCustomerCount = $customerObj->getActiveCustomerCount();

$customerCountWithToursWithinLast7Days = $customerObj->getCustomerCountWithToursWithinLast7Days();
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
                <a href="dashboard.php" class="list-group-item">
                    <span class="fa-solid fa-house"></span> &nbsp;
                    Back To Dashboard
                </a>
                <a href="add-customer.php" class="list-group-item" style="display:<?php echo checkPermissions(49); ?>">
                    <span class="fa-solid fa-plus"></span> &nbsp;
                    Add Customer
                </a>
                <a href="view-customers.php" class="list-group-item" style="display:<?php echo checkPermissions(50); ?>">
                    <span class="fa-solid fa-users"></span> &nbsp;
                    View Customers
                </a>
                <a href="revenue-by-customers.php" class="list-group-item" style="display:<?php echo checkPermissions(147); ?>">
                    <span class="fa-solid fa-file-invoice-dollar"></span> &nbsp;
                    Revenue By Customers
                </a>
            </ul>
        </div>
        <div class="col-md-9">
            <div class="col-md-6">
                <div class="panel panel-info" style="height:180px">
                    <div class="panel-heading">
                        <p align="center">No of Customers</p>
                    </div>
                    <div class="panel-body">
                        <h1 class="h1" align="center"><?php echo $activeCustomerCount;?></h1>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="panel panel-success" style="height:180px">
                    <div class="panel-heading">
                        <p align="center">No of Customers Had Tours Within Last 7 Days</p>
                    </div>
                    <div class="panel-body">
                        <h1 class="h1" align="center"><?php echo $customerCountWithToursWithinLast7Days;?></h1>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
<script src="../js/jquery-3.7.1.js"></script>
</html>