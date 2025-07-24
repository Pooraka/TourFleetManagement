<?php

include_once '../commons/session.php';
include_once '../model/tour_model.php';


//get user information from session
$userSession=$_SESSION["user"];

$dateFrom = "";
$dateTo = "";
$tourStatus ="";

$tourObj = new Tour();
$tourResult = $tourObj->getPastToursFiltered($dateFrom,$dateTo,$tourStatus);
?>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tour Management</title>
    <?php include_once "../includes/bootstrap_css_includes.php"?>
</head>
<body>
    <div class="container">
        <?php $pageName="Tour Management - View Past Tour Info" ?>
        <?php include_once "../includes/header_row_includes.php";?>
        <div class="col-md-3">
            <?php include_once "../includes/tour_management_functions.php"; ?>
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
                <div class="col-md-5">
                    <label class="control-label">Select Date Range To Filter By Tour Date </br>(Keep Blank for All)</label>
                </div>
                <div class="col-md-2">
                    <label class="control-label">Select Tour Status</label>
                </div>
                <div class="col-md-3">
                    <select id="tourStatus" class="form-control">
                        <option value="">All</option>
                        <option value="3">Completed</option>
                        <option value="-1">Cancelled</option>
                    </select>
                </div>
                <div class="col-md-2 text-right">
                    <button type="button" class="btn btn-success" id="filter_button">Filter</button>
                </div>
            </div>
            <div class="row">
                &nbsp;
            </div>
            <div class="row">
                <div class="col-md-3">
                    <label class="control-label">From Date</label>
                </div>
                <div class="col-md-3">
                    <input type="date" class="form-control" id="dateFrom" name="dateFrom" />
                </div>
                <div class="col-md-3">
                    <label class="control-label">To Date</label>
                </div>
                <div class="col-md-3">
                    <input type="date" class="form-control" id="dateTo" name="dateTo" />
                </div>
            </div>
            <div class="row">
                &nbsp;
            </div>
            <div class="row">
                <div class="col-md-12">
                    <table class="table" id="tourtable" style="width:100%">
                        <thead>
                            <tr>
                                <th>Customer</th>
                                <th>Start Date</th>
                                <th>End Date</th>
                                <th>Destination</th>
                                <th>Invoice No</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id="tourtableBody">
                            <?php while($tourRow = $tourResult->fetch_assoc()){ 
                                
                                $status = match((int)$tourRow["tour_status"]){
                                    
                                    -1=>"Cancelled",
                                    3=>"Completed",
                                };
                                ?>
                            <tr>
                                <td><?php echo $tourRow['customer_fname']." ".$tourRow['customer_lname'];?></td>
                                <td style="white-space: nowrap"><?php echo $tourRow['start_date'];?></td>
                                <td style="white-space: nowrap"><?php echo $tourRow['end_date'];?></td>
                                <td><?php echo $tourRow['destination'];?></td>
                                <td style="white-space: nowrap"><?php echo $tourRow['invoice_number'];?></td>
                                <td ><?php echo $status;?></td>
                                <td>
                                    <?php if($tourRow["tour_status"]==3){?>
                                    <a href="#" data-toggle="modal" onclick="loadTourBusList(<?php echo $tourRow['tour_id'];?>)" data-target="#bus_list" 
                                       class="btn btn-xs btn-info" style="margin:2px;display:<?php echo checkPermissions(85); ?>">                                                 
                                        View Assigned Buses
                                    </a>
                                    <?php }?>
                                    <!-- <?php //if($tourRow["tour_status"]!=-1){?>
                                    <a href="#" data-toggle="modal" onclick="getTourInfo(<?php// echo $tourRow['tour_id'];?>)" data-target="#tour_info" 
                                       class="btn btn-xs btn-primary" style="margin:2px;">                                                 
                                        View Tour Info
                                    </a>
                                    <?php// }?> -->
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
<!-- <div class="modal fade" id="tour_info" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="panel panel-info">
                <div class="panel-heading">
                    <h4>Tour Information</h4>
                </div>
                <div class="panel-body">
                    <div id="display_tour_info"></div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div> -->
<script src="../js/datatable/jquery-3.5.1.js"></script>
<script src="../js/datatable/jquery.dataTables.min.js"></script>
<script src="../js/datatable/dataTables.bootstrap.min.js"></script>
<script src="../bootstrap/js/bootstrap.min.js"></script>
<script>
    function loadTourBusList(tourId){
        var url ="../controller/tour_controller.php?status=load_tour_bus_list";

        $.post(url,{tourId:tourId},function(data){
            $("#display_bus_list").html(data).show();
            
            $("#tour_bus_list_table").DataTable();
        });
    }
    
    /*
    function getTourInfo(tourId){
        var url ="../controller/tour_controller.php?status=load_tour_info_modal";

        $.post(url,{tourId:tourId},function(data){
            $("#display_tour_info").html(data).show();
            
            
        });
    }
        */

    $(document).ready(function() {

        var dataTableOptions = {
            "pageLength": 5,
            "order": [
                [ 1, "desc" ] 
            ],
             "scrollX": true
        };

        var table = $("#tourtable").DataTable(dataTableOptions);
        
        $('#filter_button').on('click', function(){

            $("#msg").html("");
            $("#msg").removeClass("alert alert-danger");
            
            var dateFrom = $("#dateFrom").val();
            var dateTo = $("#dateTo").val();
            var tourStatus = $("#tourStatus").val();

            if(dateFrom!="" || dateTo!=""){

                if(dateFrom ==""){
                    $("#msg").html("Both Dates Must Be Selected To Get The Report For A Period");
                    $("#msg").addClass("alert alert-danger");
                    return false;
                }
                if(dateTo ==""){
                    $("#msg").html("Both Dates Must Be Selected To Get The Report For A Period");
                    $("#msg").addClass("alert alert-danger");
                    return false;
                }
                
                if(dateFrom>dateTo){
                    $("#msg").html("'From' Date Cannot Be Greater Than 'To' Date");
                    $("#msg").addClass("alert alert-danger");
                    return false;
                }

            }
            
            var url = "../controller/tour_controller.php?status=past_tours_filtered";

            $.post(url, {dateFrom:dateFrom, dateTo:dateTo, tourStatus:tourStatus}, function (data) {

                // Destroy the old DataTable instance.
                table.destroy();

                // Update the table body with the new filtered data.
                $("#tourtableBody").html(data);

                // Re-initialize the DataTable with the new content.
                table = $("#tourtable").DataTable(dataTableOptions);
            });
        });
    });
        
</script>
</html>