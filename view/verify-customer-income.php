<?php

include_once '../commons/session.php';
include_once '../model/customer_invoice_model.php';
include_once '../model/user_model.php';

//get user information from session
$userSession=$_SESSION["user"];

$customerInvoiceObj = new CustomerInvoice();
$invoiceResult = $customerInvoiceObj->getPaidInvoicesToVerify();

$userObj = new User();
?>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Finance Management</title>
    <link rel="stylesheet" type="text/css" href="../css/dataTables.bootstrap.min.css"/>
    <?php include_once "../includes/bootstrap_css_includes.php"?>
</head>
<body>
    <div class="container">
        <?php $pageName="Finance Management - Verify Customer Income" ?>
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
                    Generate Reports
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
                    <table class="table" id="customer_income">
                        <thead>
                            <tr>
                                <th>Payment Date</th>
                                <th>Receipt Number</th>
                                <th>Amount</th>
                                <th>Customer</th>
                                <th>Method</th>
                                <th>Received By</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while($invoiceRow = $invoiceResult->fetch_assoc()){
                                
                                $userId = $invoiceRow['received_by'];
                                $userResult = $userObj->getUser($userId);
                                $userRow = $userResult->fetch_assoc();
                                $userName = $userRow['user_fname']." ".$userRow['user_lname'];
                                
                                
                                ?>
                            <tr>
                                <td style="white-space:nowrap"><?php echo $invoiceRow['payment_date'];?></td>
                                <td style="white-space:nowrap"><?php echo $invoiceRow['receipt_number'];?></td>
                                <td><?php echo number_format($invoiceRow['paid_amount'],2);?></td>
                                <td><?php echo $invoiceRow['customer_fname']." ".$invoiceRow['customer_lname'];?></td>
                                <td><?php echo $invoiceRow['payment_method'];?></td>
                                <td><?php echo $userName;?></td>
                                <td>
                                    <a href="../reports/pendingInvoice.php?invoice_id=<?php echo base64_encode($invoiceRow['invoice_id']);?>" 
                                       class="btn btn-xs btn-info" style="margin:2px;display:<?php echo checkPermissions(139); ?>" target="_blank">
                                        View Invoice Issued
                                    </a>
                                    <a href="../reports/receipt.php?invoice_id=<?php echo base64_encode($invoiceRow['invoice_id']);?>" 
                                       class="btn btn-xs btn-info" style="margin:2px;display:<?php echo checkPermissions(140); ?>" target="_blank">
                                        View Receipt Issued
                                    </a>
                                    <a href="../documents/customerpaymentproofs/<?php echo $invoiceRow['payment_proof'];?>" 
                                       class="btn btn-xs btn-primary" style="margin:2px;display:<?php echo checkPermissions(141); ?>" target="_blank">
                                        View Payment Proof
                                    </a>
                                    <a href="../controller/finance_controller.php?status=verify_accepted_payment&tour_income_id=<?php echo base64_encode($invoiceRow['tour_income_id']);?>" 
                                       class="btn btn-xs btn-success" style="margin:2px;display:<?php echo checkPermissions(142); ?>">
                                        Verify
                                    </a>
                                    <a href="../controller/finance_controller.php?status=reject_accepted_payment&tour_income_id=<?php echo base64_encode($invoiceRow['tour_income_id']);?>" 
                                       class="btn btn-xs btn-danger" style="margin:2px;display:<?php echo checkPermissions(143); ?>">
                                        Reject
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

        $("#customer_income").DataTable();

    });
</script>
</html>