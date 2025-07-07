<?php

include_once '../commons/session.php';
include_once '../model/inspection_model.php';
include_once '../model/bus_model.php';

//get user information from session
$userSession=$_SESSION["user"];

$inspectionId = base64_decode($_GET['inspection_id']);

$inspectionObj = new Inspection();
$inspectionResult = $inspectionObj->getInspection($inspectionId);
$inspectionRow = $inspectionResult->fetch_assoc();

$busObj = new Bus();
$categoryId = $inspectionRow['category_id'];
$categoryResult = $busObj->getBusCategory($categoryId);
$categoryRow = $categoryResult->fetch_assoc();

$templateResult = $inspectionObj->getInspectionChecklistTemplateByBusCategoryId($categoryId);
$templateRow = $templateResult->fetch_assoc();
$templateId = $templateRow['template_id'];

$checklistItemIdsResult = $inspectionObj->getChecklistItemsInATemplate($templateId);
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
        <?php $pageName="Bus Maintenance - Inspect Bus" ?>
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
                <a href="service-cost-trend.php" class="list-group-item">
                    <span class="fas fa-chart-line"></span> &nbsp;
                    Service cost trend
                </a>
                <a href="manage-checklist-items.php" class="list-group-item">
                    <span class="fas fa-chart-line"></span> &nbsp;
                    Manage Checklist Items
                </a>
                <a href="manage-checklist-template.php" class="list-group-item">
                    <span class="fas fa-chart-line"></span> &nbsp;
                    Manage Checklist Template
                </a>
                <a href="pending-inspections.php" class="list-group-item">
                    <span class="fas fa-chart-line"></span> &nbsp;
                    Pending Inspections
                </a>
            </ul>
        </div>
        <form action="../controller/inspection_controller.php?status=perform_inspection" method="post" enctype="multipart/form-data">
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
                    <div class="panel panel-info">
                        <div class="panel-heading"><h4>Bus & Tour Information</h4></div>
                        <div class="panel-body">
                            <div class="col-md-3" style="margin-bottom: 10px">
                                <span class="fa-solid fa-bus"></span>&nbsp;<b>Vehicle Number</b>
                                </br>
                                <span><?php echo $inspectionRow['vehicle_no'];?> </span>
                            </div>
                            <div class="col-md-3" style="margin-bottom: 10px">
                                <span class="fa-solid fa-bus"></span>&nbsp;<b>Bus Category</b>
                                </br>
                                <span><?php echo $categoryRow['category_name'];?> </span>
                            </div>
                            <div class="col-md-3" style="margin-bottom: 10px">
                                <span class="fa-solid fa-bus"></span>&nbsp;<b>Tour Destination</b>
                                </br>
                                <span><?php echo $inspectionRow['destination'];?> </span>
                            </div>
                            <div class="col-md-3" style="margin-bottom: 10px">
                                <span class="fa-solid fa-bus"></span>&nbsp;<b>Tour Start Date</b>
                                </br>
                                <span><?php echo $inspectionRow['start_date'];?> </span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    &nbsp;
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Checklist Item</th>
                                    <th>Description</th>
                                    <th>Status</th>
                                    <th>Comments</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while($checklistItemIdRow = $checklistItemIdsResult->fetch_assoc()){
                                    
                                        $checklistItemId = $checklistItemIdRow["checklist_item_id"];
                                        $checklistItemResult = $inspectionObj->getChecklistItem($checklistItemId);
                                        $checklistItemRow = $checklistItemResult->fetch_assoc();
                                    ?>
                                <tr>
                                    <td><?php echo $checklistItemRow['checklist_item_name']; ?></td>
                                    <td><?php echo $checklistItemRow['checklist_item_description']; ?></td>
                                    <td style="white-space: nowrap">
                                        
                                        <label class="radio-inline">
                                            <input type="radio" 
                                                   name="item_status[<?php echo $checklistItemId; ?>]" 
                                                   value="1" 
                                                   >
                                            Pass
                                        </label>
                                        <label class="radio-inline">
                                            <input type="radio" 
                                                   name="item_status[<?php echo $checklistItemId; ?>]" 
                                                   value="0">
                                            Fail
                                        </label>
                                    </td>
                                    <td>
                                        <input type="text" 
                                               name="item_comments[<?php echo $checklistItemId; ?>]" 
                                               class="form-control" 
                                               placeholder="Brief Comment">
                                    </td>
                                </tr>
                                <?php }?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="row">
                    &nbsp;
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <b>Overall Result</b>
                        </br>
                        </br>
                        <select name="overall_result" id="overall_result" class="form-control">
                            <option value="">-- Select Final Result --</option>
                            <option value="1">Pass</option>
                            <option value="0">Fail</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <b>Final Comments</b>
                        </br>
                        </br>
                        <textarea name="final_comments" id="final_comments" class="form-control" rows="3" placeholder="Enter a summary of the inspection..."></textarea>  
                    </div>
                </div>
                <div class="row">
                    <input type="hidden" value="<?php echo $inspectionId;?>" name="inspection_id"/>
                    <input type="hidden" value="<?php echo $templateId;?>" name="template_id"/>
                    <input type="hidden" value="<?php echo $inspectionRow['bus_id'];?>" name="bus_id"/>
                    <input type="hidden" value="<?php echo $inspectionRow['tour_id'];?>" name="tour_id"/>
                </div>
                <div class="row">
                    &nbsp;
                </div>
                <div class="row">
                    &nbsp;
                </div>
                <div class="row">
                    <div class="col-md-offset-4 col-md-4">
                        <input type="submit" class="btn btn-primary" value="Submit" />
                        <input type="reset" class="btn btn-danger" value="Reset" />
                    </div>
                </div>
                <div class="row" style="height:150px">
                    &nbsp;
                </div>
            </div>
        </form>
    </div>
</body>
<script src="../js/datatable/jquery-3.5.1.js"></script>
</html>