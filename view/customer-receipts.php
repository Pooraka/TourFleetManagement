<?php

include_once '../commons/session.php';
include_once '../model/customer_invoice_model.php';
include_once '../model/finance_model.php';


//get user information from session
$userSession=$_SESSION["user"];

$customerInvoiceObj = new CustomerInvoice();
$paidInvoiceResult = $customerInvoiceObj->getPaidInvoices();

$financeObj = new Finance();
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
        <?php $pageName="Booking Management - Customer Receipts" ?>
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
                                <th>Payment Date</th>
                                <th>Invoice No</th>
                                <th>Receipt No</th>
                                <th>Customer</th>
                                <th>Actual Fare</th>
                                <th>Tour Date</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while($paidInvoiceRow = $paidInvoiceResult->fetch_assoc()){
                                
                                $tourIncomeResult = $financeObj->getTourIncomeRecordByInvoiceId($paidInvoiceRow['invoice_id']);
                                $tourIncomeRow = $tourIncomeResult->fetch_assoc();
                                
                                $receiptNo = "ST-R-".$tourIncomeRow['tour_income_id'];
                                
                                $invoiceStatus = match((int)$paidInvoiceRow['invoice_status']){
    
                                    -1=>"Cancelled",
                                    1=>"Tour to be assigned",
                                    2=>"Tour assigned",
                                    3=>"Payment Pending",
                                    4=>"Paid",
                                };     
                                ?>
                            <tr>
                                <td style="white-space: nowrap"><?php echo $tourIncomeRow['payment_date'];?></td>
                                <td style="white-space: nowrap"><?php echo $paidInvoiceRow['invoice_number'];?></td>
                                <td style="white-space: nowrap"><?php echo $receiptNo;?></td>
                                <td style="white-space: nowrap"><?php echo $paidInvoiceRow['customer_fname']." ".$paidInvoiceRow['customer_lname'];?></td>
                                <td style="white-space: nowrap"><?php echo "LKR ".number_format($paidInvoiceRow['actual_fare'],2);?></td>
                                <td style="white-space: nowrap"><?php echo $paidInvoiceRow['tour_start_date'];?></td>
                                <td><?php echo $invoiceStatus;?></td>
                                <td>
                                    <a href="../reports/receipt.php?invoice_id=<?php echo base64_encode($paidInvoiceRow['invoice_id']);?>" target="_blank" class="btn btn-xs btn-info" style="margin:2px">                                                  
                                        Get Receipt
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