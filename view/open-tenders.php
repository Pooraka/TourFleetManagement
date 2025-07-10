<?php

include_once '../commons/session.php';
include_once '../model/tender_model.php';


//get user information from session
$userSession=$_SESSION["user"];

$tenderObj = new Tender();

$tenderResult = $tenderObj->getOpenTenders();
?>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tender</title>
    <link rel="stylesheet" type="text/css" href="../css/dataTables.bootstrap.min.css"/>
    <?php include_once "../includes/bootstrap_css_includes.php"?>
</head>
<body>
    <div class="container">
        <?php $pageName="Tender Management - Open Tenders" ?>
        <?php include_once "../includes/header_row_includes.php";?>
        <div class="col-md-3">
            <ul class="list-group">
                <a href="add-supplier.php" class="list-group-item">
                    <span class="glyphicon glyphicon-plus"></span> &nbsp;
                    Add Supplier
                </a>
                <a href="view-suppliers.php" class="list-group-item">
                    <span class="glyphicon glyphicon-search"></span> &nbsp;
                    View Suppliers
                </a>
                <a href="add-tender.php" class="list-group-item">
                    <span class="glyphicon glyphicon-plus"></span> &nbsp;
                    Add Tender
                </a>
                <a href="open-tenders.php" class="list-group-item">
                    <span class="glyphicon glyphicon-search"></span> &nbsp;
                    Open Tenders
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
                    <table class="table" id="open_tenders">
                        <thead>
                            <tr>
                                <th style="white-space: wrap">Tender ID</th>
                                <th>Spare Part</th>
                                <th>Quantity</th>
                                <th>Description</th>
                                <th>Open Date</th>
                                <th>Close Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while($tenderRow = $tenderResult->fetch_assoc()){ ?>
                            <tr>
                                <td style="white-space: wrap"><?php echo $tenderRow["tender_id"];?></td>
                                <td style="white-space: nowrap"><?php echo $tenderRow["part_number"];?></td>
                                <td><?php echo $tenderRow["quantity_required"];?></td>
                                <td><?php echo $tenderRow["tender_description"];?></td>
                                <td style="white-space: nowrap"><?php echo $tenderRow["open_date"];?></td>
                                <td style="white-space: nowrap"><?php echo $tenderRow["close_date"];?></td>
                                <td>
                                    <a href="../documents/tenderadvertisements/<?php echo $tenderRow["advertisement_file_name"];?>" 
                                       class="btn btn-xs btn-info" style="margin:2px;display:<?php echo checkPermissions(69); ?>" target="_blank">                                                 
                                        Advertisement
                                    </a>
                                    <a href="add-bids.php?tender_id=<?php echo base64_encode($tenderRow["tender_id"]);?>" 
                                       class="btn btn-xs btn-success" style="margin:2px;display:<?php echo checkPermissions(70); ?>">
                                        Add Bids
                                    </a>
                                    <a href="view-bids.php?tender_id=<?php echo base64_encode($tenderRow["tender_id"]);?>" 
                                       class="btn btn-xs btn-primary" style="margin:2px;display:<?php echo checkPermissions(71); ?>">
                                        View Bids
                                    </a>
                                    <a href="../controller/tender_controller.php?status=cancel_tender&tender_id=<?php echo base64_encode($tenderRow["tender_id"]);?>" 
                                       class="btn btn-xs btn-danger" style="margin:2px;display:<?php echo checkPermissions(151); ?>">                                                 
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

        $("#open_tenders").DataTable();
    });
</script>
</html>