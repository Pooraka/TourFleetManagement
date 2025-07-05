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
    <title>Tender</title>
    <link rel="stylesheet" type="text/css" href="../css/dataTables.bootstrap.min.css"/>
    <?php include_once "../includes/bootstrap_css_includes.php"?>
</head>
<body>
    <div class="container">
        <?php $pageName="Tender Management - Add Tender" ?>
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
        <form action="../controller/tender_controller.php?status=add_tender" method="post" enctype="multipart/form-data">
            <div class="col-md-9">
                <div class="row">
                    <div id="msg" class="col-md-offset-3 col-md-6" style="text-align:center;">
                        <?php if (isset($_GET["msg"])) { ?>

                            <script>
                                var msgElement = document.getElementById("msg");
                                msgElement.classList.add("alert", "alert-danger");
                            </script>

                            <b> <p> <?php echo base64_decode($_GET["msg"]); ?></p></b>
                            <?php
                        }
                        ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-3">
                        <label class="control-label">Spare Part</label>
                    </div>
                    <div class="col-md-3">
                        <select class="form-control" name="part_id">
                            <option value="">-------</option>
                            <?php while($sparePartTypeRow = $sparePartTypeResult->fetch_assoc()){?>
                            <option value="<?php echo $sparePartTypeRow['part_id']; ?>"><?php echo $sparePartTypeRow['part_number']; ?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="control-label">Quantity Required</label>
                    </div>
                    <div class="col-md-3">
                        <input type="number" name="quantity" class="form-control" min="1" step="1"/>
                    </div>
                </div>
                <div class="row">
                    &nbsp;
                </div>
                <div class="row">
                    <div class="col-md-3">
                        <label class="control-label">Open Date</label>
                    </div>
                    <div class="col-md-3">
                        <input type="date" name="open_date" class="form-control"/>
                    </div>
                    <div class="col-md-3">
                        <label class="control-label">Close Date</label>
                    </div>
                    <div class="col-md-3">
                        <input type="date" name="close_date" class="form-control"/>
                    </div>
                </div>
                <div class="row">
                    &nbsp;
                </div>
                <div class="row">
                    <div class="col-md-3">
                        <label class="control-label">Advertisement</label>
                    </div>
                    <div class="col-md-3">
                        <input type="file" class="form-control" name="advertisement"/>
                    </div>
                    <div class="col-md-3">
                        <label class="control-label">Description</label>
                    </div>
                    <div class="col-md-3">
                        <textarea id="address" rows="2" name="tender_description" class="form-control"></textarea>
                    </div>
                </div>
                <div class="row">
                    &nbsp;
                </div>
                <div class="row">
                    <div class="col-md-6 col-md-offset-3">
                        <input type="submit" class="btn btn-primary" value="Submit"/>
                        <input type="reset" class="btn btn-danger" value="Reset"/>
                    </div>
                </div>
            </div>
        </form>
    </div>
</body>
<script src="../js/jquery-3.7.1.js"></script>
</html>