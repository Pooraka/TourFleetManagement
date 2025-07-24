<?php

include_once '../commons/session.php';
include_once '../model/service_station_model.php';

//get user information from session
$userSession=$_SESSION["user"];

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
        <?php $pageName="Bus Maintenance - View Service Stations" ?>
        <?php include_once "../includes/header_row_includes.php";?>
        <div class="col-md-3">
            <?php include_once "../includes/bus_maintenance_functions.php"; ?>
        </div>
        <form action="../controller/service_station_controller.php?status=add_service_station" method="post" enctype="multipart/form-data">
            <div class="col-md-9">
                <div class="row">
                    <div class="col-md-6 col-md-offset-3">
                        <?php
                        if (isset($_GET["msg"]) && isset($_GET["success"]) && $_GET["success"]==true) {

                            $msg = base64_decode($_GET["msg"]);
                            ?>
                            <div class="row">
                                <div class="alert alert-success" style="text-align:center">
                                    <?php echo $msg; ?>
                                </div>
                            </div>
                            <?php
                        }
                        elseif(isset($_GET["msg"])){
                            
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
                        <table class="table" id="stationtable">
                            <thead>
                                <tr>
                                    <th>Station Name</th>
                                    <th>Address</th>
                                    <th>Mobile</th>
                                    <th>Landline</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php   while($serviceStationRow = $serviceStationResult->fetch_assoc()){
                                            
                                            $serviceStationId = $serviceStationRow['service_station_id'];
                                            
                                            $serviceStationContactResult = $serviceStationObj->getServiceStationContact($serviceStationId);
                                            
                                            $serviceStationContactRow1= $serviceStationContactResult->fetch_assoc();
                                            $serviceStationContactRow2= $serviceStationContactResult->fetch_assoc();
                                            
                                            $mobile = "Not Available";
                                            $landline = "Not Available";
                                            
                                            if($serviceStationContactRow1!=null && $serviceStationContactRow1['contact_type']==1){
                                                $mobile = $serviceStationContactRow1['service_station_contact_number'];
                                            }
                                            elseif ($serviceStationContactRow1!=null && $serviceStationContactRow1['contact_type']==2) {
                                                $landline = $serviceStationContactRow1['service_station_contact_number'];
                                            }
                                            
                                            if($serviceStationContactRow2!=null && $serviceStationContactRow2['contact_type']==1){
                                                $mobile = $serviceStationContactRow2['service_station_contact_number'];
                                            }
                                            elseif ($serviceStationContactRow2!=null && $serviceStationContactRow2['contact_type']==2) {
                                                $landline = $serviceStationContactRow2['service_station_contact_number'];
                                            }
                                            
                                            $serviceStationId = base64_encode($serviceStationId);
                                            
                                ?>
                                
                                <tr>
                                    <td><?php echo htmlspecialchars($serviceStationRow['service_station_name']);?></td>
                                    <td><?php echo htmlspecialchars($serviceStationRow['address']);?></td>
                                    <td><?php echo $mobile; //No need to sanitize as data submitted to a restricted format?></td>
                                    <td><?php echo $landline; //No need to sanitize as data submitted to a restricted format?></td>
                                    <td>
                                        <a href="edit-service-station.php?station_id=<?php echo $serviceStationId; ?>" 
                                           class="btn btn-warning" style="margin:2px;display:<?php echo checkPermissions(116); ?>">
                                            <span class="glyphicon glyphicon-pencil"></span>
                                            Edit
                                        </a>
                                        <a href="../controller/service_station_controller.php?status=remove_station&station_id=<?php echo $serviceStationId; ?>" 
                                           class="btn btn-danger" style="margin:2px;display:<?php echo checkPermissions(117); ?>">
                                            <span class="glyphicon glyphicon-trash"></span>
                                            Remove
                                        </a> 
                                    </td>
                                </tr>
                                <?php             
                                }?>
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

        $("#stationtable").DataTable();
    });
</script>
</html>