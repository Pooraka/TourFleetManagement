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
            <ul class="list-group">
                <a href="pending-service-payments.php" class="list-group-item">
                    <span class="glyphicon glyphicon-search"></span> &nbsp;
                    Pending Service Payments
                </a>
                <a href="pending-supplier-payments.php" class="list-group-item">
                    <span class="glyphicon glyphicon-search"></span> &nbsp;
                    Pending Supplier Payments
                </a>
                <a href="verify-customer-income.php" class="list-group-item">
                    <span class="glyphicon glyphicon-search"></span> &nbsp;
                    Verify Customer Income
                </a>
                <a href="supplier-payment-monthly-chart.php" class="list-group-item">
                    <span class="fa fa-solid fa-chart-bar"></span> &nbsp;
                    Supplier Monthly Pmt Chart
                </a>
                <a href="customer-invoice-summary.php" class="list-group-item">
                    <span class="fa fa-solid fa-chart-bar"></span> &nbsp;
                    Customer Invoice Summary
                </a>
                <a href="service-cost-trend.php" class="list-group-item">
                    <span class="fas fa-chart-line"></span> &nbsp;
                    Service Cost trend
                </a>
            </ul>
        </div>
        <div class="col-md-9">
            
        </div>
    </div>
</body>
</html>