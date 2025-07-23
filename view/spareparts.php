<?php

include_once '../commons/session.php';
include_once '../model/sparepart_model.php';

//get user information from session
$userSession=$_SESSION["user"];
$userFunctions=$_SESSION['user_functions'];

$sparePartObj = new SparePart();

//Get zero stock spare parts count
$zeroStockCount = $sparePartObj->getSparePartCountWith0Stock();

//Get low stock spare parts count
$lowStockCount = $sparePartObj->getSparePartCountWithLowStock();
?>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Spare Parts</title>
    <?php include_once "../includes/bootstrap_css_includes.php"?>
</head>
<body>
    <div class="container">
        <?php $pageName="Spare Part Management" ?>
        <?php include_once "../includes/header_row_includes.php";?>
        <div class="col-md-3">
            <ul class="list-group">
                <a href="dashboard.php" class="list-group-item">
                    <span class="fa-solid fa-house"></span> &nbsp;
                    Back To Dashboard
                </a>
                <a href="register-spareparts.php" class="list-group-item" style="display:<?php echo checkPermissions(98); ?>">
                    <span class="fa-solid fa-gears"></span> &nbsp;
                    Register Spare Parts
                </a>
                <a href="spare-part-types.php" class="list-group-item" style="display:<?php echo checkPermissions(99); ?>">
                    <span class="fa-solid fa-tags"></span> &nbsp;
                    View Spare Part Types
                </a>
                <a href="add-spare-parts.php" class="list-group-item" style="display:<?php echo checkPermissions(101); ?>">
                    <span class="fa-solid fa-cart-plus"></span> &nbsp;
                    Add Spare Parts
                </a>
                <a href="view-grns.php" class="list-group-item" style="display:<?php echo checkPermissions(102); ?>">
                    <span class="fa-solid fa-truck-ramp-box"></span> &nbsp;
                    View GRNs
                </a>
                <a href="view-spare-parts.php" class="list-group-item" style="display:<?php echo checkPermissions(103); ?>">
                    <span class="fa-solid fa-boxes-stacked"></span> &nbsp;
                    View Spare Parts
                </a>
                <a href="../reports/part-inventory-report.php" class="list-group-item" target="_blank" style="display:<?php echo checkPermissions(106); ?>">
                    <span class="fa-solid fa-warehouse"></span> &nbsp;
                    Spare Part Inventory Report
                </a>
                <a href="spare-part-transaction-history.php" class="list-group-item" style="display:<?php echo checkPermissions(107); ?>">
                    <span class="fa-solid fa-right-left"></span> &nbsp;
                    Spare Part Transactions
                </a>
            </ul>
        </div>
        <div class="col-md-9">
            <?php if($zeroStockCount > 0) { ?>
            <div class="row">
                <div class="col-md-12">
                    <div class="alert alert-danger">
                        <span class="fa-solid fa-triangle-exclamation"></span> &nbsp;
                        <strong>Action Required:</strong> There are <?php echo $zeroStockCount; ?> spare parts with zero stock. Please consult Procurement Officer.
                    </div>
                </div>
            </div>
            <?php } ?>
            <?php if($lowStockCount > 0) { ?>
            <div class="row">
                <div class="col-md-12">
                    <div class="alert alert-warning">
                        <span class="fa-solid fa-triangle-exclamation"></span> &nbsp;
                        <strong>Action Required:</strong> There are <?php echo $lowStockCount; ?> spare parts with low stock. Please consult Procurement Officer.
                    </div>
                </div>
            </div>
            <?php } ?>
        </div>
    </div>
</body>
<script src="../js/jquery-3.7.1.js"></script>
</html>