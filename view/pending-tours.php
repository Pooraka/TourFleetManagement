<?php

include_once '../commons/session.php';
include_once '../model/tour_model.php';


//get user information from session
$userSession=$_SESSION["user"];


$dateFrom = "";
$dateTo = "";

$tourObj = new Tour();
$tourResult = $tourObj->getOngoingToursFiltered($dateFrom,$dateTo);

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
                <div class="col-md-8">
                    <label class="control-label">Select Date Range To Filter Pending Tours (Keep Blank for All)</label>
                </div>
                <div class="col-md-4 text-right">
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
                                <th>Estimated Mileage</th>
                                <th>Invoice No</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id="tourtableBody">
                            <?php while($tourRow = $tourResult->fetch_assoc()){ ?>
                            <tr>
                                <td><?php echo $tourRow['customer_fname']." ".$tourRow['customer_lname'];?></td>
                                <td style="white-space: nowrap"><?php echo $tourRow['start_date'];?></td>
                                <td style="white-space: nowrap"><?php echo $tourRow['end_date'];?></td>
                                <td><?php echo $tourRow['destination'];?></td>
                                <td style="white-space: nowrap;text-align:right"><?php echo number_format($tourRow['round_trip_mileage'])." km";?></td>
                                <td style="white-space: nowrap"><?php echo $tourRow['invoice_number'];?></td>
                                <td>
                                    <a href="#" data-toggle="modal" onclick="loadTour(<?php echo $tourRow['tour_id'];?>)" data-target="#completeTourModal" 
                                       class="btn btn-xs btn-success" style="margin:2px;display:<?php echo checkPermissions(84); ?>">
                                        <span class="glyphicon glyphicon-ok"></span>                                                  
                                        Complete
                                    </a>
                                    <a href="#" data-toggle="modal" onclick="loadTourBusList(<?php echo $tourRow['tour_id'];?>)" data-target="#bus_list" 
                                       class="btn btn-xs btn-info" style="margin:2px;display:<?php echo checkPermissions(85); ?>">
                                        <span class="glyphicon glyphicon-ok"></span>                                                  
                                        View Assigned Buses
                                    </a>
                                    <a href="../controller/tour_controller.php?status=cancel_tour&tour_id=<?php echo base64_encode($tourRow['tour_id']);?>" 
                                       class="btn btn-xs btn-danger cancel-tour-btn" style="margin:2px;display:<?php echo checkPermissions(86); ?>">
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
            <form id="completeTourModalForm" action="../controller/tour_controller.php?status=complete_tour" method="post">
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
<div class="modal fade" id="confirmationModal" tabindex="-1" role="dialog" aria-labelledby="modalLabel">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="modalLabel">Confirm Action</h4>
            </div>
            <div class="modal-body">
                Are you sure you want to complete the tour?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="confirmActionBtn">Confirm</button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="cancelTourConfirmationModal" tabindex="-1" role="dialog" aria-labelledby="modalLabel">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="modalLabel">Confirm Action</h4>
            </div>
            <div class="modal-body">
                Are you sure you want to cancel the tour?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">No</button>
                <button type="button" class="btn btn-primary" id="cancelTourConfirmActionBtn">Yes, Confirm</button>
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
            
            var url = "../controller/tour_controller.php?status=pending_tours_filtered";

            $.post(url, {dateFrom:dateFrom, dateTo:dateTo}, function (data) {

                // Destroy the old DataTable instance.
                table.destroy();

                // Update the table body with the new filtered data.
                $("#tourtableBody").html(data);

                // Re-initialize the DataTable with the new content.
                table = $("#tourtable").DataTable(dataTableOptions);
            });
        });


        $("#completeTourModalForm").on('submit', function(event) {
            event.preventDefault(); // Prevent the default form submission

            var actualMileage = parseInt($("#actual_mileage").val());

            if( isNaN(actualMileage) || actualMileage <= 0) {

                alert("Please enter a valid mileage.");
                return false;
            }

            // Show confirmation modal
            $("#confirmationModal").modal('show');
            $("#completeTourModal").modal('hide'); // Hide the modal
            
            // Set up the confirmation button to perform the actual submission
            $("#confirmActionBtn").off("click").on("click", function() {
                
                $("#completeTourModalForm").off("submit").submit(); // Submit the form
            });
        });

        $("#tourtable").on("click", ".cancel-tour-btn", function(e) {
            e.preventDefault();
            
            var cancelUrl = $(this).attr("href");
            $("#cancelTourConfirmationModal").modal("show");
            $("#cancelTourConfirmActionBtn").off("click").on("click", function() {
                window.location.href = cancelUrl;
            });
        });

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