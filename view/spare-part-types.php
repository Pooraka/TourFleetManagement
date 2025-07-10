<?php

include_once '../commons/session.php';
include_once '../model/sparepart_model.php';

//get user information from session
$userSession=$_SESSION["user"];

$sparePartObj = new SparePart();
$sparePartTypeResult = $sparePartObj->getSpareParts();
?>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Spare Parts</title>
    <link rel="stylesheet" type="text/css" href="../css/dataTables.bootstrap.min.css"/>
    <?php include_once "../includes/bootstrap_css_includes.php"?>
</head>
<body>
    <div class="container">
        <?php $pageName="Spare Part Management - Spare Part Types" ?>
        <?php include_once "../includes/header_row_includes.php";?>
        <div class="col-md-3">
            <ul class="list-group">
                <a href="register-spareparts.php" class="list-group-item" style="display:<?php echo checkPermissions(98); ?>">
                    <span class="glyphicon glyphicon-search"></span> &nbsp;
                    Register Spare Parts
                </a>
                <a href="spare-part-types.php" class="list-group-item" style="display:<?php echo checkPermissions(99); ?>">
                    <span class="glyphicon glyphicon-search"></span> &nbsp;
                    View Spare Part Types
                </a>
                <a href="add-spare-parts.php" class="list-group-item" style="display:<?php echo checkPermissions(101); ?>">
                    <span class="glyphicon glyphicon-search"></span> &nbsp;
                    Add Spare Parts
                </a>
                <a href="view-grns.php" class="list-group-item" style="display:<?php echo checkPermissions(102); ?>">
                    <span class="glyphicon glyphicon-search"></span> &nbsp;
                    View GRNs
                </a>
                <a href="view-spare-parts.php" class="list-group-item" style="display:<?php echo checkPermissions(103); ?>">
                    <span class="glyphicon glyphicon-search"></span> &nbsp;
                    View Spare Parts
                </a>
                <a href="../reports/part-inventory-report.php" class="list-group-item" target="_blank" style="display:<?php echo checkPermissions(106); ?>">
                    <span class="glyphicon glyphicon-search"></span> &nbsp;
                    Spare Part Inventory Report
                </a>
                <a href="spare-part-transaction-history.php" class="list-group-item" style="display:<?php echo checkPermissions(107); ?>">
                    <span class="glyphicon glyphicon-search"></span> &nbsp;
                    Spare Part Transactions
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
                    <table class="table" id="spare_part_types">
                        <thead>
                            <tr>
                                <th>Part Number</th>
                                <th>Part Name</th>
                                <th>Re-order Level</th>
                                <th>Description</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while($sparePartTypeRow = $sparePartTypeResult->fetch_assoc()){ ?>
                            <tr>
                                <td style="white-space: nowrap"><?php echo $sparePartTypeRow['part_number'];?></td>
                                <td style="white-space: nowrap"><?php echo $sparePartTypeRow['part_name'];?></td>
                                <td><?php echo $sparePartTypeRow['reorder_level'];?></td>
                                <td><?php echo $sparePartTypeRow['description'];?></td>
                                <td>
                                    <a href="edit-spare-part-type.php?part_id=<?php echo base64_encode($sparePartTypeRow['part_id']); ?>" 
                                       class="btn btn-xs btn-warning" style="margin:2px;display:<?php echo checkPermissions(100); ?>">
                                        <span class="glyphicon glyphicon-pencil"></span>
                                        Edit
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

        $("#spare_part_types").DataTable();

    });
</script>
</html>