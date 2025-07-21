<?php

include_once '../commons/session.php';
include_once '../model/inspection_model.php';
include_once '../model/bus_model.php';

//get user information from session
$userSession=$_SESSION["user"];


$inspectionObj = new Inspection();

$resultId = "";

$inspectionData = $inspectionObj->getPastInspectionsFiltered($resultId);

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
        <?php $pageName="Bus Maintenance - View Past Inspections" ?>
        <?php include_once "../includes/header_row_includes.php";?>
        <div class="col-md-3">
            <ul class="list-group">
                <a href="add-service-station.php" class="list-group-item" style="display:<?php echo checkPermissions(114); ?>">
                    <span class="glyphicon glyphicon-plus"></span> &nbsp;
                    Add Service Station
                </a>
                <a href="view-service-stations.php" class="list-group-item" style="display:<?php echo checkPermissions(115); ?>">
                    <span class="glyphicon glyphicon-search"></span> &nbsp;
                    View Service Stations
                </a>
                <a href="initiate-service.php" class="list-group-item" style="display:<?php echo checkPermissions(118); ?>">
                    <span class="fa-solid fa-wrench"></span> &nbsp;
                    Initiate Service
                </a>
                <a href="view-ongoing-services.php" class="list-group-item" style="display:<?php echo checkPermissions(119); ?>">
                    <span class="fa-solid fa-gear fa-spin"></span> &nbsp;
                    View Ongoing Services
                </a>
                <a href="service-history.php" class="list-group-item" style="display:<?php echo checkPermissions(122); ?>">
                    <span class="fa fa-list-alt"></span> &nbsp;
                    Service History
                </a>
                <a href="manage-checklist-items.php" class="list-group-item" style="display:<?php echo checkPermissions(125); ?>">
                    <span class="fas fa-chart-line"></span> &nbsp;
                    Manage Checklist Items
                </a>
                <a href="manage-checklist-template.php" class="list-group-item" style="display:<?php echo checkPermissions(129); ?>">
                    <span class="fas fa-chart-line"></span> &nbsp;
                    Manage Checklist Template
                </a>
                <a href="pending-inspections.php" class="list-group-item" style="display:<?php echo checkPermissions(130); ?>">
                    <span class="fas fa-chart-line"></span> &nbsp;
                    Pending Inspections
                </a>
                <a href="past-inspections.php" class="list-group-item" style="display:<?php echo checkPermissions(152); ?>">
                    <span class="fas fa-chart-line"></span> &nbsp;
                    View Past Inspections
                </a>
                <a href="../reports/upcoming-services-report.php" class="list-group-item" target="_blank" style="display:<?php echo checkPermissions(132); ?>">
                    <span class="fas fa-chart-line"></span> &nbsp;
                    Upcoming Services Report
                </a>
                <a href="inspection-status-report.php" class="list-group-item" style="display:<?php echo checkPermissions(133); ?>">
                    <span class="fas fa-chart-line"></span> &nbsp;
                    Inspection Status Report
                </a>
            </ul>
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
                <div class="col-md-3">
                    <label class="control-label">Select Result</label>
                </div>
                <div class="col-md-3">
                    <select id="resultId" class="form-control">
                        <option value="">All</option>
                        <option value="1">Passed</option>
                        <option value="0">Failed</option>
                    </select>
                </div>
                <div class="col-md-offset-3 col-md-3 text-right">
                    <button type="button" class="btn btn-success" id="filter_button">Filter</button>
                </div>
            </div>
            <div class="row">
                &nbsp;
            </div>
            <div class="row">
                <div class="col-md-12">
                    <table class="table" id="past_inspection_table" style="width:100%">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>ID</th>
                                <th style="white-space: nowrap">Vehicle No</th>
                                <th>Result</th>
                                <th>Comments</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id="past_inspection_table_body">
                            <?php while($inspectionRow = $inspectionData->fetch_assoc()){
                                
                                $result = match((int)$inspectionRow["inspection_result"]){
                                    1=>"Passed",
                                    0=>"Failed",
                                };
                                
                                $busId = $inspectionRow["bus_id"];
                                
                                $busResult = $busObj->getBus($busId);
                                $busRow = $busResult->fetch_assoc();
                                
                                ?>
                            
                            <tr>
                                <td style="white-space: nowrap"><?php echo $inspectionRow["inspection_date"];?></td>
                                <td><?php echo $inspectionRow["inspection_id"];?></td>
                                <td style="white-space: nowrap"><?php echo $busRow["vehicle_no"];?></td>
                                <td style="white-space: nowrap"><?php echo $result;?></td>
                                <td><?php echo $inspectionRow["final_comments"];?></td>
                                <td style="white-space: nowrap">
                                    <a href="view-inspection.php?inspection_id=<?php echo base64_encode($inspectionRow["inspection_id"]); ?>" 
                                       class="btn btn-sm btn-info" style="margin:2px;display:<?php echo checkPermissions(153); ?>">
                                        <span class="fa-solid fa-circle-info"></span>
                                        View
                                    </a>
                                    <?php if($inspectionRow["inspection_status"]==2||$inspectionRow["inspection_status"]==3){?>
                                    <a href="edit-inspection.php?inspection_id=<?php echo base64_encode($inspectionRow["inspection_id"]); ?>" 
                                       class="btn btn-sm btn-warning" style="margin:2px;display:<?php echo checkPermissions(154); ?>">
                                        <span class="glyphicon glyphicon-pencil"></span>
                                        Edit
                                    </a>
                                    <?php }?>
                                </td>
                            </tr>
                            <?php }?>
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
<script src="../bootstrap/js/bootstrap.min.js"></script>
<script>
    $(document).ready(function() {

        var dataTableOptions = {
            "pageLength": 5,
            "order": [
                [ 1, "desc" ] 
            ],
             "scrollX": true
        };

        var table = $("#past_inspection_table").DataTable(dataTableOptions);

        $('#filter_button').on('click', function(){

            $("#msg").html("");
            $("#msg").removeClass("alert alert-danger");

            var resultId = $("#resultId").val();
            
            var url = "../controller/inspection_controller.php?status=past_inspections_filtered";

            $.post(url, {resultId:resultId}, function (data) {

                // Destroy the old DataTable instance.
                table.destroy();

                // Update the table body with the new filtered data.
                $("#past_inspection_table_body").html(data);

                // Re-initialize the DataTable with the new content.
                table = $("#past_inspection_table").DataTable(dataTableOptions);
            });
        });
    });
</script>
</html>