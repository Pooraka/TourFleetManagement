<?php

include_once '../commons/session.php';


//get user information from session
$userSession=$_SESSION["user"];
?>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tender</title>
    <?php include_once "../includes/bootstrap_css_includes.php"?>
</head>
<body>
    <div class="container">
        <?php $pageName="Tender Management" ?>
        <?php include_once "../includes/header_row_includes.php";?>
        <div class="col-md-3">
            <ul class="list-group">
                <a href="add-supplier.php" class="list-group-item" style="display:<?php echo checkPermissions(61); ?>">
                    <span class="fa-solid fa-plus"></span> &nbsp;
                    Add Supplier
                </a>
                <a href="view-suppliers.php" class="list-group-item" style="display:<?php echo checkPermissions(62); ?>">
                    <span class="fa-solid fa-truck-field"></span> &nbsp;
                    View Suppliers
                </a>
                <a href="add-tender.php" class="list-group-item" style="display:<?php echo checkPermissions(67); ?>">
                    <span class="fa-solid fa-plus"></span> &nbsp;
                    Add Tender
                </a>
                <a href="open-tenders.php" class="list-group-item" style="display:<?php echo checkPermissions(68); ?>">
                    <span class="fa-solid fa-folder-open"></span> &nbsp;
                    View Open Tenders
                </a>
                <a href="tender-status-report.php" class="list-group-item" style="display:<?php echo checkPermissions(150); ?>">
                    <span class="fa-solid fa-file-contract"></span> &nbsp;
                    Tender Status Report
                </a>
            </ul>
        </div>
        <div class="col-md-9">

        </div>
    </div>
</body>
<script src="../js/jquery-3.7.1.js"></script>
</html>