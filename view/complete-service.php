<?php

include_once '../commons/session.php';
include_once '../model/service_detail_model.php';
include_once '../model/bus_model.php';
include_once '../model/service_station_model.php';

//get user information from session
$userSession=$_SESSION["user"];

$serviceId = $_GET['service_id'];
$serviceId = base64_decode($serviceId);

$serviceDetailObj = new ServiceDetail();

$serviceDetailResult = $serviceDetailObj->getServiceDetail($serviceId);
$serviceDetailRow = $serviceDetailResult->fetch_assoc();

//if someone tries to complete a service that is not ongoing (by URL) they will be directed to view ongoing services
if($serviceDetailRow['service_status']!=1){?>
    
    <script>
        window.location="/tourfleetmanagement/view/view-ongoing-services.php";
    </script>
    <?php
    exit();
}

$busId = $serviceDetailRow['bus_id'];
$serviceStationId = $serviceDetailRow['service_station_id'];

//bus Info
$busObj = new Bus();
$busResult = $busObj->getBus($busId);
$busRow = $busResult->fetch_assoc();

//Service Station Info
$serviceStationObj = new ServiceStation();
$serviceStationResult = $serviceStationObj->getServiceStation($serviceStationId);
$serviceStationRow = $serviceStationResult->fetch_assoc();
?>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bus Maintenance</title>
    <link rel="stylesheet" type="text/css" href="../css/dataTables.bootstrap.min.css"/>
    <?php include_once "../includes/bootstrap_css_includes.php"?>
</head>
<body>
    <div class="container">
        <?php $pageName = "Bus Maintenance - Complete Service" ?>
        <?php include_once "../includes/header_row_includes.php"; ?>
        <div class="col-md-3">
            <?php include_once "../includes/bus_maintenance_functions.php"; ?>
        </div>
        <form id="completeServiceForm" action="../controller/service_detail_controller.php?status=complete_service" method="post" enctype="multipart/form-data">
            <div class="col-md-9">
                <div class="row">
                    <div class="col-md-6 col-md-offset-3" id="msg" style="text-align:center;">
                        <?php
                        if (isset($_GET["msg"]) && isset($_GET["success"]) && $_GET["success"] == true) {

                            $msg = base64_decode($_GET["msg"]); //Success message is decoded from base64
                            ?>
                            <div class="row">
                                <div class="alert alert-success" style="text-align:center">
                                    <?php echo $msg; ?>
                                </div>
                            </div>
                            <?php
                        } elseif (isset($_GET["msg"])) {

                            $msg = base64_decode($_GET["msg"]); //Error message is decoded from base64
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
                            <h3 style="margin:0px">Service Information</h3>
                        </div>
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-md-3" style="margin-bottom: 10px">
                                    <span class="fa-solid fa-bus"></span>&nbsp;<b>Vehicle Number</b>
                                    </br>
                                    <span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $busRow['vehicle_no']; ?> </span>
                                </div>
                                <div class="col-md-3" style="margin-bottom: 10px">
                                    <span class="fa-solid fa-shop"></span>&nbsp;<b>Service Station</b>
                                    </br>
                                    <span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $serviceStationRow['service_station_name']; ?></span>
                                </div>
                                <div class="col-md-3" style="margin-bottom: 10px">
                                    <span class="fa-solid fa-calendar-day"></span>&nbsp;<b>Service Start Date</b>
                                    </br>
                                    <span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $serviceDetailRow['start_date'] ?></span>
                                </div>
                                <div class="col-md-3" style="margin-bottom: 10px">
                                    <span class="fa-solid fa-road"></span>&nbsp;<b>Mileage At Service</b>
                                    </br>
                                    <span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo number_format($serviceDetailRow['mileage_at_service'], 0) ?> Km</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-3">
                        <label class="control-label">Service Cost (LKR)</label>
                    </div>
                    <div class="col-md-2">
                        <input type="number" class="form-control" name="cost" id="cost" step="0.01" inputmode="decimal"/>
                    </div>
                    <div class="col-md-3">
                        <label class="control-label">Attach Service Invoice</label>
                    </div>
                    <div class="col-md-4">
                        <input type="file" class="form-control" name="invoice" id="invoice"/>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        &nbsp;
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-3">
                        <label class="control-label">Invoice Number</label>
                    </div>
                    <div class="col-md-2">
                        <input type="text" id="invoice_number" class="form-control" name="invoice_number"/>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12"> 
                        <input type="hidden" name="service_id" value="<?php echo $serviceId; ?>"/>
                        <input type="hidden" name="mileage_at_service" value="<?php echo $serviceDetailRow['mileage_at_service']; ?>"/>
                        <input type="hidden" name="bus_id" value="<?php echo $busId; ?>"/>
                        &nbsp;
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-offset-3 col-md-6">
                        <input type="submit" class="btn btn-success" value="Complete"/>
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
    $(document).ready(function() {
        // Handle form submission
        $("#completeServiceForm").on("submit", function(event) {
            event.preventDefault(); // Prevent the default form submission

            var cost = parseFloat($("#cost").val());
            var invoiceNumber = $("#invoice_number").val();
            var invoiceFile = $("#invoice").val();

            if(isNaN(cost) || cost <= 0) {
                $("#msg").addClass("alert alert-danger");
                $("#msg").html("<b>Please enter a valid service cost.</b>");
                return false;
            }


            if(invoiceNumber == "") {
                $("#msg").addClass("alert alert-danger");
                $("#msg").html("<b>Please enter the invoice number.</b>");
                return false;
            }

            if(invoiceFile == "") {
                $("#msg").addClass("alert alert-danger");
                $("#msg").html("<b>Please attach the service invoice file.</b>");
                return false;
            }

            // If all validations pass, show the confirmation modal
            $("#confirmationModal").modal('show');

            // Set up the confirmation button to perform the actual submission
            $("#confirmActionBtn").off("click").on("click", function() {
                $("#completeServiceForm").off("submit").submit();
            });
        });

        $("#completeServiceForm").on("reset", function() {
            $("#msg").removeClass("alert alert-danger");
            $("#msg").html("");
        });

        $("#cost").on("blur", function() {
            var cost = parseFloat($(this).val());
            if(isNaN(cost) || cost <= 0) {

                alert("Please enter a valid service cost.");
                $(this).val("");
            }
        });
    });

</script>
</html>