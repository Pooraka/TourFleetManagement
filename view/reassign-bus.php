<?php

include_once '../commons/session.php';
include_once '../model/inspection_model.php';
include_once '../model/bus_model.php';
include_once '../model/user_model.php';


//get user information from session
$userSession=$_SESSION["user"];

$inspectionId = base64_decode($_GET["inspection_id"]);

$inspectionObj = new Inspection();
$inspectionResult = $inspectionObj->getInspection($inspectionId);
$inspectionRow = $inspectionResult->fetch_assoc();

//get bus category id as user might want to select the new bus in same category so better to inform
$categoryId = $inspectionRow["category_id"];

$busObj = new Bus();
$categoryResult = $busObj->getBusCategory($categoryId);
$categoryRow = $categoryResult->fetch_assoc();

//better to provide the user info of inspector
$userObj = new User();
$inspectedBy = $inspectionRow["inspected_by"];
$userResult = $userObj->getUser($inspectedBy);
$userRow = $userResult->fetch_assoc();
$userName = $userRow["user_fname"]." ".$userRow["user_lname"];


//Get the new buses available for the tour
$tourStartDate = $inspectionRow["start_date"];
$tourEndDate = $inspectionRow["end_date"];
$busResult = $busObj->getBusAvailableForTour($tourStartDate,$tourEndDate);

//Get Old Bus Details to replace in bus_tour table
$OldBusId = $inspectionRow["bus_id"];
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
        <?php $pageName="Tour Management - Reassign Bus" ?>
        <?php include_once "../includes/header_row_includes.php";?>
        <div class="col-md-3">
            <?php include_once "../includes/tour_management_functions.php"; ?>
        </div>
        <form action="../controller/tour_controller.php?status=reassign_bus_to_tour" method="post" enctype="multipart/form-data">
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
                        <div class="panel-heading"><h3 style="margin:0px">Failed Inspection Details</h3></div>
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-md-3" style="margin-bottom: 10px">
                                    <span class="fa-solid fa-bus"></span>&nbsp;<b>Vehicle Number</b>
                                    </br>
                                    <span><?php echo $inspectionRow['vehicle_no']; ?> </span>
                                </div>
                                <div class="col-md-3" style="margin-bottom: 10px">
                                    <span class="fa-solid fa-bus"></span>&nbsp;<b>Bus Category</b>
                                    </br>
                                    <span><?php echo $categoryRow["category_name"]; ?> </span>
                                </div>
                                <div class="col-md-3" style="margin-bottom: 10px">
                                    <span class="fa-solid fa-bus"></span>&nbsp;<b>Tour Date</b>
                                    </br>
                                    <span><?php echo $inspectionRow['start_date']; ?> </span>
                                </div>
                                <div class="col-md-3" style="margin-bottom: 10px">
                                    <span class="fa-solid fa-bus"></span>&nbsp;<b>Tour Destination</b>
                                    </br>
                                    <span><?php echo $inspectionRow['destination']; ?> </span>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-3" style="margin-bottom: 10px">
                                    <span class="fa-solid fa-bus"></span>&nbsp;<b>Inspection ID</b>
                                    </br>
                                    <span><?php echo $inspectionRow['inspection_id']; ?> </span>
                                </div>
                                <div class="col-md-3" style="margin-bottom: 10px">
                                    <span class="fa-solid fa-bus"></span>&nbsp;<b>Inspection By</b>
                                    </br>
                                    <span><?php echo $userName; ?> </span>
                                </div>
                                <div class="col-md-6" style="margin-bottom: 10px">
                                    <span class="fa-solid fa-bus"></span>&nbsp;<b>Inspection Comments</b>
                                    </br>
                                    <span><?php echo $inspectionRow['final_comments']; ?> </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    &nbsp;
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <label class="control-label">Select The New Bus</label>
                    </div>
                    <div class="col-md-4">
                        <select name="bus_id" id="bus_id" class="form-control">
                            <option value="">Select a Vehicle</option>
                            <?php while($busRow = $busResult->fetch_assoc()){ 

                                if($categoryId==$busRow["category_id"]){

                                    $bus = $busRow["vehicle_no"]." (Same Category)";

                                }else{

                                    $bus = $busRow["vehicle_no"]."---".$busRow["category_name"];

                                }

                                ?>
                            <option value="<?php echo $busRow["bus_id"];?>"><?php echo $bus?></option>
                            <?php }?>
                        </select>
                    </div>
                </div>
                <div class="row">
                    &nbsp;
                </div>
                <div class="row">
                    <input type="hidden" name="inspection_id" value="<?php echo $inspectionId;?>"/>
                    <input type="hidden" name="tour_id" value="<?php echo $inspectionRow["tour_id"];?>"/>
                    <input type="hidden" name="old_bus_id" value="<?php echo $OldBusId;?>"/>
                </div>
                <div class="row">
                    <div class="col-md-offset-2 col-md-6">
                        <input type="submit" class="btn btn-success" value="Re-assign"/>
                        <a href="inspection-failed.php" class="btn btn-danger" style="width: 90px">
                            Cancel
                        </a>
                    </div>
                </div>
            </div>
        </form>
    </div>
</body>
<script src="../js/datatable/jquery-3.5.1.js"></script>
<script src="../js/datatable/jquery.dataTables.min.js"></script>
<script src="../js/datatable/dataTables.bootstrap.min.js"></script>
<script>
    $(document).ready(function(){

        $("#servicetable").DataTable();

    });
</script>
</html>