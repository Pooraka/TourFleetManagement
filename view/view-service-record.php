<?php

include_once '../commons/session.php';
include_once '../model/service_detail_model.php';
include_once '../model/bus_model.php';
include_once '../model/service_station_model.php';
include_once '../model/user_model.php';

//get user information from session
$userSession=$_SESSION["user"];

$serviceId = $_GET['service_id'];
$serviceId = base64_decode($serviceId);

$serviceDetailObj = new ServiceDetail();

$serviceDetailResult = $serviceDetailObj->getServiceDetail($serviceId);
$serviceDetailRow = $serviceDetailResult->fetch_assoc();

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

$serviceStatus = $serviceDetailRow['service_status'];

$statusDisplay = match ($serviceStatus) {
    -1 => 'Cancelled',
    1 => 'Ongoing',
    2 => 'Completed',
    3 => 'Completed & Paid',
};

$userObj = new User();

//Get Initiator Details
$initiatorUserId = $serviceDetailRow['initiated_by'];
$initiatorResult = $userObj->getUser($initiatorUserId);
$initiatorRow = $initiatorResult->fetch_assoc();

//Get the details who finalized
if($serviceStatus==2 || $serviceStatus==3 ){
    $finalizedByUserId = $serviceDetailRow['completed_by'];
}elseif ($serviceStatus==-1){
    $finalizedByUserId = $serviceDetailRow['cancelled_by'];
}

$finalizedByUserResult = $userObj->getUser($finalizedByUserId);
$finalizedByUserRow = $finalizedByUserResult->fetch_assoc();

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
        <?php $pageName="Bus Maintenance - View Service Record" ?>
        <?php include_once "../includes/header_row_includes.php";?>
        <div class="col-md-3">
            <?php include_once "../includes/bus_maintenance_functions.php"; ?>
        </div>
        <div class="col-md-9">
            <div class="row">
                <div class="col-md-6 col-md-offset-3" id="msg">
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
                        <h3 style="margin:0px">Service Information</h3>
                    </div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-3" style="margin-bottom: 10px">
                                <span class="fa-solid fa-bus"></span>&nbsp;<b>Vehicle Number</b>
                                </br>
                                <span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $busRow['vehicle_no'];?> </span>
                            </div>
                            <div class="col-md-3" style="margin-bottom: 10px">
                                <span class="glyphicon glyphicon-stats"></span>&nbsp;<b>Service Status</b>
                                </br>
                                <span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $statusDisplay;?></span>
                            </div>
                            <div class="col-md-3" style="margin-bottom: 10px">
                                <span class="fa fa-user"></span>&nbsp;<b>Service Initiated By</b>
                                </br>
                                <span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $initiatorRow['user_fname']." ".$initiatorRow['user_lname']; ?></span>
                            </div>
                            <div class="col-md-3" style="margin-bottom: 10px">
                                <span class="fa-solid fa-calendar"></span>&nbsp;<b>Service Started Date</b>
                                </br>
                                <span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $serviceDetailRow['start_date'] ?></span>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                &nbsp;
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3" style="margin-bottom: 10px">
                                <?php if($serviceStatus==2 || $serviceStatus==3){?>
                                <span class="fa fa-user"></span>&nbsp;<b>Completed By</b>
                                </br>
                                <span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $finalizedByUserRow['user_fname']." ".$finalizedByUserRow['user_lname'] ?></span>
                                <?php } elseif($serviceStatus==-1){ ?>
                                <span class="fa fa-user"></span>&nbsp;<b>Cancelled By</b>
                                </br>
                                <span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $finalizedByUserRow['user_fname']." ".$finalizedByUserRow['user_lname'] ?></span>
                                <?php } ?>
                            </div>
                            <div class="col-md-3" style="margin-bottom: 10px">
                                <?php if($serviceStatus==2 || $serviceStatus==3){?>
                                <span class="fa-solid fa-calendar-day"></span>&nbsp;<b>Service Completed Date</b>
                                </br>
                                <span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $serviceDetailRow['completed_date'] ?></span>
                                <?php } elseif($serviceStatus==-1){ ?>
                                <span class="fa-solid fa-calendar-day"></span>&nbsp;<b>Service Cancelled Date</b>
                                </br>
                                <span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $serviceDetailRow['cancelled_date'] ?></span>
                                <?php } ?>
                            </div>
                            <div class="col-md-3" style="margin-bottom: 10px">
                                <span class="fa-solid fa-road"></span>&nbsp;<b>Mileage At Service</b>
                                </br>
                                <span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo number_format($serviceDetailRow['mileage_at_service'],0) ?> Km</span>
                            </div>
                            <div class="col-md-3" style="margin-bottom: 10px">
                                <span class="fa-solid fa-shop"></span>&nbsp;<b>Service Station</b>
                                </br>
                                <span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $serviceStationRow['service_station_name'] ?></span>
                            </div>
                        </div>
                        <?php if($serviceStatus==2 || $serviceStatus==3){?>
                        <div class="row">
                            <div class="col-md-3" style="margin-bottom: 10px">
                                <span class="fas fa-coins"></span>&nbsp;<b>Service Cost</b>
                                </br>
                                <span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;LKR <?php echo number_format($serviceDetailRow['cost'],2) ?></span>
                            </div>
                            <div class="col-md-3" style="margin-bottom: 10px">
                                <span class="fas fa-file-invoice"></span>&nbsp;<b>Invoice Number</b>
                                </br>
                                <span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $serviceDetailRow['invoice_number']?></span>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3" style="margin-bottom: 10px">
                                <a href="../documents/busserviceinvoices/<?php echo $serviceDetailRow['invoice'];?>" target="_blank" class="btn btn-primary">
                                    <span class="glyphicon glyphicon-file"></span> View Invoice
                                </a>
                            </div>
                        </div>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>