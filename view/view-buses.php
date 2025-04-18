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
        <?php $pageName="Bus Management" ?>
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
            
            <div class="row">
                <div class="col-md-12">
                    <table class="table" id="bustable">
                        <thead>
                            <tr>
                                <th>Vehicle No</th>
                                <th>Manufacturer</th>
                                <th>Model</th>
                                <th>Year</th>                                
                                <th>Passenger</br>Capacity</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                
                                while($busRow = $busResult->fetch_assoc()){
                                    
                                    $status = 'Operational';
                                    $bus_id = $busRow['bus_id'];
                                    $bus_id = base64_encode($bus_id);
                                    
                                ?>
                            
                                    <tr>
                                        <td><?php echo $busRow['registration_number'];?></td>
                                        <td><?php echo $busRow['manufacturer'];?></td>
                                        <td><?php echo $busRow['model'];?></td>
                                        <td><?php echo $busRow['year'];?></td>
                                        <td><?php echo $busRow['capacity'];?></td>
                                        <td><?php echo $status;?></td>
                                        <td>
                                            <a href="view-bus.php?bus_id=<?php echo $bus_id;?>" class="btn btn-info" style="margin:2px">
                                                <span class="glyphicon glyphicon-search"></span>                                                  
                                                View
                                            </a>
                                            <a href="edit-bus.php?bus_id=<?php echo $bus_id;?>" class="btn btn-warning" style="margin:2px">
                                                <span class="glyphicon glyphicon-pencil"></span>
                                                Edit
                                            </a>
                                            <a href="../controller/bus_controller.php?status=remove&bus_id=<?php echo $bus_id; ?>" class="btn btn-danger" style="margin:2px">
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