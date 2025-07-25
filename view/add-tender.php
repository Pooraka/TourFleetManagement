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
            <?php include_once "../includes/tender_functions.php"; ?>
        </div>
        <form id="addTenderForm" action="../controller/tender_controller.php?status=add_tender" method="post" enctype="multipart/form-data">
            <div class="col-md-9">
                <div class="row">
                    <div class="col-md-6 col-md-offset-3" id="msg" style="text-align:center">
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
                    <div class="col-md-3">
                        <label class="control-label">Spare Part</label>
                    </div>
                    <div class="col-md-3">
                        <select class="form-control" name="part_id" id="part_id" required>
                            <option value="">Select Spare Part</option>
                            <?php while($sparePartTypeRow = $sparePartTypeResult->fetch_assoc()){?>
                            <option value="<?php echo $sparePartTypeRow['part_id']; ?>"><?php echo $sparePartTypeRow['part_name']; ?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="control-label">Quantity Required</label>
                    </div>
                    <div class="col-md-3">
                        <input type="number" id="quantity" name="quantity" class="form-control" min="1" step="1"/>
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
                        <input type="date" id="open_date" name="open_date" class="form-control" min="<?php echo date('Y-m-d');?>"/>
                    </div>
                    <div class="col-md-3">
                        <label class="control-label">Close Date</label>
                    </div>
                    <div class="col-md-3">
                        <input type="date" id="close_date" name="close_date" class="form-control" min="<?php echo date('Y-m-d');?>"/>
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
                        <input type="file" id="advertisement" class="form-control" name="advertisement"/>
                    </div>
                    <div class="col-md-3">
                        <label class="control-label">Description</label>
                    </div>
                    <div class="col-md-3">
                        <textarea id="tender_description" rows="2" name="tender_description" class="form-control"></textarea>
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
<div class="modal fade" id="confirmationModal" tabindex="-1" role="dialog" aria-labelledby="modalLabel">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="modalLabel">Confirm Action</h4>
            </div>
            <div class="modal-body">
                Are you sure you want to proceed?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="confirmActionBtn">Confirm</button>
            </div>
        </div>
    </div>
</div>
<script src="../js/datatable/jquery-3.5.1.js"></script>
<script src="../js/datatable/jquery.dataTables.min.js"></script>
<script src="../js/datatable/dataTables.bootstrap.min.js"></script>
<script src="../bootstrap/js/bootstrap.min.js"></script>
<script>
    $(document).ready(function () {

        $("#addTenderForm").submit(function (event) {

            event.preventDefault(); // Prevent the default form submission

            var partId = $("#part_id").val();
            var quantity = $("#quantity").val();
            var openDate = $("#open_date").val();
            var closeDate = $("#close_date").val();
            var advertisement = $("#advertisement").val();
            var tenderDescription = $("#tender_description").val();

            if(partId=="" ){
                $("#msg").addClass("alert alert-danger");
                $("#msg").html("<b>Please select a spare part.</b>");
                return false;
            }

            if(quantity=="" || quantity <= 0){
                $("#msg").addClass("alert alert-danger");
                $("#msg").html("<b>Please enter a quantity above 0.</b>");
                return false;
            }

            if(openDate==""){
                $("#msg").addClass("alert alert-danger");
                $("#msg").html("<b>Please select an open date.</b>");
                return false;
            }

            if(closeDate==""){
                $("#msg").addClass("alert alert-danger");
                $("#msg").html("<b>Please select a close date.</b>");
                return false;
            }

            if(new Date(openDate) > new Date(closeDate)){
                $("#msg").addClass("alert alert-danger");
                $("#msg").html("<b>Close date must be same or after open date.</b>");
                return false;
            }

            if (advertisement == "") {
                $("#msg").addClass("alert alert-danger");
                $("#msg").html("<b>Please attach the advertisement proof.</b>");
                return false;
            }

            if (tenderDescription == "") {
                $("#msg").addClass("alert alert-danger");
                $("#msg").html("<b>Please enter a tender description.</b>");
                return false;
            }

            // If all validations pass, show the confirmation modal
            $("#confirmationModal").modal('show');

            // Set up the confirmation button to perform the actual submission
            $("#confirmActionBtn").off("click").on("click", function() {
                $("#addTenderForm").off("submit").submit();
            });
        });
    });
</script>
</html>