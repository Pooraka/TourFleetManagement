<?php

include_once '../commons/session.php';
include_once '../model/bus_model.php';
include_once '../model/service_station_model.php';

//get user information from session
$userSession=$_SESSION["user"];

$busObj = new Bus();
$busResult = $busObj->getAllBusesToService();

$serviceStationObj = new ServiceStation();
$serviceStationResult = $serviceStationObj->getServiceStations();
?>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bus Maintenance</title>
    <?php include_once "../includes/bootstrap_css_includes.php"?>
</head>
<body>
    <div class="container">
        <?php $pageName="Bus Maintenance - Initiate Service" ?>
        <?php include_once "../includes/header_row_includes.php";?>
        <div class="col-md-3">
            <ul class="list-group">
                <a href="add-service-station.php" class="list-group-item">
                    <span class="glyphicon glyphicon-plus"></span> &nbsp;
                    Add Service Station
                </a>
                <a href="view-service-stations.php" class="list-group-item">
                    <span class="glyphicon glyphicon-search"></span> &nbsp;
                    View Service Stations
                </a>
                <a href="initiate-service.php" class="list-group-item">
                    <span class="fa-solid fa-wrench"></span> &nbsp;
                    Initiate Service
                </a>
                <a href="view-ongoing-services.php" class="list-group-item">
                    <span class="fa-solid fa-gear fa-spin"></span> &nbsp;
                    View Ongoing Services
                </a>
                <a href="service-history.php" class="list-group-item">
                    <span class="fa fa-list-alt"></span> &nbsp;
                    Service History
                </a>
            </ul>
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
                            <?php   while($busRow = $busResult->fetch_assoc()){ ?>
                                        
                            <option value="<?php echo $busRow['bus_id'];?>"><?php echo $busRow['vehicle_no'];?></option>
                            
                            <?php } ?>
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
            </div>
        </form>
    </div>
</body>
<script src="../js/jquery-3.7.1.js"></script>
</html>