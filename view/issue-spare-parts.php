<?php

include_once '../commons/session.php';
include_once '../model/sparepart_model.php';
include_once '../model/bus_model.php';


//get user information from session
$userSession=$_SESSION["user"];

$partId = base64_decode($_GET["part_id"]);

$sparePartObj = new SparePart();
$sparePartResult = $sparePartObj->getSparePart($partId);
$sparePartRow = $sparePartResult->fetch_assoc();

$busObj = new Bus();
$busResult = $busObj->getAllBuses();

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
        <?php $pageName="Spare Part Management - Issue Spare Parts" ?>
        <?php include_once "../includes/header_row_includes.php";?>
        <div class="col-md-3">
            <ul class="list-group">
                <a href="register-spareparts.php" class="list-group-item">
                    <span class="glyphicon glyphicon-search"></span> &nbsp;
                    Register Spare Parts
                </a>
                <a href="spare-part-types.php" class="list-group-item">
                    <span class="glyphicon glyphicon-search"></span> &nbsp;
                    View Spare Part Types
                </a>
                <a href="add-spare-parts.php" class="list-group-item">
                    <span class="glyphicon glyphicon-search"></span> &nbsp;
                    Add Spare Parts
                </a>
            </ul>
        </div>
        <form action="../controller/sparepart_controller.php?status=issue_spare_parts" method="post" enctype="multipart/form-data">
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
                            <h3 style="margin:0px">Spare Part Information</h3>
                        </div>
                        <div class="panel-body" id="poinfo">
                            <div class="row">
                                <div class="col-md-3" style="margin-bottom: 10px">
                                    <span class="fas fa-hashtag"></span>&nbsp;<b>Part Number</b>
                                    </br>
                                    <span><?php echo $sparePartRow['part_number']; ?> </span>
                                </div>
                                <div class="col-md-3" style="margin-bottom: 10px">
                                    <span class="fas fa-wrench"></span>&nbsp;<b>Part Name</b>
                                    </br>
                                    <span><?php echo $sparePartRow['part_name']; ?> </span>
                                </div>
                                <div class="col-md-3" style="margin-bottom: 10px">
                                    <span class="fas fa-boxes"></span>&nbsp;<b>Quantity On Hand</b>
                                    </br>
                                    <span><?php echo number_format($sparePartRow['quantity_on_hand'],0); ?> </span>
                                </div>
                                <div class="col-md-3" style="margin-bottom: 10px">
                                    <span class="fas fa-redo-alt"></span>&nbsp;<b>Re-Order Level</b>
                                    </br>
                                    <span><?php echo number_format($sparePartRow['reorder_level'], 0); ?> </span>
                                </div>
                            </div>
                            <div class="row">
                                &nbsp;
                            </div>
                            <div class="row">
                                <div class="col-md-12" style="margin-bottom: 10px">
                                    <span class="fas fa-info-circle"></span>&nbsp;<b>Description</b>
                                    </br>
                                    <span><?php echo $sparePartRow['description']; ?> </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    &nbsp;
                </div>
                <div class="row">
                    <div class="panel panel-info">
                        <div class="panel-heading">
                            <h3 style="margin:0px">Vehicle Details</h3>
                        </div>
                        <div class="panel-body" id="vehicleInfo">
                            Select The Vehicle To View The Details
                        </div>
                    </div>
                </div>
                <div class="row">
                    &nbsp;
                </div>
                <div class="row">
                    <div class="col-md-3">
                        <label class="control-label">Vehicle No</label>
                    </div>
                    <div class="col-md-3">
                        <select name="bus_id" id="bus_id" class="form-control">
                            <option value="">Select Vehicle</option>
                            <?php
                                while($busRow = $busResult->fetch_assoc()){
                                    ?>
                            <option value="<?php echo $busRow['bus_id'];?>">
                                <?php echo $busRow['vehicle_no'];?>
                            </option>
                            <?php
                                }
                                ?>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="control-label">Quantity To Issue</label>
                    </div>
                    <div class="col-md-3">
                        <input type="number" class="form-control" name="quantity_to_issue" min="1" step="1" max="<?php echo $sparePartRow['quantity_on_hand'];?>"/>
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
                        <textarea id="issue_notes" name="issue_notes" rows="1" class="form-control"></textarea>
                        <input type="hidden" value="<?php echo $partId;?>" name="part_id"/>
                    </div>
                    <div class="col-md-offset-2 col-md-4 text-right">
                        <button type=submit class="btn btn-primary"><i class="fas fa-check"></i> Submit</button>
                        <button type="reset" class="btn btn-danger"><i class="fas fa-undo"></i> Reset</button>
                    </div>
                </div>
                <div class="row">
                    &nbsp;
                </div>
            </div>
        </form>
    </div>
</body>
<script src="../js/jquery-3.7.1.js"></script>
<script>
$(document).ready(function() {

    $("#bus_id").on("change", function() {
        var busId = $(this).val();

        var url = "../controller/bus_controller.php?status=get_bus_details_to_issue_spare_parts";

        
        if (busId) {

            $.post(url, { busId: busId }, function(data) {
                $("#vehicleInfo").html(data);
            });
        }
    });
});
</script>
</html>