<?php

include_once '../commons/session.php';
include_once '../model/customer_invoice_model.php';


//get user information from session
$userSession=$_SESSION["user"];

$customerInvoiceObj = new CustomerInvoice();
$pendingInvoiceResult = $customerInvoiceObj->getPendingCustomerInvoices();
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
        <?php $pageName="Booking Management - Pending Invoices" ?>
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
                <a href="pending-customer_invoices.php" class="list-group-item">
                    <span class="glyphicon glyphicon-search"></span> &nbsp;
                    Pending Invoice
                </a>
            </ul>
        </div>
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
                <div class="col-md-12">
                    <table class="table" id="invoicetable">
                        <thead>
                            <tr>
                                <th>Invoice No</th>
                                <th>Date</th>
                                <th>Customer</th>
                                <th>Amount</th>
                                <th>Tour Date</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while($pendingInvoiceRow = $pendingInvoiceResult->fetch_assoc()){?>
                            <tr>
                                <td><?php echo $pendingInvoiceRow['invoice_number'];?></td>
                                <td><?php echo $pendingInvoiceRow['invoice_date'];?></td>
                                <td><?php echo $pendingInvoiceRow['customer_fname']." ".$pendingInvoiceRow['customer_lname'];?></td>
                                <td><?php echo "LKR ".number_format($pendingInvoiceRow['invoice_amount'],2);?></td>
                                <td><?php echo $pendingInvoiceRow['tour_start_date'];?></td>
                                <td>
                                    <a href="" class="btn btn-xs btn-info" style="margin:2px">
                                        <span class="glyphicon glyphicon-search"></span>                                                  
                                        View
                                    </a>
                                    <a href="accept-customer-payment.php?invoice_id=<?php echo base64_encode($pendingInvoiceRow['invoice_id']);?>" class="btn btn-xs btn-success" style="margin:2px">
                                        <span class="glyphicon glyphicon-ok"></span>                                                  
                                        Accept Payment
                                    </a>
                                    </br>
                                    <a href="" class="btn btn-xs btn-danger" style="margin:2px">
                                        <span class="glyphicon glyphicon-remove"></span>                                                  
                                        Cancel
                                    </a>
                                </td>
                            </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</body>
<script src="../js/datatable/jquery-3.5.1.js"></script>
<script src="../js/datatable/jquery.dataTables.min.js"></script>
<script src="../js/datatable/dataTables.bootstrap.min.js"></script>
<script>
    $(document).ready(function(){

        $("#invoicetable").DataTable();
    });
</script>
</html>