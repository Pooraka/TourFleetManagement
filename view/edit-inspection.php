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

$busId = $inspectionRow["bus_id"];

$busObj = new Bus();

$busResult = $busObj->getBus($busId);
$busRow = $busResult->fetch_assoc();

$responseResult = $inspectionObj->getInspectionResponses($inspectionId);

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
        <?php $pageName="Bus Maintenance - Edit Inspection" ?>
        <?php include_once "../includes/header_row_includes.php";?>
        <div class="col-md-3">
            <?php include_once "../includes/bus_maintenance_functions.php"; ?>
        </div>
        <form action="../controller/inspection_controller.php?status=edit_inspection" method="post" enctype="multipart/form-data">
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
                                <span><?php echo $busRow['vehicle_no'];?> </span>
                            </div>
                            <div class="col-md-3" style="margin-bottom: 10px">
                                <span class="fa-solid fa-bus"></span>&nbsp;<b>Bus Category</b>
                                </br>
                                <span><?php echo $busRow['category_name'];?> </span>
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
                                <?php while($responseRow = $responseResult->fetch_assoc()){?>
                                <tr>
                                    <td><?php echo $responseRow["checklist_item_name"]?></td>
                                    <td><?php echo $responseRow["checklist_item_description"]?></td>
                                    <td style="white-space: nowrap">
                                        
                                        <label class="radio-inline">
                                            <input type="radio" 
                                                   name="item_status[<?php echo $responseRow["checklist_item_id"]; ?>]" 
                                                   value="1" 
                                                   
                                                   <?php if($responseRow["response_value"]==1) {?>
                                                   
                                                   checked
                                                   <?php }?>
                                                   
                                                   >
                                            Pass
                                        </label>
                                        <label class="radio-inline">
                                            <input type="radio" 
                                                   name="item_status[<?php echo $responseRow["checklist_item_id"]; ?>]" 
                                                   value="0"
                                                   
                                                   <?php if($responseRow["response_value"]==0) {?>
                                                   
                                                   checked
                                                   <?php }?>
                                                   
                                                   
                                                   >
                                            Fail
                                        </label>
                                    </td>
                                    <td>
                                        <input type="text" 
                                               name="item_comments[<?php echo $responseRow["checklist_item_id"]; ?>]" 
                                               class="form-control" 
                                               placeholder="Brief Comment"
                                               value="<?php echo $responseRow["item_comment"]?>">
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
                            <option value="1" <?php if($inspectionRow["inspection_result"]==1) {?> selected <?php }?>>Pass</option>
                            <option value="0" <?php if($inspectionRow["inspection_result"]==0) {?> selected <?php }?>>Fail</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <b>Final Comments</b>
                        </br>
                        </br>
                        <textarea name="final_comments" id="final_comments" class="form-control" rows="3"><?php echo $inspectionRow["final_comments"]?></textarea>  
                    </div>
                </div>
                <div class="row">
                    <input type="hidden" value="<?php echo $inspectionId;?>" name="inspection_id"/>
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