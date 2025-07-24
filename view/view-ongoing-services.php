<?php

include_once '../commons/session.php';
include_once '../model/service_detail_model.php';
include_once '../model/bus_model.php';
include_once '../model/service_station_model.php';

//get user information from session
$userSession=$_SESSION["user"];

$serviceDetailObj = new ServiceDetail();
$serviceDetailResult = $serviceDetailObj->getOngoingServices();

$busObj = new Bus();
$serviceStationObj = new ServiceStation();
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
        <?php $pageName="Bus Maintenance - View Ongoing Services" ?>
        <?php include_once "../includes/header_row_includes.php";?>
        <div class="col-md-3">
            <?php include_once "../includes/bus_maintenance_functions.php"; ?>
        </div>
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
                <div class="col-md-12">
                    <table class="table" id="servicetable">
                        <thead>
                            <tr>
                                <th>Vehicle</th>
                                <th>Mileage at Service</th>
                                <th>Service Station</th>
                                <th>Sent to Service On</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while($serviceDetailRow = $serviceDetailResult->fetch_assoc()){
                                
                                //get vehicle number
                                $busId = $serviceDetailRow['bus_id'];
                                $busResult = $busObj->getBus($busId);
                                $busRow = $busResult->fetch_assoc();
                                $vehicleNo = $busRow['vehicle_no'];
                                
                                //get service station name
                                $serviceStationId = $serviceDetailRow['service_station_id'];
                                $serviceStationResult = $serviceStationObj->getServiceStation($serviceStationId);
                                $serviceStationRow = $serviceStationResult->fetch_assoc();
                                $serviceStationName = $serviceStationRow['service_station_name'];
                                
                                $serviceId = $serviceDetailRow['service_id'];
                                $serviceId = base64_encode($serviceId);
                                ?>
                            
                            <tr>
                                <td><?php echo $vehicleNo;?></td>
                                <td><?php echo number_format($serviceDetailRow['mileage_at_service'],0);?>&nbsp;Km</td>
                                <td><?php echo $serviceStationName;?></td>
                                <td><?php echo $serviceDetailRow['start_date'];?></td>
                                <td>
                                    <a href="complete-service.php?service_id=<?php echo $serviceId; ?>" 
                                       class="btn btn-success" style="margin:2px;display:<?php echo checkPermissions(120); ?>">
                                        <span class="glyphicon glyphicon-ok"></span>
                                        Complete
                                    </a>
                                    <a href="../controller/service_detail_controller.php?status=cancel_service&service_id=<?php echo $serviceId; ?>" onclick="return confirm('Are you sure you want to cancel?')" 
                                       class="btn btn-danger" style="margin:2px;display:<?php echo checkPermissions(121); ?>">
                                        <span class="glyphicon glyphicon-remove"></span>
                                        Cancel
                                    </a> 
                                </td>
                            </tr>
                            
                            <?php
                                
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</body>
<script src="../js/datatable/jquery-3.5.1.js"></script>
<script src="../js/datatable/jquery.dataTables.min.js"></script>
<script src="../js/datatable/dataTables.bootstrap.min.js"></script>
<script>
    $(document).ready(function(){

        $("#servicetable").DataTable();

    });
</script>
</html>