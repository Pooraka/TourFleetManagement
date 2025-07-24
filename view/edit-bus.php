<?php

include_once '../commons/session.php';
include_once '../model/bus_model.php';

//get user information from session
$userSession=$_SESSION["user"];

$busId = $_GET["bus_id"];
$busId = base64_decode($busId);

$busObj = new Bus();
$categoryResult = $busObj->getAllBusCategories();

$busResult = $busObj->getBus($busId);
$busRow = $busResult->fetch_assoc();
?>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bus Management</title>
    <?php include_once "../includes/bootstrap_css_includes.php"?>
</head>
<body>
    <div class="container">
        <?php $pageName="Bus Management - Edit Bus" ?>
        <?php include_once "../includes/header_row_includes.php";?>
        <div class="col-md-3">
            <?php include_once "../includes/bus_management_functions.php"; ?>
        </div>
        <form action="../controller/bus_controller.php?status=update_bus" method="post" enctype="multipart/form-data">
            <div class="col-md-9">
                <div class="row">
                    <div id="msg" class="col-md-offset-3 col-md-6" style="text-align:center;">
                        <?php if(isset($_GET["msg"])){ ?>
                        
                                <script>
                                    var msgElement = document.getElementById("msg");
                                    msgElement.classList.add("alert", "alert-danger");
                                </script>
                                
                                <b> <p> <?php echo base64_decode($_GET["msg"]);?></p></b>
                                <?php
                            }
                        ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-3">
                        <label class="control-label">Vehicle No</label>
                    </div>
                    <div class="col-md-3">
                        <input type="hidden" name="bus_id" value="<?php echo $busId;?>" />
                        <input type="text" value="<?php echo $busRow['vehicle_no'];?>" class="form-control" name="vehicleno" id="vehicleno" placeholder="Ex: CAA-1234"/>
                    </div>
                    <div class="col-md-3">
                        <label class="control-label">Make</label>
                    </div>
                    <div class="col-md-3">
                        <input type="text" value="<?php echo $busRow['make'];?>" class="form-control" name="make" id="make" placeholder="Ex: Toyota"/>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        &nbsp;
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-3">
                        <label class="control-label">Model</label>
                    </div>
                    <div class="col-md-3">
                        <input type="text" value="<?php echo $busRow['model'];?>" class="form-control" name="model" id="model" placeholder="Ex: Coaster"/>
                    </div>
                    <div class="col-md-3">
                        <label class="control-label">Year</label>
                    </div>
                    <div class="col-md-3">
                        <input type="text" value="<?php echo $busRow['year'];?>" class="form-control" name="year" id="year" placeholder="Ex: 2014"/>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        &nbsp;
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-3">
                        <label class="control-label">Passenger Capacity</label>
                    </div>
                    <div class="col-md-3">
                        <input type="number" value="<?php echo $busRow['capacity'];?>" class="form-control" name="capacity" id="capacity" placeholder="Ex: 33"/>
                    </div>
                    <div class="col-md-3">
                        <label class="control-label">Service Interval (Km)</label>
                    </div>
                    <div class="col-md-3">
                        <input type="number" value="<?php echo $busRow['service_interval_km'];?>" class="form-control" name="serviceintervalkm" id="serviceintervalkm" placeholder="Ex: 5000"/>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        &nbsp;
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-3">
                        <label class="control-label">Current Mileage (Km) </label>
                    </div>
                    <div class="col-md-3">
                        <input type="number" value="<?php echo $busRow['current_mileage_km'];?>" class="form-control" name="currentmileage" id="currentmileage" placeholder="Ex: 21000"/>
                    </div>
                    <div class="col-md-3">
                        <label class="control-label">Service Interval (Months) </label>
                    </div>
                    <div class="col-md-3">
                        <input type="number" value="<?php echo $busRow['service_interval_months'];?>" class="form-control" name="serviceintervalmonths" id="serviceintervalmonths" placeholder="Ex: 3"/>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        &nbsp;
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-3">
                        <label class="control-label">Last Service Mileage (Km)</label>
                    </div>
                    <div class="col-md-3">
                        <input type="number" value="<?php echo $busRow['last_service_mileage_km'];?>" class="form-control" name="lastservicekm" id="lastservicekm" placeholder="Ex: 15000"/>
                    </div>
                    <div class="col-md-3">
                        <label class="control-label">Last Service Date</label>
                    </div>
                    <div class="col-md-3">
                        <input type="date" value="<?php echo $busRow['last_service_date'];?>" class="form-control" name="lastservicedate" id="lastservicedate"/>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        &nbsp;
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-3">
                        <label class="control-label">AC Available ?</label>
                    </div>
                    <div class="col-md-3">
                        <select name="ac" id="ac" class="form-control" required="required">
                            <option value="">------------</option>
                            <option value="Y" <?php if($busRow['ac_available']=='Y'){?> selected <?php }?> >Yes</option>
                            <option value="N" <?php if($busRow['ac_available']=='N'){?> selected <?php }?> >No</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="control-label">Bus Category </label>
                    </div>
                    <div class="col-md-3">
                        <select name="category" id="category" class="form-control" required="required">
                            <option value="">------------</option>
                            <?php   while($busCategoryRow = $categoryResult->fetch_assoc()){
                                ?>
                                    <option value="<?php echo $busCategoryRow['category_id'];?>"  <?php if($busRow['category_id']==$busCategoryRow['category_id']){?> selected <?php }?>>
                                        <?php echo $busCategoryRow['category_name']; ?>
                                    </option>
                            <?php
                                    }
                            ?>
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12"> &nbsp; </div>
                </div>
                <div class="row">
                    <div class="col-md-offset-3 col-md-6">
                        <input type="submit" class="btn btn-primary" value="Submit"/>
                        <input type="reset" class="btn btn-danger" value="Reset"/>
                    </div>
                </div>
            </div>
        </form>
    </div>
</body>
<script src="../js/jquery-3.7.1.js"></script>
<script src="../js/busValidation.js"></script>
</html>