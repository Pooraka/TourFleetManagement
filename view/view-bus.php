<?php

include_once '../commons/session.php';
include_once '../model/bus_model.php';

//get user information from session
$userSession=$_SESSION["user"];

$busId = $_GET["bus_id"];
$busId = base64_decode($busId);

$busObj = new Bus();
$busResult = $busObj->getBus($busId);
$busRow = $busResult->fetch_assoc();

//Calculate Next Due Date
$lastServiceDate = $busRow['last_service_date'];
$serviceIntervalMonths = (int) $busRow['service_interval_months']; //get months as an integer
$lastServiceTimestamp = strtotime($lastServiceDate); 
$serviceDueDateTimestamp = strtotime("+".$serviceIntervalMonths." months",$lastServiceTimestamp);
$todayTimestamp = strtotime('today');

$serviceDueDate = ($serviceDueDateTimestamp<=$todayTimestamp)? "Service is due" : date('Y-m-d',$serviceDueDateTimestamp);
$serviceDueDateClass ="panel-success";

if($serviceDueDate=="Service is due"){
    $serviceDueDateClass ="panel-danger";
}

//Calculate Next Due in Kms
$lastServiceKM = (int) $busRow['last_service_mileage_km'];
$currentMileage = (int) $busRow['current_mileage_km'];
$serviceIntervalKM = (int) $busRow['service_interval_km'];
$serviceDueKm = $lastServiceKM + $serviceIntervalKM;
$remainingKmUntilService = ($serviceDueKm<=$currentMileage)? "Service is due" : number_format($serviceDueKm-$currentMileage,0);

$remainingKmUntilServiceClass ="panel-success";

if($remainingKmUntilService=="Service is due"){
    $remainingKmUntilServiceClass ="panel-danger";
}


$status = match((int)$busRow['bus_status']){
    
    -1=>"Removed",
    0=>"Out of Service",
    1=>"Operational",
    2=>"Service is Due",
    3=>"In Service",
    4=>"Inspection Failed"
};
    
$statusClass = match((int)$busRow['bus_status']){
    
    -1,0,2,4=>"panel-danger",
    1=>"panel-success",
    3=>"panel-warning",
};

if($busRow['bus_status']==3){
   
    $remainingKmUntilService="In Service";
    $remainingKmUntilServiceClass="panel-warning";
    $serviceDueDate = "In Service";
    $serviceDueDateClass = "panel-warning";
}

?>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bus Management</title>
    <?php include_once "../includes/bootstrap_css_includes.php"?>
