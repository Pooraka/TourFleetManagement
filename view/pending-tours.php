<?php

include_once '../commons/session.php';
include_once '../model/tour_model.php';


//get user information from session
$userSession=$_SESSION["user"];

$tourObj = new Tour();
$tourResult = $tourObj->getOngoingTours();

?>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tour Management</title>
    <link rel="stylesheet" type="text/css" href="../css/dataTables.bootstrap.min.css"/>
    <?php include_once "../includes/bootstrap_css_includes.php"?>
</head>
<body>
    <div class="container">
        <?php $pageName="Tour Management - Pending Tours" ?>
        <?php include_once "../includes/header_row_includes.php";?>
        <div class="col-md-3">
            <ul class="list-group">
                <a href="add-tour.php" class="list-group-item">
                    <span class="glyphicon glyphicon-plus"></span> &nbsp;
                    Add Tour
                </a>
                <a href="pending-tours.php" class="list-group-item">
                    <span class="glyphicon glyphicon-search"></span> &nbsp;
                    Pending Tours
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
                    <table class="table" id="tourtable">
                        <thead>
                            <tr>
                                <th>Customer</th>
                                <th>Start Date</th>
                                <th>End Date</th>
                                <th>Destination</th>
                                <th>Invoice No</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while($tourRow = $tourResult->fetch_assoc()){ ?>
                            <tr>
                                <td><?php echo $tourRow['customer_fname']." ".$tourRow['customer_lname'];?></td>
                                <td><?php echo $tourRow['start_date'];?></td>
                                <td><?php echo $tourRow['end_date'];?></td>
                                <td><?php echo $tourRow['destination'];?></td>
                                <td><?php echo $tourRow['invoice_number'];?></td>
                                <td>
                                    <a href="#" data-toggle="modal" onclick="loadTour(<?php echo $tourRow['tour_id'];?>)" data-target="#completeTourModal" class="btn btn-xs btn-success" style="margin:2px">
                                        <span class="glyphicon glyphicon-ok"></span>                                                  
                                        Complete
                                    </a>
                                    <a href="#" data-toggle="modal" onclick="loadTourBusList(<?php echo $tourRow['tour_id'];?>)" data-target="#bus_list" class="btn btn-xs btn-info" style="margin:2px">
                                        <span class="glyphicon glyphicon-ok"></span>                                                  
                                        View Assigned Buses
                                    </a>
                                    <a href="../controller/tour_controller.php?status=cancel_tour&tour_id=<?php echo base64_encode($tourRow['tour_id']);?>" class="btn btn-xs btn-danger" style="margin:2px">
                                        <span class="glyphicon glyphicon-remove"></span>                                                  
                                        Cancel
                                    </a>
                                </td>
                            </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</body>
<div class="modal fade" id="completeTourModal" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="../controller/tour_controller.php?status=complete_tour" method="post">
                <div class="modal-header"><b><h4>Complete Tour</h4></b></div>
            <div class="modal-body">
                <div id="display_data">
                </div>
            </div>
            <div class="modal-footer">
                <input type="submit" class="btn btn-success" value="Submit"/>
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
            </form>
        </div>
    </div>
</div>
<div class="modal fade" id="bus_list" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
                <div class="modal-header"><b><h4>Bus List</h4></b></div>
            <div class="modal-body">
                <div id="display_bus_list">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<script src="../js/datatable/jquery-3.5.1.js"></script>
<script src="../js/datatable/jquery.dataTables.min.js"></script>
<script src="../js/datatable/dataTables.bootstrap.min.js"></script>
<script src="../bootstrap/js/bootstrap.min.js"></script>
<script>
    $(document).ready(function(){

        $("#tourtable").DataTable();
    });
    
    function loadTour(tourId){
        var url ="../controller/tour_controller.php?status=load_tour";

        $.post(url,{tourId:tourId},function(data){
            $("#display_data").html(data).show();
        });
    }
    
    function loadTourBusList(tourId){
        var url ="../controller/tour_controller.php?status=load_tour_bus_list";

        $.post(url,{tourId:tourId},function(data){
            $("#display_bus_list").html(data).show();
            
            $("#tour_bus_list_table").DataTable();
        });
    }
</script>
</html>