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
                <a href="pending-service-payments.php" class="list-group-item" style="display:<?php echo checkPermissions(134); ?>">
                    <span class="fa-solid fa-file-invoice-dollar"></span> &nbsp;
                    Pending Service Payments
                </a>
                <a href="pending-supplier-payments.php" class="list-group-item" style="display:<?php echo checkPermissions(136); ?>">
                    <span class="fa-solid fa-file-invoice-dollar"></span> &nbsp;
                    Pending Supplier Payments
                </a>
                <a href="customer-invoice-summary.php" class="list-group-item" style="display:<?php echo checkPermissions(145); ?>">
                    <span class="fa-solid fa-file-lines"></span> &nbsp;
                    Customer Invoice Summary
                </a>
                <a href="cash-flow.php" class="list-group-item" style="display:<?php echo checkPermissions(158); ?>">
                    <span class="fa-solid fa-piggy-bank"></span> &nbsp;
                    Cash Flow
                </a>
                <a href="service-cost-trend.php" class="list-group-item" style="display:<?php echo checkPermissions(146); ?>">
                    <span class="fa-solid fa-arrow-trend-up"></span> &nbsp;
                    Service Cost Trend
                </a>
                <a href="supplier-cost-trend.php" class="list-group-item" style="display:<?php echo checkPermissions(159); ?>">
                    <span class="fa-solid fa-arrow-trend-up"></span> &nbsp;
                    Supplier Cost Trend
                </a>
                <a href="tour-income-trend.php" class="list-group-item" style="display:<?php echo checkPermissions(155); ?>">
                    <span class="fa-solid fa-chart-line"></span> &nbsp;
                    Tour Income Trend
                </a>
                <a href="service-payment-monthly-chart.php" class="list-group-item" style="display:<?php echo checkPermissions(160); ?>">
                    <span class="fa-solid fa-chart-column"></span> &nbsp;
                    Service Monthly Pmt Chart
                </a>
                <a href="supplier-payment-monthly-chart.php" class="list-group-item" style="display:<?php echo checkPermissions(144); ?>">
                    <span class="fa-solid fa-chart-column"></span> &nbsp;
                    Supplier Monthly Pmt Chart
                </a>
            </ul>
        </div>
        <div class="col-md-9">
            
        </div>
    </div>
</body>
</html>