</head>
<body>
    <div class="container">
        <?php $pageName="Bus Management - View Bus" ?>
        <?php include_once "../includes/header_row_includes.php";?>
        <div class="col-md-3">
            <?php include_once "../includes/bus_management_functions.php"; ?>
        </div>
        <form action="../controller/bus_controller.php?status=update_bus" method="post" enctype="multipart/form-data">
            <div class="col-md-9">
                <div class="panel panel-info" style="height:auto">
                    <div class="panel-heading">
                        <div class="row">
                            <div class="col-md-12" style="text-align:center">
                                <h2><b><?php echo $busRow['vehicle_no'];?></b></h2>
                                <h3 style="color:grey"><?php echo $busRow['make']." ".$busRow['model'];?></h3>
                            </div>
                        </div>
                    </div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-12">
                                <h4><span class="fa-solid fa-circle-info"></span> &nbsp; Bus Status</h4>
                            </div>
                            </br>
                            <div class="col-md-4">
                                <div class="panel <?php echo $statusClass;?>">
                                    <div class="panel-heading" style="text-align: center;">
                                        <span class="fa-solid fa-gauge-high" style="font-size: 20px; margin-bottom: 10px; display: block;"></span>
                                        <p style="margin-bottom: 5px; font-weight: bold;color:black">Status</p>
                                        <h4 style="margin-top: 0;"><?php echo $status;?></h4>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="panel <?php echo $serviceDueDateClass;?>">
                                    <div class="panel-heading" style="text-align: center;">
                                        <span class="fa-solid fa-clock" style="font-size: 20px; margin-bottom: 10px; display: block;"></span>
                                        <p style="margin-bottom: 5px; font-weight: bold;color:black">Next Service Due Date</p>
                                        <h4 style="margin-top: 0;"><?php echo $serviceDueDate;?></h4>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="panel <?php echo $remainingKmUntilServiceClass;?>">
                                    <div class="panel-heading" style="text-align: center;">
                                        <span class="fa-solid fa-road" style="font-size: 20px; margin-bottom: 10px; display: block;"></span>
                                        <p style="margin-bottom: 5px; font-weight: bold;color:black">Remaining Km Until Service</p>
                                        <h4 style="margin-top: 0;"><?php echo $remainingKmUntilService; ?></h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <hr/>
                        <div class="row">
                            <div class="col-md-6 col-sm-6">
                                <h4><span class="fa-solid fa-bus"></span> &nbsp; Bus Information</h4>
                                </br>
                                <span style="color:grey;font-size: 16px;">Vehicle No :</span>
                                <span style="font-size: 16px;"><?php echo $busRow['vehicle_no'];?></span>
                                </br>
                                </br>
                                <span style="color:grey;font-size: 16px;">Make :</span>
                                <span style="font-size: 16px;"><?php echo $busRow['make'];?></span>
                                </br>
                                </br>
                                <span style="color:grey;font-size: 16px;">Model :</span>
                                <span style="font-size: 16px;"><?php echo $busRow['model'];?></span>
                                </br>
                                </br>
                                <span style="color:grey;font-size: 16px;">Year :</span>
                                <span style="font-size: 16px;"><?php echo $busRow['year'];?></span>
                                </br>
                                </br>
                                <span style="color:grey;font-size: 16px;">Passenger Capacity :</span>
                                <span style="font-size: 16px;"><?php echo $busRow['capacity'];?></span>
                                </br>
                                </br>
                                <span style="color:grey;font-size: 16px;">AC Available :</span>
                                <span style="font-size: 16px;"><?php if($busRow['ac_available']=='Y'){echo "Yes";}else{echo "No";}?></span>
                                </br>
                                </br>
                                <span style="color:grey;font-size: 16px;">Category :</span>
                                <span style="font-size: 16px;"><?php echo $busRow['category_name'];?></span>
                                </br>
                                </br>
                            </div>
                            <div class="col-md-6 col-sm-6">
                                <h4><span class="fa-solid fa-wrench"></span> &nbsp; Maintenance Information</h4>
                                </br>
                                <span style="color:grey;font-size: 16px;">Current Mileage (Km) :</span>
                                <span style="font-size: 16px;"><?php echo number_format($currentMileage,0);?></span>
                                </br>
                                </br>
                                <span style="color:grey;font-size: 16px;">Current Mileage As At :</span>
                                <span style="font-size: 16px;"><?php echo $busRow['current_mileage_as_at'];?></span>
                                </br>
                                </br>
                                <span style="color:grey;font-size: 16px;">Last Service Date :</span>
                                <span style="font-size: 16px;"><?php echo $lastServiceDate;?></span>
                                </br>
                                </br>
                                <span style="color:grey;font-size: 16px;">Last Service Mileage (Km) :</span>
                                <span style="font-size: 16px;"><?php echo number_format($lastServiceKM,0);?></span>
                                </br>
                                </br>
                                <span style="color:grey;font-size: 16px;">Service Interval (Km) :</span>
                                <span style="font-size: 16px;"><?php echo number_format($serviceIntervalKM,0);?></span>
                                </br>
                                </br>
                                <span style="color:grey;font-size: 16px;">Service Interval (Months) :</span>
                                <span style="font-size: 16px;"><?php echo $serviceIntervalMonths;?></span>
                                </br>
                                </br>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</body>
<script src="../js/jquery-3.7.1.js"></script>
</html>