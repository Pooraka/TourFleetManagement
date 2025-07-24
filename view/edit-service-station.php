<?php

include_once '../commons/session.php';
include_once '../model/service_station_model.php';

//get user information from session
$userSession=$_SESSION["user"];

$serviceStationId = $_GET["station_id"];
$serviceStationId = base64_decode($serviceStationId);

$serviceStationObj = new ServiceStation();

$serviceStationResult = $serviceStationObj->getServiceStation($serviceStationId);
$serviceStationRow = $serviceStationResult->fetch_assoc();

$serviceStationContactResult = $serviceStationObj->getServiceStationContact($serviceStationId);

$mobileRow=$serviceStationContactResult->fetch_assoc();
$landlineRow=$serviceStationContactResult->fetch_assoc();

if($mobileRow!=null && $mobileRow['contact_type']==2){
    $landlineRow=$mobileRow;
    $mobileRow=null;
}

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
        <?php $pageName="Bus Maintenance - Edit Service Station" ?>
        <?php include_once "../includes/header_row_includes.php";?>
        <div class="col-md-3">
            <?php include_once "../includes/bus_maintenance_functions.php"; ?>
        </div>
        <form action="../controller/service_station_controller.php?status=update_service_station" method="post" enctype="multipart/form-data">
            <div class="col-md-9">
                <div class="row">
                    <div id="msg" class="col-md-offset-3 col-md-6" style="text-align:center;">
                        <?php if(isset($_GET["msg"])){ ?>
                        
                                <script>
                                    var msgElement = document.getElementById("msg");
                                    msgElement.classList.add("alert", "alert-danger");
                                </script>
                                
                                <b> <p> <?php echo base64_decode($_GET["msg"]);?></p></b>
                                <?php
                            }
                        ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-2">
                        <label class="control-label">Station Name</label>
                    </div>
                    <div class="col-md-5">
                        <input type="hidden" name="service_station_id" value="<?php echo $serviceStationId;?>" />
                        <input type="text" value="<?php echo $serviceStationRow['service_station_name'] ;?>" class="form-control" name="stationname" id="stationname"/>
                    </div>
                    <div class="col-md-2">
                        <label class="control-label">Mobile</label>
                    </div>
                    <div class="col-md-3">
                        <input type="text" value="<?php if($mobileRow!=null){ echo $mobileRow['service_station_contact_number'];}?>" class="form-control" name="mobile" id="mobile"/>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        &nbsp;
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-2">
                        <label class="control-label">Address</label>
                    </div>
                    <div class="col-md-5">
                        <textarea id="address" name="address" rows="2" class="form-control"><?php echo $serviceStationRow['address'] ;?></textarea>
                    </div>
                    <div class="col-md-2">
                        <label class="control-label">Landline</label>
                    </div>
                    <div class="col-md-3">
                        <input type="text" value="<?php if($landlineRow!=null) {echo $landlineRow['service_station_contact_number'];}?>" class="form-control" name="landline" id="landline"/>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        &nbsp;
                    </div>
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
<script src="../js/serviceStationValidation.js"></script>
</html>