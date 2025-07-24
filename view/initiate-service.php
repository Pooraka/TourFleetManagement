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
        <form action="../controller/service_detail_controller.php?status=initiate_service" method="post" enctype="multipart/form-data">
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
<script src="../js/jquery-3.7.1.js"></script>
<script>
$(document).ready(function() {

    $("form").submit(function () {

        var busId = $("#bus_id").val();
        var serviceStationId = $("#service_station_id").val();
        var currentMileage = $("#currentmileage").val();

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

        if(currentMileage ==""){
            $("#msg").html("<b>Please enter the current mileage.</b>");
            $("#msg").addClass("alert alert-danger");
            return false;
        }   

    });
});

</script>
</html>