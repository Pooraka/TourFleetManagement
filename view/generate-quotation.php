<?php

include_once '../commons/session.php';


//get user information from session
$userSession=$_SESSION["user"];
?>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking</title>
    <link rel="stylesheet" type="text/css" href="../css/dataTables.bootstrap.min.css"/>
    <?php include_once "../includes/bootstrap_css_includes.php"?>
</head>
<body>
    <div class="container">
        <?php $pageName="Booking Management - Generate Quotation" ?>
        <?php include_once "../includes/header_row_includes.php";?>
        <div class="col-md-3">
            <?php include_once "../includes/booking_functions.php"; ?>
        </div>
        <form action="../controller/quotation_controller.php?status=generate_quotation" method="post" enctype="multipart/form-data">
        <div class="col-md-9">
            <div class="row">
                <div id="msg" class="col-md-offset-3 col-md-6" style="text-align:center;">
                    <?php if (isset($_GET["msg"])) { ?>

                        <script>
                            var msgElement = document.getElementById("msg");
                            msgElement.classList.add("alert", "alert-danger");
                        </script>

                        <b> <p> <?php echo base64_decode($_GET["msg"]); ?></p></b>
                        <?php
                    }
                    ?>
                </div>
            </div>
            <div class="row">
                <div class="col-md-2">
                    <label class="control-label">Customer NIC</label>
                </div>
                <div class="col-md-4">
                    <input type="text" class="form-control" name="nic" id="nic" placeholder="991234567V / 199912345678"/>
                </div>
                <div class="col-md-3">
                    <h5 id="customer_name"></h5>
                </div>
                <div class="col-md-3 text-right">
                    <input type="button" class="btn btn-info" onclick="getCustomer()" value="Check Customer">
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    &nbsp;
                </div>
            </div>
            <div class="row">
                <div class="col-md-1">
                    <label class="control-label">Start Date</label>
                </div>
                <div class="col-md-3">
                    <input type="date" id="start_date" class="form-control" name="start_date"/>
                </div>
                <div class="col-md-1">
                    <label class="control-label">End Date</label>
                </div>
                <div class="col-md-3">
                    <input type="date" id="end_date" class="form-control" name="end_date"/>
                </div>
                <div class="col-md-4 text-right">
                    <input type="button" class="btn btn-info" onclick="checkBusCategoryAvailable()" value="Check Bus Availability"/>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    &nbsp;
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 col-md-offset-3" id="bus_availability">
                    
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    &nbsp;
                </div>
            </div>
            <div class="row">
                <div class="col-md-3">
                    <label class="control-label">Pickup location</label>
                </div>
                <div class="col-md-3">
                    <input type="text" class="form-control" name="pickup"/>
                </div>
                <div class="col-md-3">
                    <label class="control-label">Drop off location</label>
                </div>
                <div class="col-md-3">
                    <input type="text" class="form-control" name="dropoff"/>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    &nbsp;
                </div>
            </div>
            <div class="row">
                <div class="col-md-3">
                    <label class="control-label">Round Trip Mileage (Km)</label>
                </div>
                <div class="col-md-3">
                    <input type="number" class="form-control" name="round_trip" step="1"/>
                </div>
                <div class="col-md-3">
                    <label class="control-label">Amount (LKR)</label>
                </div>
                <div class="col-md-3">
                    <input type="number" class="form-control" name="amount" step="0.01"/>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    &nbsp;
                </div>
            </div>
            <div class="row">
                <div class="col-md-3">
                    <label class="control-label">Destination</label>
                </div>
                <div class="col-md-3">
                    <input type="text" class="form-control" name="destination"/>
                </div>
                <div class="col-md-3">
                    <label class="control-label">Description</label>
                </div>
                <div class="col-md-3">
                    <textarea name="description" rows="2" class="form-control"></textarea>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    &nbsp;
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    &nbsp;
                </div>
            </div>
            <div class="row">
                <div class="col-md-offset-3 col-md-6">
                    <input type="submit" class="btn btn-primary" value="Generate"/>
                    <input type="reset" class="btn btn-danger" value="Reset"/>
                </div>
            </div>
        </div>
        </form>
    </div>
</body>
<script src="../js/jquery-3.7.1.js"></script>
<script>
    function getCustomer(){
        var nic = $('#nic').val();
        
        var url = "../controller/customer_controller.php?status=get_customer";
        
        $.post(url, {nic: nic}, function (data) {

            $("#customer_name").html(data);

        });
    }
    
    function checkBusCategoryAvailable(){
        var startDate = $('#start_date').val();
        var endDate = $('#end_date').val();
        
        var url = "../controller/bus_controller.php?status=categories_available_for_tour";
        
        $.post(url, {startDate:startDate, endDate:endDate}, function (data) {

            $("#bus_availability").html(data);

        });
    }
</script>
</html>