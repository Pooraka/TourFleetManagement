<?php

include_once '../commons/session.php';
include_once '../model/bus_model.php';

//get user information from session
$userSession=$_SESSION["user"];

$busObj = new Bus();
$categoryResult = $busObj->getAllBusCategories();
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
        <?php $pageName="Bus Management - Add Bus" ?>
        <?php include_once "../includes/header_row_includes.php";?>
        <div class="col-md-3">
            <?php include_once "../includes/bus_management_functions.php"; ?>
        </div>
        <form action="../controller/bus_controller.php?status=add_bus" method="post" enctype="multipart/form-data">
            <div class="col-md-9">
                <div class="row">
                    <div class="col-md-6 col-md-offset-3" id="msg" style="text-align:center">
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
                        <label class="control-label">Vehicle No</label>
                    </div>
                    <div class="col-md-3">
                        <input type="text" class="form-control" name="vehicleno" id="vehicleno" placeholder="Ex: CAA-1234"/>
                    </div>
                    <div class="col-md-3">
                        <label class="control-label">Make</label>
                    </div>
                    <div class="col-md-3">
                        <input type="text" class="form-control" name="make" id="make" placeholder="Ex: Toyota"/>
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
                        <input type="text" class="form-control" name="model" id="model" placeholder="Ex: Coaster"/>
                    </div>
                    <div class="col-md-3">
                        <label class="control-label">Year</label>
                    </div>
                    <div class="col-md-3">
                        <!-- <input type="text" class="form-control" name="year" id="year" placeholder="Ex: 2014"/> -->
                        <input type="number" class="form-control" name="year" id="year" placeholder="Ex: 2014" min="1950" max="<?php echo date('Y'); ?>"/>
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
                        <input type="number" class="form-control" name="capacity" id="capacity" placeholder="Ex: 33"/>
                    </div>
                    <div class="col-md-3">
                        <label class="control-label">Service Interval (Km)</label>
                    </div>
                    <div class="col-md-3">
                        <input type="number" class="form-control" name="serviceintervalkm" id="serviceintervalkm" placeholder="Ex: 5000"/>
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
                        <input type="number" class="form-control" name="currentmileage" id="currentmileage" placeholder="Ex: 21000"/>
                    </div>
                    <div class="col-md-3">
                        <label class="control-label">Service Interval (Months) </label>
                    </div>
                    <div class="col-md-3">
                        <input type="number" class="form-control" name="serviceintervalmonths" id="serviceintervalmonths" placeholder="Ex: 3" min="1" max="6"/>
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
                        <input type="number" class="form-control" name="lastservicekm" id="lastservicekm" placeholder="Ex: 15000"/>
                        <h5>If vehicle is brand new enter 0 Km</h5>
                    </div>
                    <div class="col-md-3">
                        <label class="control-label">Last Service Date</label>
                    </div>
                    <div class="col-md-3">
                        <input type="date" class="form-control" name="lastservicedate" id="lastservicedate" max="<?php echo date('Y-m-d'); ?>"/>
                        <h5>If vehicle is brand new enter today's date</h5>
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
                            <option value="">Select AC Availability</option>
                            <option value="Y">Yes</option>
                            <option value="N">No</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="control-label">Bus Category </label>
                    </div>
                    <div class="col-md-3">
                        <select name="category" id="category" class="form-control" required="required">
                            <option value="">Select Bus Category</option>
                            <?php   while($busCategoryRow = $categoryResult->fetch_assoc()){
                                ?>
                                    <option value="<?php echo $busCategoryRow['category_id'];?>">
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