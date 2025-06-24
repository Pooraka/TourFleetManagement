<?php

include_once '../commons/session.php';
include_once '../model/service_detail_model.php';
include_once '../model/bus_model.php';

//get user information from session
$userSession=$_SESSION["user"];

$serviceDetailObj = new ServiceDetail();
$serviceDetailResult = $serviceDetailObj->getPastServices();

$busObj = new Bus();
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
        <?php $pageName="Bus Maintenance - Service History" ?>
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
                                    <th>Serviced Date</th>
                                    <th>Serviced Mileage</th>
                                    <th>Invoice</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php   while($serviceDetailRow = $serviceDetailResult->fetch_assoc()){
                                            
                                            $busId = $serviceDetailRow['bus_id'];
                                            $busResult = $busObj->getBus($busId);
                                            $busRow = $busResult->fetch_assoc();
                                            $serviceStatus = $serviceDetailRow['service_status'];
                                            $statusDisplay = match($serviceStatus){
                                                '-1'=>'Cancelled',
                                                '1'=>'Ongoing',
                                                '2'=>'Completed',
                                                '3'=>'Completed & Paid'
                                            };
                                            
                                            $statusDisplayClass = match($serviceStatus){
                                                '-1'=>'label label-danger',
                                                '1'=>'label label-warning',
                                                '2'=>'label label-success',
                                                '3'=>'label label-primary'
                                            };
                                            
                                            $serviceId = $serviceDetailRow['service_id'];
                                            $serviceId = base64_encode($serviceId);
                                ?>
                                <tr>
                                    <td><?php echo $busRow['vehicle_no'];?></td>
                                    <td><?php echo $servicedDate = ($serviceDetailRow['completed_date']=="")?"Not Applicable":$serviceDetailRow['completed_date'];?> </td>
                                    <td><?php echo number_format($serviceDetailRow['mileage_at_service'],0);?>&nbsp; Km </td>
                                    <td><?php echo $serviceDetailRow['invoice_number'];?></td>
                                    <td><span class="<?php echo $statusDisplayClass;?>"><?php echo $statusDisplay;?></span> </td>
                                    <td>
                                        <a href="view-service-record.php?service_id=<?php echo $serviceId; ?>" class="btn btn-info" style="margin:2px">
                                            <span class="glyphicon glyphicon-search"></span>                                                  
                                            View
                                        </a>
                                        <?php if($serviceStatus==2){ ?>
                                        <a href="edit-service-record.php?service_id=<?php echo $serviceId; ?>" class="btn btn-warning" style="margin:2px">
                                            <span class="glyphicon glyphicon-pencil"></span>
                                            Edit
                                        </a>
                                        <?php } ?>
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
        </form>
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