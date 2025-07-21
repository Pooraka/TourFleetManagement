<?php

include_once '../commons/session.php';


//get user information from session
$userSession=$_SESSION["user"];
?>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Purchasing</title>
    <?php include_once "../includes/bootstrap_css_includes.php"?>
</head>
<body>
    <div class="container">
        <?php $pageName="Purchasing" ?>
        <?php include_once "../includes/header_row_includes.php";?>
        <div class="col-md-3">
            <ul class="list-group">
                <a href="awarded-bids.php" class="list-group-item" style="display:<?php echo checkPermissions(89); ?>">
                    <span class="fa-solid fa-gavel"></span> &nbsp;
                    View Awarded Bids
                </a>
                <a href="pending-purchase-orders.php" class="list-group-item" style="display:<?php echo checkPermissions(92); ?>">
                    <span class="fa-solid fa-file-import"></span> &nbsp;
                    View Pending PO
                </a>
                <a href="past-purchase-orders.php" class="list-group-item" style="display:<?php echo checkPermissions(162); ?>">
                    <span class="fa-solid fa-scroll"></span> &nbsp;
                    Past Purchase Orders
                </a>
                <a href="po-status-report.php" class="list-group-item" style="display:<?php echo checkPermissions(97); ?>">
                    <span class="fa-solid fa-chart-gantt"></span> &nbsp;
                    PO Status Report
                </a>
            </ul>
        </div>
        <div class="col-md-9">

        </div>
    </div>
</body>
<script src="../js/jquery-3.7.1.js"></script>
</html>