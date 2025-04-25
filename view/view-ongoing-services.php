<?php

include_once '../commons/session.php';

//get user information from session
$userSession=$_SESSION["user"];


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
            </ul>
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
                    <table class="table" id="bustable">
                        <thead>
                            <tr>
                                <th>Vehicle</br>No</th>
                                <th>Make</th>
                                <th>Model</th>                               
                                <th>Passenger</br>Capacity</th>
                                <th>Category</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                
                                while($busRow = $busResult->fetch_assoc()){
                                    
                                    $status = 'Operational';
                                    $busId = $busRow['bus_id'];
                                    $busId = base64_encode($busId);
                                    
                                ?>
                            
                                    <tr>
                                        <td><?php echo $busRow['vehicle_no'];?></td>
                                        <td><?php echo $busRow['make'];?></td>
                                        <td><?php echo $busRow['model'];?></td>
                                        <td><?php echo $busRow['capacity'];?></td>
                                        <td><?php echo $busRow['category_name'];?></td>
                                        <td><?php echo $status;?></td>
                                        <td>
                                            <a href="view-bus.php?bus_id=<?php echo $busId;?>" class="btn btn-info" style="margin:2px">
                                                <span class="glyphicon glyphicon-search"></span>                                                  
                                                View
                                            </a>
                                            <a href="edit-bus.php?bus_id=<?php echo $busId;?>" class="btn btn-warning" style="margin:2px">
                                                <span class="glyphicon glyphicon-pencil"></span>
                                                Edit
                                            </a>
                                            <a href="../controller/bus_controller.php?status=remove_bus&bus_id=<?php echo $busId; ?>" class="btn btn-danger" style="margin:2px">
                                                <span class="glyphicon glyphicon-trash"></span>
                                                Remove
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

        $("#bustable").DataTable();
    });
</script>
</html>