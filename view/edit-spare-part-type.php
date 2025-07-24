<?php

include_once '../commons/session.php';
include_once '../model/sparepart_model.php';


//get user information from session
$userSession=$_SESSION["user"];

$partId = base64_decode($_GET["part_id"]);

$sparePartObj = new SparePart();
$sparePartTypeResult = $sparePartObj->getSparePart($partId);
$sparePartTypeRow = $sparePartTypeResult->fetch_assoc();
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
        <?php $pageName="Spare Part Management - Edit Spare Part Type" ?>
        <?php include_once "../includes/header_row_includes.php";?>
        <div class="col-md-3">
            <?php include_once "../includes/spareparts_functions.php"; ?>
        </div>
        <form action="../controller/sparepart_controller.php?status=edit_sparepart_type" method="post" enctype="multipart/form-data">
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
                    <div class="col-md-2">
                        <label class="control-label">Part No</label>
                    </div>
                    <div class="col-md-4">
                        <input type="text" class="form-control" name="part_number" id="part_number" value="<?php echo $sparePartTypeRow['part_number'];?>"/>
                        <input type="hidden" name="part_id" value="<?php echo $sparePartTypeRow['part_id'];?>"/>
                    </div>
                    <div class="col-md-2">
                        <label class="control-label">Part Name</label>
                    </div>
                    <div class="col-md-4">
                        <input type="text" class="form-control" name="part_name" id="part_name" value="<?php echo $sparePartTypeRow['part_name'];?>"/>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        &nbsp;
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-3">
                        <label class="control-label">Re-order Level</label>
                    </div>
                    <div class="col-md-3">
                        <input type="number" class="form-control" name="reorder_level" id="reorder_level" value="<?php echo $sparePartTypeRow['reorder_level'];?>" min="1"/>
                    </div>
                    <div class="col-md-2">
                        <label class="control-label">Description</label>
                    </div>
                    <div class="col-md-4">
                        <textarea id="address" name="description" rows="2" class="form-control"><?php echo $sparePartTypeRow['description'];?></textarea>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12"> &nbsp; </div>
                </div>
                <div class="row">
                    <div class="col-md-offset-3 col-md-6">
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