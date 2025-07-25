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
        <form action="../controller/sparepart_controller.php?status=register_sparepart" method="post" enctype="multipart/form-data">
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
<script src="../js/jquery-3.7.1.js"></script>
<script>
    $(document).ready(function () {

        $("form").submit(function () {

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

        });

        $("form").on("reset", function () {
            $("#msg").removeClass("alert alert-danger");
            $("#msg").html("");
        });

    });
</script>
</html>