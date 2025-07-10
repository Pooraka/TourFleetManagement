<?php

include_once '../commons/session.php';
include_once '../model/quotation_model.php';

//get user information from session
$userSession=$_SESSION["user"];

$quotationObj = new Quotation();
$pendingQuotationResult = $quotationObj->getPendingQuotations();

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
        <?php $pageName="Booking Management - Pending Quotations" ?>
        <?php include_once "../includes/header_row_includes.php";?>
        <div class="col-md-3">
            <ul class="list-group">
                <a href="generate-quotation.php" class="list-group-item" style="display:<?php echo checkPermissions(76); ?>">
                    <span class="glyphicon glyphicon-plus"></span> &nbsp;
                    Generate Quotation
                </a>
                <a href="pending-quotations.php" class="list-group-item" style="display:<?php echo checkPermissions(77); ?>">
                    <span class="glyphicon glyphicon-search"></span> &nbsp;
                    Pending Quotations
                </a>
                <a href="pending-customer-invoices.php" class="list-group-item" style="display:<?php echo checkPermissions(149); ?>">
                    <span class="glyphicon glyphicon-search"></span> &nbsp;
                    Pending Invoices
                </a>
                <a href="customer-receipts.php" class="list-group-item" style="display:<?php echo checkPermissions(81); ?>">
                    <span class="glyphicon glyphicon-search"></span> &nbsp;
                    Customer Receipts
                </a>
            </ul>
        </div>
        <form action="../controller/service_detail_controller.php?status=initiate_service" method="post" enctype="multipart/form-data">
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
                    <table class="table" id="quotationtable">
                        <thead>
                            <tr>
                                <th>Quotation Id</th>
                                <th>Issued Date</th>
                                <th>Customer</th>
                                <th>Amount</th>
                                <th>Tour Date</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while($pendingQuotationRow = $pendingQuotationResult->fetch_assoc()){?>
                            <tr>
                                <td style="text-align: center"><?php echo $pendingQuotationRow['quotation_id'];?></td>
                                <td><?php echo $pendingQuotationRow['issued_date'];?></td>
                                <td><?php echo htmlspecialchars($pendingQuotationRow['customer_fname']." ".$pendingQuotationRow['customer_lname']);?></td>
                                <td><?php echo "LKR ".number_format($pendingQuotationRow['total_amount'],2);?></td>
                                <td><?php echo $pendingQuotationRow['issued_date'];?></td>
                                <td>
                                    <a href="../reports/quotation.php?quotation_id=<?php echo base64_encode($pendingQuotationRow['quotation_id']);?>" 
                                       class="btn btn-xs btn-info" style="margin:2px;display:<?php echo checkPermissions(78);?>" target="_blank">
                                        <span class="glyphicon glyphicon-search"></span>                                                  
                                        View
                                    </a>
                                    <a href="../controller/customer_invoice_controller.php?status=generate_customer_invoice&quotation_id=<?php echo base64_encode($pendingQuotationRow['quotation_id']);?>" 
                                       class="btn btn-xs btn-success" style="margin:2px;display:<?php echo checkPermissions(79);?>">
                                        <span class="glyphicon glyphicon-ok"></span>                                                  
                                        Generate Invoice
                                    </a>
                                    <a href="../controller/quotation_controller.php?status=cancel_quotation&quotation_id=<?php echo base64_encode($pendingQuotationRow['quotation_id']);?>" 
                                       class="btn btn-xs btn-danger" style="margin:2px;display:<?php echo checkPermissions(80);?>">
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
        </form>
    </div>
</body>
<script src="../js/datatable/jquery-3.5.1.js"></script>
<script src="../js/datatable/jquery.dataTables.min.js"></script>
<script src="../js/datatable/dataTables.bootstrap.min.js"></script>
<script>
    $(document).ready(function(){

        $("#quotationtable").DataTable();
    });
</script>
</html>
