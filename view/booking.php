<?php

include_once '../commons/session.php';
include_once '../model/quotation_model.php';
include_once '../model/customer_invoice_model.php';


//get user information from session
$userSession=$_SESSION["user"];

$quotationObj = new Quotation();
$customerInvoiceObj = new CustomerInvoice();

$pendingQuotationCount = $quotationObj->getPendingQuotationCount();
$pendingCustomerInvoiceCount = $customerInvoiceObj->getPendingInvoiceCount();
$advancePaymentsReceivedWithinLast7Days = $customerInvoiceObj->getAdvancePaymentAmountReceivedWithinLast7Days();
$refundedAmountsWithinLast7Days = $customerInvoiceObj->getRefundAmountsWithinLast7Days();
?>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking</title>
    <?php include_once "../includes/bootstrap_css_includes.php"?>
</head>
<body>
    <div class="container">
        <?php $pageName="Booking Management" ?>
        <?php include_once "../includes/header_row_includes.php";?>
        <div class="col-md-3">
            <ul class="list-group">
                <a href="dashboard.php" class="list-group-item">
                    <span class="fa-solid fa-house"></span> &nbsp;
                    Back To Dashboard
                </a>
                <a href="generate-quotation.php" class="list-group-item" style="display:<?php echo checkPermissions(76); ?>">
                    <span class="fa-solid fa-plus"></span> &nbsp;
                    Generate Quotation
                </a>
                <a href="pending-quotations.php" class="list-group-item" style="display:<?php echo checkPermissions(77); ?>">
                    <span class="fa-solid fa-hourglass-half"></span> &nbsp;
                    Pending Quotations
                </a>
                <a href="pending-customer-invoices.php" class="list-group-item" style="display:<?php echo checkPermissions(149); ?>">
                    <span class="fa-solid fa-file-invoice"></span> &nbsp;
                    Pending Invoices
                </a>
                <a href="booking-history.php" class="list-group-item" style="display:<?php echo checkPermissions(81); ?>">
                    <span class="fa-solid fa-receipt"></span> &nbsp;
                    Booking History
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
                <div class="col-md-6">
                    <div class="panel panel-info" style="text-align:center;height:150px">
                        <div class="panel-heading">
                            <p align="center">Pending Quotations</p>
                        </div>
                        <div class="panel-body">
                            <h1 class="h1" align="center"><?php echo $pendingQuotationCount;?></h1>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="panel panel-info" style="text-align:center;height:150px">
                        <div class="panel-heading">
                            <p align="center">Pending Invoices</p>
                        </div>
                        <div class="panel-body">
                            <h1 class="h1" align="center"><?php echo $pendingCustomerInvoiceCount;?></h1>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="panel panel-info" style="text-align:center;height:150px">
                        <div class="panel-heading">
                            <p align="center">Advance Payments Received Within Last 7 Days</p>
                        </div>
                        <div class="panel-body">
                            <h1 class="h1" align="center"><?php echo "LKR " . number_format($advancePaymentsReceivedWithinLast7Days, 2);?></h1>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="panel panel-info" style="text-align:center;height:150px">
                        <div class="panel-heading">
                            <p align="center">Refunded Amount Within Last 7 Days</p>
                        </div>
                        <div class="panel-body">
                            <h1 class="h1" align="center"><?php echo "LKR " . number_format($refundedAmountsWithinLast7Days, 2);?></h1>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
<script src="../js/jquery-3.7.1.js"></script>
</html>