<?php

include_once '../commons/session.php';
include_once '../model/customer_invoice_model.php';


//get user information from session
$userSession=$_SESSION["user"];

$invoiceId = $_GET['invoice_id'];
$invoiceId = base64_decode($invoiceId);

$customerInvoiceObj = new CustomerInvoice();
$invoiceResult = $customerInvoiceObj->getInvoice($invoiceId);
$invoiceRow = $invoiceResult->fetch_assoc();

$invoiceStatus = match((int)$invoiceRow['invoice_status']){
    
    -1=>"Cancelled",
    3=>"Advance Payment Made",
    4=>"Paid",
};

$invoiceItemResult = $customerInvoiceObj->getInvoiceItems($invoiceId);

$invoiceAmount = (float)$invoiceRow['invoice_amount'];
$advancePayment = (float)$invoiceRow['advance_payment'];
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
        <?php $pageName="Booking Management - Accept Customer Payment" ?>
        <?php include_once "../includes/header_row_includes.php";?>
        <div class="col-md-3">
            <?php include_once "../includes/booking_functions.php"; ?>
        </div>
        <form id="acceptPaymentForm" action="../controller/finance_controller.php?status=accept_payment" method="post" enctype="multipart/form-data">
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
                <div class="panel panel-info">
                    <div class="panel-heading">
                        <h3 style="margin:0px">Invoice Information</h3>
                    </div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-3" style="margin-bottom: 10px">
                                <span class="fa-solid fa-hashtag"></span>&nbsp;<b>Invoice Number</b>
                                </br>
                                <span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $invoiceRow['invoice_number'];?> </span>
                            </div>
                            <div class="col-md-3" style="margin-bottom: 10px">
                                <span class="fa-solid fa-calendar-day"></span>&nbsp;<b>Date</b>
                                </br>
                                <span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $invoiceRow['invoice_date'];?></span>
                            </div>
                            <div class="col-md-3" style="margin-bottom: 10px">
                                <span class="fa-solid fa-money-bill-wave"></span>&nbsp;<b>Amount</b>
                                </br>
                                <span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo "LKR ".number_format($invoiceAmount,2); ?></span>
                            </div>
                            <div class="col-md-3" style="margin-bottom: 10px">
                                <span class="fa-solid fa-money-bill-wave"></span>&nbsp;<b>Advance Payment</b>
                                </br>
                                <span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo "LKR ".number_format($advancePayment,2);?> </span>
                            </div>
                        </div>
                        <div class="row">
                            &nbsp;
                        </div>
                        <div class="row">
                            <div class="col-md-3" style="margin-bottom: 10px">
                                <span class="fa-solid fa-calendar-check"></span>&nbsp;<b>Tour Start</b>
                                </br>
                                <span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $invoiceRow['tour_start_date'];?> </span>
                            </div>
                            <div class="col-md-3" style="margin-bottom: 10px">
                                <span class="fa-solid fa-calendar-times"></span>&nbsp;<b>Tour End</b>
                                </br>
                                <span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $invoiceRow['tour_end_date'];?></span>
                            </div>
                            <div class="col-md-3" style="margin-bottom: 10px">
                                <span class="fa-solid fa-map-marker-alt"></span>&nbsp;<b>Pickup From</b>
                                </br>
                                <span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $invoiceRow['pickup_location']; ?></span>
                            </div>
                            <div class="col-md-3" style="margin-bottom: 10px">
                                <span class="fa-solid fa-map-marker-alt"></span>&nbsp;<b>Drop At</b>
                                </br>
                                <span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $invoiceRow['dropoff_location']; ?></span>
                            </div>
                        </div>
                        <div class="row">
                            &nbsp;
                        </div>
                        <div class="row">
                            <div class="col-md-3" style="margin-bottom: 10px">
                                <span class="fa-solid fa-flag-checkered"></span>&nbsp;<b>Destination</b>
                                </br>
                                <span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $invoiceRow['destination'];?> </span>
                            </div>
                            <div class="col-md-3" style="margin-bottom: 10px">
                                <span class="fa-solid fa-road"></span>&nbsp;<b>Expected Mileage</b>
                                </br>
                                <span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo "Km ".number_format($invoiceRow['round_trip_mileage'],0);?> </span>
                            </div>
                            <div class="col-md-3" style="margin-bottom: 10px">
                                <span class="fa-solid fa-road"></span>&nbsp;<b>Actual Mileage</b>
                                </br>
                                <span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo "Km ".number_format($invoiceRow['actual_mileage'],0);?> </span>
                            </div>
                            <div class="col-md-3" style="margin-bottom: 10px">
                                <span class="fas fa-tags"></span>&nbsp;<b>Invoice Status</b>
                                </br>
                                <span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $invoiceStatus;?> </span>
                            </div>
                        </div>
                        <div class="row">
                            &nbsp;
                        </div>
                        <div class="row">
                            <div class="col-md-3" style="margin-bottom: 10px">
                                <span class="fa-solid fa-user"></span>&nbsp;<b>Customer</b>
                                </br>
                                <span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $invoiceRow['customer_fname']." ".$invoiceRow['customer_lname']; ?></span>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>Bus Category</th>
                                            <th>No of Buses</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php while($invoiceItemRow = $invoiceItemResult->fetch_assoc()){ ?>
                                        <tr>
                                            <td><?php echo $invoiceItemRow['category_name'];?> </td>
                                            <td><?php echo $invoiceItemRow['quantity'];?> </td>
                                        </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="panel-footer">
                        <div class="row">
                            <div class="col-md-3">
                                <label class="control-label">Actual Fare</label>
                            </div>
                            <div class="col-md-3">
                                <input type="number" class="form-control" name="actual_fare" id="actual_fare" step="0.01" inputmode="decimal" min="<?php echo $invoiceAmount;?>"/>
                            </div>
                        </div>
                        <div class="row">
                            &nbsp;
                        </div>
                        <div class="row">
                            <div class="col-md-3">
                                <label class="control-label">Advance Payment</label>
                            </div>
                            <div class="col-md-3">
                                <span><?php echo "LKR ".number_format($advancePayment,2);?></span>
                            </div>
                            <div class="col-md-3">
                                <label class="control-label">Payment To Be Made</label>
                            </div>
                            <div class="col-md-3">
                                <span id="payment_to_be_made"></span>
                            </div>
                        </div>
                        <div class="row" id="paymentNote" style="display:none">
                            <div class="col-md-12">
                                <label class="control-label">Click complete if the payment to be made is LKR 0.00</label>
                            </div>
                        </div>
                        <div class="row">
                            &nbsp;
                        </div>
                        <div class="row" id="paymentContainer" style="display:none">
                            <div class="col-md-3">
                                <label class="control-label">Select Payment Type</label>
                            </div>
                            <div class="col-md-3">
                                <input type="radio" name="payment_method" value="1"/>
                                <span>Cash</span>
                                &nbsp;&nbsp;
                                <input type="radio" name="payment_method" value="2"/>
                                <span>Funds Transfer</span>
                            </div>
                            <div class="col-md-3" id="receiptLabel" style="display:none">
                                <label class="control-label">Upload Receipt</label>
                            </div>
                            <div class="col-md-3" id="receiptInputTag" style="display:none">
                                <input type="file" name="transfer_receipt" class="form-control"/>
                            </div>
                        </div>
                        <div class="row">
                            &nbsp;
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <input type="hidden" id="paymentMade" name="paymentMade" value=""/>
                                <input type="hidden" name="invoice_id" value="<?php echo $invoiceId;?>"/>
                                <input type="submit" class="btn btn-success" value="Complete" style="width:130px"/>
                                <input type="reset" class="btn btn-danger" value="Reset" style="width:130px"/>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        </form>
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

        
        $('#actual_fare').on('blur', function() {

            var invoiceAmount = <?php echo $invoiceAmount; ?>;
            var advancePayment = <?php echo $advancePayment; ?>;
            var actualFare = parseFloat($(this).val()) || 0;
            
            
            if (actualFare < invoiceAmount) {
                alert("Actual fare cannot be less than the invoice amount.");
                $(this).val(invoiceAmount.toFixed(2)); // Reset the input field
                actualFare = invoiceAmount; 
            }

            
            var paymentToBeMade = actualFare - advancePayment;
            
            $('input[name="paymentMade"]').val(paymentToBeMade.toFixed(2));
            
            
            var paymentToBeMadeFormatted = paymentToBeMade.toLocaleString('en-LK', {
                style: 'currency',
                currency: 'LKR',
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            });
            $('#payment_to_be_made').text(paymentToBeMadeFormatted);

            
            if (paymentToBeMade <= 0) {
                $('#paymentNote').slideDown();
                $('#paymentContainer').slideUp();
            } else {
                $('#paymentNote').slideUp();
                $('#paymentContainer').slideDown();
            }
        });
    
        $('input[name="payment_method"]').on('change', function() {
            if ($(this).val() == '2') { 
                $('#receiptLabel, #receiptInputTag').slideDown();
            } else { 
                $('#receiptLabel, #receiptInputTag').slideUp();
            }
        });

        $("#acceptPaymentForm").on("submit", function(event) {
            event.preventDefault(); 

            var paymentMethod = $('input[name="payment_method"]:checked').val();

            if (!paymentMethod) {
                $("#msg").addClass("alert alert-danger");
                $("#msg").html("Please select a payment method.");
                return false;
            }

            if( paymentMethod == '2' && $('input[name="transfer_receipt"]').val() == "") {
                $("#msg").addClass("alert alert-danger");
                $("#msg").html("Please upload a transfer receipt.");
                return false;
            }

            $("#confirmationModal").modal('show');
            $("#confirmActionBtn").off("click").on("click", function() {
                $("#acceptPaymentForm").off("submit").submit(); 
            });

        });

    });
</script>
</html>