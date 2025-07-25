<?php

include_once '../commons/session.php';

//get user information from session
$userSession=$_SESSION["user"];
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
        <?php $pageName="Spare Part Management - Register Spare Parts" ?>
        <?php include_once "../includes/header_row_includes.php";?>
        <div class="col-md-3">
            <?php include_once "../includes/spareparts_functions.php"; ?>
        </div>
        <form id="registerSparePartForm" action="../controller/sparepart_controller.php?status=register_sparepart" method="post" enctype="multipart/form-data">
            <div class="col-md-9">
                <div class="row">
                    <div class="col-md-6 col-md-offset-3" id="msg" style="text-align: center;">
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
                    <div class="col-md-2">
                        <label class="control-label">Part No</label>
                    </div>
                    <div class="col-md-4">
                        <input type="text" class="form-control" name="part_number" id="part_number" placeholder="Ex: LAL-BP-VK01"/>
                    </div>
                    <div class="col-md-2">
                        <label class="control-label">Part Name</label>
                    </div>
                    <div class="col-md-4">
                        <input type="text" class="form-control" name="part_name" id="part_name" placeholder="Ex: LAL Viking Brake Pad Set"/>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        &nbsp;
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-3">
                        <label class="control-label">Quantity On Hand</label>
                    </div>
                    <div class="col-md-3">
                        <input type="number" class="form-control" name="quantity" id="quantity" placeholder="Ex: 53" min="0"/>
                    </div>
                    <div class="col-md-3">
                        <label class="control-label">Re-order Level</label>
                    </div>
                    <div class="col-md-3">
                        <input type="number" class="form-control" name="reorder_level" id="reorder_level" placeholder="Ex: 10" min="1"/>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        &nbsp;
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-2">
                        <label class="control-label">Description</label>
                    </div>
                    <div class="col-md-5">
                        <textarea id="description" name="description" rows="2" class="form-control" placeholder="Front brake pads for Lanka Ashok Leyland Viking models."></textarea>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12"> &nbsp; </div>
                </div>
                <div class="row">
                    <div class="col-md-offset-3 col-md-6">
                        <input type="submit" class="btn btn-primary" value="Register"/>
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

        $("#registerSparePartForm").on("submit", function (event) {
            event.preventDefault(); // Prevent the default form submission

            var partNumber = $("#part_number").val();
            var partName = $("#part_name").val();
            var quantity = $("#quantity").val();
            var reorderLevel = $("#reorder_level").val();
            var description = $("#description").val();

            if( partNumber == "" ){
                $("#msg").addClass("alert alert-danger");
                $("#msg").html("<b>Part Number is required.</b>");
                return false;
            }

            if( partName == "" ){
                $("#msg").addClass("alert alert-danger");
                $("#msg").html("<b>Part Name is required.</b>");
                return false;
            }

            if( quantity == "" || quantity < 0 ){
                $("#msg").addClass("alert alert-danger");
                $("#msg").html("<b>Minimum quantity on hand must be 0.</b>");
                return false;
            }

            if( reorderLevel == "" || reorderLevel < 1 ){
                $("#msg").addClass("alert alert-danger");
                $("#msg").html("<b>Re-order level must be at least 1.</b>");
                return false;
            }

            if( description == "" ){
                $("#msg").addClass("alert alert-danger");
                $("#msg").html("<b>Description is required.</b>");
                return false;
            }

            //If all validations pass, show confirmation modal
            $("#confirmationModal").modal("show");
            $("#confirmActionBtn").off("click").on("click", function () {
                //Submit the form
                $("#registerSparePartForm").off("submit").submit();
            });

        });

        $("form").on("reset", function () {
            $("#msg").removeClass("alert alert-danger");
            $("#msg").html("");
        });

    });
</script>
</html>