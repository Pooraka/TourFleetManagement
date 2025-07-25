<?php

include_once '../commons/session.php';
include_once '../model/inspection_model.php';
include_once '../model/bus_model.php';

//get user information from session
$userSession=$_SESSION["user"];

$inspectionId = base64_decode($_GET["inspection_id"]);
$inspectionObj = new Inspection();
$busObj = new Bus();

$inspectionResult = $inspectionObj->getInspection($inspectionId);
$inspectionRow = $inspectionResult->fetch_assoc();

$finalResult = match((int)$inspectionRow["inspection_result"]){
    
    0=>"Failed",
    1=>"Passed",
};

$busId = $inspectionRow["bus_id"];
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
        <?php $pageName="Bus Maintenance - View Inspection" ?>
        <?php include_once "../includes/header_row_includes.php";?>
        <div class="col-md-3">
            <?php include_once "../includes/bus_maintenance_functions.php"; ?>
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
                    <div class="palen panel-info">
                        <div class="panel-heading">
                            <h3 style="margin:0px">General Information</h3>
                        </div>
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-md-3" style="margin-bottom: 10px">
                                    <span class="fa-solid fa-bus"></span>&nbsp;<b>Inspection Date</b>
                                    </br>
                                    <span><?php echo $inspectionRow["inspection_date"]; ?> </span>
                                </div>
                                <div class="col-md-3" style="margin-bottom: 10px">
                                    <span class="fa-solid fa-bus"></span>&nbsp;<b>Inspection ID</b>
                                    </br>
                                    <span><?php echo $inspectionRow["inspection_id"]; ?> </span>
                                </div>
                                <div class="col-md-3" style="margin-bottom: 10px">
                                    <span class="fa-solid fa-bus"></span>&nbsp;<b>Inspection Result</b>
                                    </br>
                                    <span><?php echo $finalResult; ?> </span>
                                </div>
                                <div class="col-md-3" style="margin-bottom: 10px">
                                    <span class="fa-solid fa-bus"></span>&nbsp;<b>Vehicle No</b>
                                    </br>
                                    <span><?php echo $busRow["vehicle_no"]; ?> </span>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12" style="margin-bottom: 10px">
                                    <span class="fa-solid fa-bus"></span>&nbsp;<b>Comments</b>
                                    </br>
                                    <span><?php echo $inspectionRow["final_comments"]; ?> </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                &nbsp;
            </div>
            <div class="row">
                <div class="col-md-12">
                    <table class="table" id="checklist_table">
                        <thead>
                            <tr>
                                <th>Checklist Item</th>
                                <th>Description</th>
                                <th>Result</th>
                                <th>Comments</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while($responseRow = $responseResult->fetch_assoc()){
                                
                                $result = match((int)$responseRow["response_value"]){
                                    
                                    0=>"Failed",
                                    1=>"Passed",
                                };
                                
                                ?>
                            
                            <tr>
                                <td><?php echo $responseRow["checklist_item_name"];?></td>
                                <td><?php echo $responseRow["checklist_item_description"];?></td>
                                <td><?php echo $result;?></td>
                                <td><?php echo $responseRow["item_comment"];?></td>
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
<script>
    $(document).ready(function(){

        $("#checklist_table").DataTable();

    });
</script>
</html>