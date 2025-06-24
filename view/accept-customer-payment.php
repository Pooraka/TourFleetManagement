<?php

include_once '../commons/session.php';
include_once '../model/customer_invoice_model.php';


//get user information from session
$userSession=$_SESSION["user"];

$invoiceId = $_GET['invoice_id'];
$invoiceId = base64_decode($invoiceId);

$customerInvoiceObj = new CustomerInvoice();
$invoiceResult = $customerInvoiceObj->getInvoiceInformation($invoiceId);
$invoiceRow = $invoiceResult->fetch_assoc();

$invoiceItemResult = $customerInvoiceObj->getInvoiceItems($invoiceId);
?>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking</title>
    <link rel="stylesheet" type="text/css" href="../css/dataTables.bootstrap.min.css"/>
    <?php include_once "../includes/bootstrap_css_includes.php"?>
</head>
<body>
    <div class="container">
        <?php $pageName="Booking Management - Accept Customer Payment" ?>
        <?php include_once "../includes/header_row_includes.php";?>
        <div class="col-md-3">
            <ul class="list-group">
                <a href="generate-quotation.php" class="list-group-item">
                    <span class="glyphicon glyphicon-plus"></span> &nbsp;
                    Generate Quotation
                </a>
                <a href="pending-quotations.php" class="list-group-item">
                    <span class="glyphicon glyphicon-search"></span> &nbsp;
                    Pending Quotations
                </a>
                <a href="pending-customer-invoices.php" class="list-group-item">
                    <span class="glyphicon glyphicon-search"></span> &nbsp;
                    Pending Invoice
                </a>
            </ul>
        </div>
        <form action="../controller/booking_controller.php" method="post" enctype="multipart/form-data">
        <div class="col-md-9">
            <div class="row">
                <div class="col-md-6 col-md-offset-3">
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
                <div class="panel panel-info">
                    <div class="panel-heading">
                        <h3 style="margin:0px">Invoice Information</h3>
                    </div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-3" style="margin-bottom: 10px">
                                <span class="fa-solid fa-hashtag"></span>&nbsp;<b>Invoice Number</b>
                                </br>
                                <span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $invoiceRow['invoice_number'];?> </span>
                            </div>
                            <div class="col-md-3" style="margin-bottom: 10px">
                                <span class="fa-solid fa-calendar-day"></span>&nbsp;<b>Date</b>
                                </br>
                                <span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $invoiceRow['invoice_date'];?></span>
                            </div>
                            <div class="col-md-3" style="margin-bottom: 10px">
                                <span class="fa-solid fa-money-bill-wave"></span>&nbsp;<b>Amount</b>
                                </br>
                                <span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo "LKR ".number_format($invoiceRow['invoice_amount'],2); ?></span>
                            </div>
                            <div class="col-md-3" style="margin-bottom: 10px">
                                <span class="fa-solid fa-user"></span>&nbsp;<b>Customer</b>
                                </br>
                                <span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $invoiceRow['customer_fname']." ".$invoiceRow['customer_lname']; ?></span>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3" style="margin-bottom: 10px">
                                <span class="fa-solid fa-calendar-check"></span>&nbsp;<b>Tour Start</b>
                                </br>
                                <span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $invoiceRow['tour_start_date'];?> </span>
                            </div>
                            <div class="col-md-3" style="margin-bottom: 10px">
                                <span class="fa-solid fa-calendar-times"></span>&nbsp;<b>Tour End</b>
                                </br>
                                <span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $invoiceRow['tour_end_date'];?></span>
                            </div>
                            <div class="col-md-3" style="margin-bottom: 10px">
                                <span class="fa-solid fa-map-marker-alt"></span>&nbsp;<b>Pickup From</b>
                                </br>
                                <span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $invoiceRow['pickup_location']; ?></span>
                            </div>
                            <div class="col-md-3" style="margin-bottom: 10px">
                                <span class="fa-solid fa-flag-checkered"></span>&nbsp;<b>Drop At</b>
                                </br>
                                <span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $invoiceRow['dropoff_location']; ?></span>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3" style="margin-bottom: 10px">
                                <span class="fa-solid fa-road"></span>&nbsp;<b>Total Mileage</b>
                                </br>
                                <span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo "Km ".number_format($invoiceRow['round_trip_mileage'],0);?> </span>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>Bus Category</th>
                                            <th>No of Buses</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php while($invoiceItemRow = $invoiceItemResult->fetch_assoc()){ ?>
                                        <tr>
                                            <td><?php echo $invoiceItemRow['category_name'];?> </td>
                                            <td><?php echo $invoiceItemRow['quantity'];?> </td>
                                        </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="panel-footer">
                        <div class="row">
                            <div class="col-md-2">
                                <label class="control-label">Actual Fare</label>
                            </div>
                            <div class="col-md-3">
                                <input type="number" class="form-control" name="actual_fair" id="actual_fair" step="0.01" inputmode="decimal"/>
                            </div>
                            <div class="col-md-2">
                                <label class="control-label">Actual Mileage</label>
                            </div>
                            <div class="col-md-3">
                                <input type="number" class="form-control" name="actual_mileage" id="actual_mileage" step="1"/>
                            </div>
                        </div>
                        <div class="row">
                            &nbsp;
                        </div>
                        <div class="row">
                            <div class="col-md-2">
                                <label class="control-label">Attach Proof</label>
                            </div>
                            <div class="col-md-3">
                                <input type="file" class="form-control" name="proof"/>
                            </div>
                            <div class="col-md-3">
                                <label class="control-label">Payment Method</label>
                            </div>
                            <div class="col-md-4">
                                <input type="radio" name="payment_method" value="cash"/>
                                <label>Cash</label>
                                &nbsp;&nbsp;
                                <input type="radio" name="payment_method" value="transfer"/>
                                <label>Funds Transfer</label>
                            </div>
                        </div>
                        <div class="row">
                            &nbsp;
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <input type="submit" class="btn btn-success" value="Accept Payment"/>
                                <input type="reset" class="btn btn-danger" value="Reset" style="width:130px"/>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        </form>
    </div>
</body>
<script src="../js/jquery-3.7.1.js"></script>
</html>