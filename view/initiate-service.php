<?php

include_once '../commons/session.php';
include_once '../model/bus_model.php';
include_once '../model/service_station_model.php';

//get user information from session
$userSession=$_SESSION["user"];

$busObj = new Bus();
$busResult = $busObj->getAllBusesToService();

/*This queue is being created to show the bus pickup 
 * dropdown sorted based on priority */
$busPriorityQueue = new SplPriorityQueue();

while($busRow = $busResult->fetch_assoc()){
    
    $priority = match ((int) $busRow['bus_status']) {
        1,0=> 1, //Low priority (1) for operational buses
        2=> 9, //Service due priority level 9
        4=> 10, //Highest priority (10) for broken buses
    };
    
    //Insert bus array to a queue with a priority
    $busPriorityQueue->insert($busRow, $priority);
}

$serviceDueBusResult = $busObj->getServiceDueBuses();

$serviceStationObj = new ServiceStation();
$serviceStationResult = $serviceStationObj->getServiceStations();
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
        <?php $pageName="Bus Maintenance - Initiate Service" ?>
        <?php include_once "../includes/header_row_includes.php";?>
        <div class="col-md-3">
            <?php include_once "../includes/bus_maintenance_functions.php"; ?>
        </div>
        <form id="addServiceForm" action="../controller/service_detail_controller.php?status=initiate_service" method="post" enctype="multipart/form-data">
            <div class="col-md-9">
                <div class="row">
                    <div class="col-md-6 col-md-offset-3" id="msg" style="text-align:center;">
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
                        <label class="control-label">Vehicle Number</label>
                    </div>
                    <div class="col-md-3">
                        <select name="bus_id" id="bus_id" class="form-control" required="required">
                            <option value="">Select a Vehicle</option>
                            <?php foreach($busPriorityQueue as $bus){ 
                                
                                $displayText = $bus['vehicle_no'];
                                
                                if($bus['bus_status']==2){
                                    $displayText.=" (Service Due)";
                                }elseif($bus['bus_status']==4) {
                                    $displayText.=" (Inspection Failed)";
                                }
                            ?>
                            
                            <option value="<?php echo $bus['bus_id'];?>"><?php echo $displayText;?></option>
                            
                            <?php
                            }
                            ?>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="control-label">Service Station</label>
                    </div>
                    <div class="col-md-4">
                        <select name="service_station_id" id="service_station_id" class="form-control" required="required">
                            <option value="">Select a Service Station</option>
                            <?php   while($serviceStationRow = $serviceStationResult->fetch_assoc()){ ?>
                                        
                            <option value="<?php echo $serviceStationRow['service_station_id'];?>"><?php echo $serviceStationRow['service_station_name'];?></option>
                            
                            <?php } ?>
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        &nbsp;
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-3">
                        <label class="control-label">Current Mileage (Km)</label>
                    </div>
                    <div class="col-md-2">
                        <input type="number" class="form-control" name="currentmileage" id="currentmileage" placeholder="Ex: 21537"/>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12"> &nbsp; </div>
                </div>
                <div class="row">
                    <div class="col-md-12"> &nbsp; </div>
                </div>
                <div class="row">
                    <div class="col-md-offset-2 col-md-6">
                        <input type="submit" class="btn btn-primary" value="Submit"/>
                        <input type="reset" class="btn btn-danger" value="Reset"/>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        &nbsp;
                    </div>
                </div>
                <div class="row">
                    <div class="panel panel-info">
                        <div class="panel-heading">Service Due Vehicles</div>
                        <div class="panel-body">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Vehicle No</th>
                                        <th>Last Serviced Date</th>
                                        <th>Last Serviced Mileage (Km)</th>
                                        <th>Current Mileage (Km)</th>
                                        <th>Current Mileage As At</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php while($serviceDueBusRow=$serviceDueBusResult->fetch_assoc()){ ?>
                                    <tr>
                                        <td><?php echo $serviceDueBusRow['vehicle_no'];?></td>
                                        <td><?php echo $serviceDueBusRow['last_service_date'];?></td>
                                        <td style="text-align:right"><?php echo number_format($serviceDueBusRow['last_service_mileage_km'],0);?></td>
                                        <td style="text-align:right"><?php echo number_format($serviceDueBusRow['current_mileage_km'],0);?></td>
                                        <td><?php echo $serviceDueBusRow['current_mileage_as_at'];?></td>
                                    </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
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

    $("#addServiceForm").submit(function () {

        //prevent the form from submitting immediately
        event.preventDefault();

        var busId = $("#bus_id").val();
        var serviceStationId = $("#service_station_id").val();
        var currentMileage = parseInt($("#currentmileage").val(), 10);

        if(busId ==""){
            $("#msg").html("<b>Please select a bus to initiate service.</b>");
            $("#msg").addClass("alert alert-danger");
            return false;
        }

        if(serviceStationId ==""){
            $("#msg").html("<b>Please select a service station.</b>");
            $("#msg").addClass("alert alert-danger");
            return false;
        }

        if(isNaN(currentMileage) || currentMileage <= 0){
            $("#msg").html("<b>Please enter the current mileage.</b>");
            $("#msg").addClass("alert alert-danger");
            return false;
        }
        
        // If all validations pass, show the confirmation modal
        $("#confirmationModal").modal('show');
        // Set up the confirmation button to perform the actual submission
        $("#confirmActionBtn").off("click").on("click", function() {
            // Remove the handler to avoid this validation logic from running again in a loop
            $("#addServiceForm").off("submit").submit();
        });

    });
});

</script>
</html>