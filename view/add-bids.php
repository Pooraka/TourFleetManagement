<?php

include_once '../commons/session.php';
include_once '../model/sparepart_model.php';
include_once '../model/tender_model.php';
include_once '../model/bid_model.php';
include_once '../model/supplier_model.php';


//get user information from session
$userSession=$_SESSION["user"];

$tenderId = base64_decode($_GET["tender_id"]);

$tenderObj = new Tender();
$tenderResult = $tenderObj->getTender($tenderId);
$tenderRow = $tenderResult->fetch_assoc();

$supplierObj = new Supplier();
$supplierResult = $supplierObj->getActiveSuppliers();

$bidObj = new Bid();
$bidResult = $bidObj->getBidsOfATender($tenderId);
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
        <?php $pageName="Tender Management - Add Bids" ?>
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
            </ul>
        </div>
        <form action="../controller/bid_controller.php?status=add_bid" method="post" enctype="multipart/form-data">
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
                    <div class="panel panel-info">
                        <div class="panel-heading"><span style="font-weight: bold; font-size: 18px;">Tender Details</span></div>
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-md-2"><label class="control-label">Tender ID:</label></div>
                                <div class="col-md-2"><?php echo $tenderRow['tender_id'];?></div>
                                <div class="col-md-2"><label class="control-label">Spare Part:</label></div>
                                <div class="col-md-2"><?php echo $tenderRow['part_number'];?></div>
                                <div class="col-md-2"><label class="control-label">Quantity:</label></div>
                                <div class="col-md-2"><?php echo $tenderRow['quantity_required'];?></div>
                            </div>
                            <div class="row">
                                &nbsp;
                            </div>
                            <div class="row">
                                <div class="col-md-2"><label class="control-label">Open Date:</label></div>
                                <div class="col-md-2"><?php echo $tenderRow['open_date'];?></div>
                                <div class="col-md-2"><label class="control-label">Close Date:</label></div>
                                <div class="col-md-2"><?php echo $tenderRow['close_date'];?></div>
                            </div>
                            <div class="row">
                                &nbsp;
                            </div>
                            <div class="row">
                                <div class="col-md-3"><label class="control-label">Tender Description:</label></div>
                                <div class="col-md-9"><?php echo $tenderRow['tender_description'];?></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-3">
                        <label class="control-label">Supplier</label>
                    </div>
                    <div class="col-md-3">
                        <select class="form-control" name="supplier_id">
                            <option value="">-------</option>
                            <?php while($supplierRow = $supplierResult->fetch_assoc()){?>
                            <option value="<?php echo $supplierRow['supplier_id']; ?>"><?php echo $supplierRow['supplier_name']; ?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="control-label">Unit Price</label>
                    </div>
                    <div class="col-md-3">
                        <input type="number" class="form-control" name="unit_price" min="0" step="0.01"/>
                        <input type="hidden" name="tender_id" value="<?php echo $tenderRow['tender_id'];?>"/>
                    </div>
                </div>
                <div class="row">
                    &nbsp;
                </div>
                <div class="row">
                    <div class="col-md-6 col-md-offset-3">
                        <input type="submit" class="btn btn-primary" value="Add Bid"/>
                        <input type="reset" class="btn btn-danger" value="Reset"/>
                    </div>
                </div>
                <div class="row">
                    &nbsp;
                </div>
                <div class="row">
                    <table class="table" id="bid_table">
                        <thead>
                            <tr>
                                <th>Bid Id</th>
                                <th>Supplier</th>
                                <th>Unit Price</th>
                                <th>Bid Date</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php while($bidRow = $bidResult->fetch_assoc()){ ?>
                            <tr>
                                <td><?php echo $bidRow['bid_id'];?></td>
                                <td><?php echo $bidRow['supplier_name'];?></td>
                                <td><?php echo "LKR ".number_format($bidRow['unit_price'],2);?></td>
                                <td><?php echo $bidRow['bid_date'];?></td>
                            </tr>
                        <?php } ?>    
                        </tbody>
                    </table>
                </div>
            </div>
        </form>
    </div>
</body>
<script src="../js/jquery-3.7.1.js"></script>
</html>