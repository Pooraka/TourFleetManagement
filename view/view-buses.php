<?php

include_once '../commons/session.php';
include_once '../model/bus_model.php';

//get user information from session
$userSession=$_SESSION["user"];

$busObj = new Bus();

$busStatus = "";
$categoryId = "";

$busResult = $busObj->getAllBusesFiltered($busStatus,$categoryId);

$categoryResult = $busObj->getAllBusCategories();
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
            <?php include_once "../includes/bus_management_functions.php"; ?>
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
                <div class="col-md-2">
                    <label class="control-label">Select Category</label>
                </div>
                <div class="col-md-3">
                    <select id="categoryId" class="form-control">
                        <option value="">All</option>
                        <?php while($categoryRow = $categoryResult->fetch_assoc()){ ?>
                        <option value="<?php echo $categoryRow["category_id"];?>"><?php echo $categoryRow["category_name"];?></option>
                        <?php } ?>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="control-label">Bus Status</label>
                </div>
                <div class="col-md-3">
                    <select id="busStatus" class="form-control">
                        <option value="">All</option>
                        <option value="1">Operational</option>
                        <option value="2">Service Due</option>
                        <option value="3">In Service</option>
                        <option value="4">Inspection Failed</option>
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
                <div class="col-md-12">
                    <table class="table" id="bustable" style="width:100%">
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
                        <tbody id="bustableBody">
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

                                    $assignedToATour = $busObj->isATourAssignedForThisBus($busId);
                                    $busId = base64_encode($busId);
                                    
                                ?>
                            
                                    <tr>
                                        <td><?php echo $busRow['vehicle_no'];?></td>
                                        <td><?php echo $busRow['make'];?></td>
                                        <td><?php echo $busRow['model'];?></td>
                                        <td><?php echo $busRow['capacity'];?></td>
                                        <td><?php echo $busRow['category_name'];?></td>
                                        <td><span class="<?php echo $statusClass;?>"><?php echo $status;?></span></td>
                                        <td style="white-space: nowrap">
                                            <a href="view-bus.php?bus_id=<?php echo $busId;?>" class="btn btn-sm btn-info" 
                                               style="margin:2px;display:<?php echo checkPermissions(110); ?>" title="View">
                                                <span class="fa-solid fa-circle-info"></span>                                                  
                                            </a>
                                            <a href="edit-bus.php?bus_id=<?php echo $busId;?>" class="btn btn-sm btn-warning" 
                                               style="margin:2px;display:<?php echo checkPermissions(111); ?>" title="Edit">
                                                <span class="glyphicon glyphicon-pencil"></span>
                                            </a>
                                            <?php if(!$assignedToATour) { ?>                                    
                                            <a href="../controller/bus_controller.php?status=remove_bus&bus_id=<?php echo $busId; ?>" 
                                               class="btn btn-sm btn-danger remove-bus-btn" style="margin:2px;display:<?php echo checkPermissions(112); ?>" title="Remove">
                                                <span class="glyphicon glyphicon-trash"></span>
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
    </div>
</body>
<div class="modal fade" id="confirmationModal" tabindex="-1" role="dialog" aria-labelledby="modalLabel">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="modalLabel">Confirm Action</h4>
            </div>
            <div class="modal-body">
                Are you sure you want to proceed?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="confirmActionBtn">Confirm</button>
            </div>
        </div>
    </div>
</div>
<script src="../js/datatable/jquery-3.5.1.js"></script>
<script src="../js/datatable/jquery.dataTables.min.js"></script>
<script src="../js/datatable/dataTables.bootstrap.min.js"></script>
<script src="../bootstrap/js/bootstrap.min.js"></script>
<script>
    $(document).ready(function() {

        var dataTableOptions = {
            "pageLength": 15,
            "order": [
                [ 0, "asc" ] 
            ],
             "scrollX": true
        };

        var table = $("#bustable").DataTable(dataTableOptions);

        $('#filter_button').on('click', function(){

            $("#msg").html("");
            $("#msg").removeClass("alert alert-danger");
            
            var categoryId = $("#categoryId").val();
            var busStatus = $("#busStatus").val();

            
            var url = "../controller/bus_controller.php?status=all_buses_filtered";

            $.post(url, {categoryId:categoryId, busStatus:busStatus}, function (data) {

                // Destroy the old DataTable instance.
                table.destroy();

                // Update the table body with the new filtered data.
                $("#bustableBody").html(data);

                // Re-initialize the DataTable with the new content.
                table = $("#bustable").DataTable(dataTableOptions);
            });
        });


        $("#bustable").on("click", ".remove-bus-btn", function(event) {

            event.preventDefault();

            var removeUrl = $(this).attr("href");

            $("#confirmationModal").modal("show");

            $("#confirmActionBtn").off("click").on("click", function () {
                // Redirect to the remove URL
                window.location.href = removeUrl;
            });
        });
    });
</script>
</html>