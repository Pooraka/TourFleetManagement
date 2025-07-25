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
                            <option value="">Select Purchase Order</option>
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
                        <input type="number" class="form-control" id="quantity_received" name="quantity_received" min="1" step="1" disabled/>
                        <span id="qty_error_msg" style="color: red; font-size: 12px;"></span>
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
                        <textarea id="grn_notes" name="grn_notes" rows="2" class="form-control" value="No Notes" disabled></textarea>
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

        $("form").submit(function (){

            var poId = $("#po_id").val();
            var quantityReceived = $("#quantity_received").val();
            var grnNotes = $("#grn_notes").val();

            if (poId == "") {
                $("#msg").addClass("alert alert-danger");
                $("#msg").html("<b>Select A Purchase Order</b>");
                return false;
            }
            if (quantityReceived == "" || quantityReceived <= 0) {
                $("#msg").addClass("alert alert-danger");
                $("#msg").html("<b>Quantity Received Should Be Higher Than 0</b>");
                return false;
            }
            if (grnNotes == "") {
                $("#msg").addClass("alert alert-danger");
                $("#msg").html("<b>GRN Notes Cannot Be Empty</b>");
                return false;
            }
        });

        $("form").on("reset", function () {
            $("#msg").removeClass("alert alert-danger");
            $("#msg").html("");

            $("#poinfo").html("<span>Select The Purchase Order</span>");
            $("#quantity_received").val("").prop("disabled", true);
            $("#grn_notes").val("").prop("disabled", true);
            $("#qty_error_msg").html("");
        });

        
        
        $("#po_id").change(function () {

            $("#qty_error_msg").html("");
            
            var poId = $("#po_id").val();

            if (poId == "") {
                $("#poinfo").html("<span>Select The Purchase Order</span>");
                $("#quantity_received").attr("max", "").val("").prop("disabled", true);
                $("#grn_notes").val("").prop("disabled", true);
                return;
            }

            var url = "../controller/purchase_order_controller.php?status=load_purchase_order_add_sparepart_page";

            $.post(url, {poId: poId}, function (data) {

                $("#poinfo").html(data);

                // Find the div with our data attribute and read the value
                var maxQty = $("#poinfo").find("#po_data_container").data("max-quantity");

                // Set the 'max' attribute of the quantity input
                $("#quantity_received").attr("max", maxQty);
                
                // Enable the quantity input field
                $("#quantity_received").prop("disabled", false);

                // Enable the notes textarea
                $("#grn_notes").prop("disabled", false);

                $("#quantity_received").on("keyup input", function () {

                
                var quantityEntered = parseFloat($(this).val()); 
                var maxQty = parseFloat($(this).attr("max"));

                // Check if the entered value is a valid number and within the allowed range
                if (isNaN(quantityEntered) || quantityEntered <= 0 || quantityEntered > maxQty) {
                    $("#qty_error_msg").html("Maximum quantity should be " + maxQty);
                    $(this).val(""); // Clear the input if invalid
                } else {
                    $("#qty_error_msg").html(""); // Clear the message if input is valid
                }
            });

            });
        });
    });
</script>
</html>