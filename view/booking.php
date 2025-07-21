<?php

include_once '../commons/session.php';


//get user information from session
$userSession=$_SESSION["user"];
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

        </div>
    </div>
</body>
<script src="../js/jquery-3.7.1.js"></script>
</html>