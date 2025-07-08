<?php

include_once '../commons/session.php';
include_once '../model/bus_model.php';

//get user information from session
$userSession=$_SESSION["user"];

$busObj = new Bus();

$busResult = $busObj->getAllBuses();
?>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bus Management</title>
    <link rel="stylesheet" type="text/css" href="../css/dataTables.bootstrap.min.css"/>
    <?php include_once "../includes/bootstrap_css_includes.php"?>
</head>
<body>
    <div class="container">
        <?php $pageName="Bus Management - View Buses" ?>
        <?php include_once "../includes/header_row_includes.php";?>
        <div class="col-md-3">
            <ul class="list-group">
                <a href="add-bus.php" class="list-group-item">
                    <span class="glyphicon glyphicon-plus"></span> &nbsp;
                    Add Bus
                </a>
                <a href="view-buses.php" class="list-group-item">
                    <span class="glyphicon glyphicon-search"></span> &nbsp;
                    View Buses
                </a>
                <a href="generate-bus-reports.php" class="list-group-item">
                    <span class="glyphicon glyphicon-book"></span> &nbsp;
                    Generate Bus Reports
                </a>
            </ul>
        </div>
        <div class="col-md-9">
            <div class="row">
                <div class="col-md-6 col-md-offset-3">
                    <?php

                        if(isset($_GET["msg"])){

                            $msg = base64_decode($_GET["msg"]);
                    ?>
                            <div class="row">
                                <div class="alert alert-success" style="text-align:center">
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
                                    
                                    $status = match ((int)$busRow['bus_status']) {
                                                        0=> "Out of Service",
                                                        1=> "Operational",
                                                        2=> "Service is Due",
                                                        3=> "In Service",
                                                        4=> "Inspection Failed",
                                                    };
                                    $statusClass = match ((int)$busRow['bus_status']) {
                                                        0,4=> "label label-danger",
                                                        1=> "label label-success",
                                                        2=> "label label-default",
                                                        3=> "label label-warning",
                                                    };                
                                                    
                                    $busId = $busRow['bus_id'];
                                    $busId = base64_encode($busId);
                                    
                                ?>
                            
                                    <tr>
                                        <td><?php echo $busRow['vehicle_no'];?></td>
                                        <td><?php echo $busRow['make'];?></td>
                                        <td><?php echo $busRow['model'];?></td>
                                        <td><?php echo $busRow['capacity'];?></td>
                                        <td><?php echo $busRow['category_name'];?></td>
                                        <td><span class="<?php echo $statusClass;?>"><?php echo $status;?></span></td>
                                        <td>
                                            <a href="view-bus.php?bus_id=<?php echo $busId;?>" class="btn btn-xs btn-info" style="margin:2px">
                                                <span class="glyphicon glyphicon-search"></span>                                                  
                                                View
                                            </a>
                                            <a href="edit-bus.php?bus_id=<?php echo $busId;?>" class="btn btn-xs btn-warning" style="margin:2px">
                                                <span class="glyphicon glyphicon-pencil"></span>
                                                Edit
                                            </a>
                                            <a href="../controller/bus_controller.php?status=remove_bus&bus_id=<?php echo $busId; ?>" class="btn btn-xs btn-danger" style="margin:2px">
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