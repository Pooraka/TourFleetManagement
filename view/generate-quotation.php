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
        <form id="quotationForm" action="../controller/quotation_controller.php?status=generate_quotation" method="post" enctype="multipart/form-data">
        <div class="col-md-9">
            <div class="row">
                <div class="col-md-6 col-md-offset-3" id="msg" style="text-align:center;">
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
                <div class="col-md-4">
                    <label class="control-label">Customer NIC</label>
                </div>
                <div class="col-md-4">
                    <input type="text" class="form-control" name="nic" id="nic" placeholder="991234567V / 199912345678"/>
                </div>
                <div class="col-md-4">
                    <h5 id="customer_name"></h5>
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
                    <input type="date" id="start_date" class="form-control" name="start_date" min="<?php echo date('Y-m-d'); ?>"/>
                </div>
                <div class="col-md-1">
                    <label class="control-label">End Date</label>
                </div>
                <div class="col-md-3">
                    <input type="date" id="end_date" class="form-control" name="end_date" min="<?php echo date('Y-m-d'); ?>"/>
                </div>
                <div class="col-md-4 text-right">
                    <button type="button" class="btn btn-info" id="check_bus_availability_btn">Check Bus Availability</button>        
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
                    <input type="text" class="form-control" id="pickup" name="pickup"/>
                </div>
                <div class="col-md-3">
                    <label class="control-label">Drop off location</label>
                </div>
                <div class="col-md-3">
                    <input type="text" class="form-control" id="dropoff" name="dropoff"/>
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
                    <input type="number" class="form-control" id="round_trip" name="round_trip" step="1"/>
                </div>
                <div class="col-md-3">
                    <label class="control-label">Amount (LKR)</label>
                </div>
                <div class="col-md-3">
                    <input type="number" class="form-control" id="amount" name="amount" step="0.01" min="0.01"/>
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
                    <input type="text" class="form-control" id="destination" name="destination"/>
                </div>
                <div class="col-md-3">
                    <label class="control-label">Description</label>
                </div>
                <div class="col-md-3">
                    <textarea name="description" id="description" rows="2" class="form-control"></textarea>
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
    $(document).ready(function () {

        $("#check_bus_availability_btn").on("click", function () {
            var startDate = $('#start_date').val();
            var endDate = $('#end_date').val();

            if (startDate == "" || endDate == "") {
                alert("Please select both start and end dates.");
                return false;
            }
            if (startDate > endDate) {
                alert("Start Date cannot be greater than End Date.");
                return false;
            }

            var url = "../controller/bus_controller.php?status=categories_available_for_tour";
        
            $.post(url, {startDate:startDate, endDate:endDate}, function (data) {

                $("#bus_availability").html(data);

            });

        });

        $('#quotationForm').on('submit', function(event) {

            //This is to stop submitting before clicking 'check bus availability btn'
            if ($('input[name^="request_count"]').length === 0) {
                alert('Please click "Check Bus Availability" before generating the quotation.');
                event.preventDefault();
                return false;
            }

            // Check if at least one bus category has a quantity greater than 0
            var totalRequestedBuses = 0;
            $('input[name^="request_count"]').each(function() {
                if ($(this).val()) {
                    totalRequestedBuses += parseInt($(this).val(), 10);
                }
            });

            if (totalRequestedBuses === 0) {
                alert('Please enter a quantity for at least one bus category.');
                event.preventDefault();
                return false;
            }

            var nic = $('#nic').val();
            var pickup = $('#pickup').val();
            var dropoff = $('#dropoff').val();
            var roundTrip = parseInt($('#round_trip').val(), 10);
            var amount = parseFloat($('#amount').val());
            var destination = $('#destination').val();
            var description = $('#description').val();

            if (nic == ""){

                $('#msg').addClass('alert alert-danger');
                $('#msg').html("<b>Customer NIC cannot be empty.</b>");
                return false;
            }

            if (pickup == ""){

                $('#msg').addClass('alert alert-danger');
                $('#msg').html("<b>Pickup location cannot be empty.</b>");
                return false;
            }

            if (dropoff == ""){

                $('#msg').addClass('alert alert-danger');
                $('#msg').html("<b>Drop off location cannot be empty.</b>");
                return false;
            }

            if(isNaN(roundTrip) || roundTrip <= 0){

                $('#msg').addClass('alert alert-danger');
                $('#msg').html("<b>Round trip mileage must be greater than 0.</b>");
                return false;
            }

            if(isNaN(amount) || amount <= 0){

                $('#msg').addClass('alert alert-danger');
                $('#msg').html("<b>Amount must be greater than 0.</b>");
                return false;
            }

            if (destination == ""){

                $('#msg').addClass('alert alert-danger');
                $('#msg').html("<b>Destination cannot be empty.</b>");
                return false;
            }

            if (description == ""){

                $('#msg').addClass('alert alert-danger');
                $('#msg').html("<b>Description cannot be empty.</b>");
                return false;
            }

        });

        $('#bus_availability').on('input', 'input[name^="request_count"]', function() {
            const inputField = $(this);
            const maxValue = parseInt(inputField.attr('max'), 10);
            let currentValue = parseInt(inputField.val(), 10);
            
            // Find the category name by traversing the DOM from the input field
            const categoryName = inputField.closest('tr').find('td:nth-child(1)').text();

            // If the entered number is greater than the max allowed
            if (!isNaN(currentValue) && currentValue > maxValue) {
                
                alert('You cannot request more than ' + maxValue + ' available buses for the "' + categoryName + '" category.');
                //reset the value to the maximum allowed.
                inputField.val(maxValue);
            }
        });

        $('#nic').on('change', function() {
            var nic = $('#nic').val();
        
            var url = "../controller/customer_controller.php?status=get_customer";
            
            $.post(url, {nic: nic}, function (data) {

                $("#customer_name").html(data);

            });
        });

    });








    /*
    function getCustomer(){
        var nic = $('#nic').val();
        
        var url = "../controller/customer_controller.php?status=get_customer";
        
        $.post(url, {nic: nic}, function (data) {

            $("#customer_name").html(data);

        });
    }
        */

    
    
    /*
    function checkBusCategoryAvailable(){
        var startDate = $('#start_date').val();
        var endDate = $('#end_date').val();
        
        var url = "../controller/bus_controller.php?status=categories_available_for_tour";
        
        $.post(url, {startDate:startDate, endDate:endDate}, function (data) {

            $("#bus_availability").html(data);

        });
    }
    */
</script>
</html>