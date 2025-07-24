<?php

include_once '../commons/session.php';
include_once '../model/supplier_model.php';

//get user information from session
$userSession=$_SESSION["user"];

$supplierObj = new Supplier();
$supplierResult = $supplierObj->getPaymentPendingSuppliers();
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
        <?php $pageName="Finance Management - Pending Supplier Payments" ?>
        <?php include_once "../includes/header_row_includes.php";?>
        <div class="col-md-3">
            <?php include_once "../includes/finance_functions.php"; ?>
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
                    <table class="table" id="payment_pending_suppliers">
                        <thead>
                            <tr>
                                <th>Supplier</th>
                                <th>Number Of Invoices</th>
                                <th>Total Due</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while($supplierRow=$supplierResult->fetch_assoc()){ ?>
                            <tr>
                                <td><?php echo $supplierRow['supplier_name'];?> </td>
                                <td><?php echo $supplierRow['po_count'];?> </td>
                                <td><?php echo "LKR ".number_format($supplierRow['total_due'],2);?> </td>
                                <td>
                                    <a href="make-supplier-payment.php?supplier_id=<?php echo base64_encode($supplierRow['supplier_id']) ?>" 
                                       class="btn btn-success" style="margin:2px;display:<?php echo checkPermissions(137); ?>">
                                        <span class="fas fa-dollar-sign"></span>
                                        Pay
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

        $("#payment_pending_suppliers").DataTable();

    });
</script>
</html>