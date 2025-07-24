<?php

include_once '../commons/session.php';
include_once '../model/purchase_order_model.php';

//get user information from session
$userSession=$_SESSION["user"];

$poObj = new PurchaseOrder();
$poResult = $poObj->getPOToAddSpareParts();
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
        <?php $pageName="Spare Part Management - Add Spare Parts" ?>
        <?php include_once "../includes/header_row_includes.php";?>
        <div class="col-md-3">
            <?php include_once "../includes/spareparts_functions.php"; ?>
        </div>
        <form action="../controller/grn_controller.php?status=add_grn" method="post" enctype="multipart/form-data">
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
                        <div class="panel-heading">
                            <h3 style="margin:0px">Purchase Order Information</h3>
                        </div>
                        <div class="panel-body" id="poinfo">
                            <span>Select The Purchase Order</span>
                        </div>
                    </div>
                </div>
                <div class="row">
                    &nbsp;
                </div>
                <div class="row">
                    <div class="col-md-3">
                        <label class="control-label">Purchase Order</label>
                    </div>
                    <div class="col-md-3">
                        <select name="po_id" id="po_id" class="form-control">
                            <option value="">------------------</option>
                            <?php
                                while($poRow=$poResult->fetch_assoc()){
                                    ?>
                            <option value="<?php echo $poRow['po_id'];?>">
                                <?php echo $poRow['po_number'];?>
                            </option>
                            <?php
                                }
                                ?>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="control-label">Quantity Received</label>
                    </div>
                    <div class="col-md-3">
                        <input type="number" class="form-control" name="quantity_received" min="1" step="1"/>
                    </div>
                </div>
                <div class="row">
                    &nbsp;
                </div>
                <div class="row">
                    <div class="col-md-2">
                        <label class="control-label">Notes</label>
                    </div>
                    <div class="col-md-4">
                        <textarea id="grn_notes" name="grn_notes" rows="2" class="form-control" value="No Notes"></textarea>
                    </div>
                </div>
                <div class="row">
                    &nbsp;
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
<script>
    $(document).ready(function () {
        
        $("#po_id").change(function () {
            
            var poId = $("#po_id").val();
            var url = "../controller/purchase_order_controller.php?status=load_purchase_order_add_sparepart_page";

            $.post(url, {poId: poId}, function (data) {

                $("#poinfo").html(data);

            });
        });
    });
</script>
</html>