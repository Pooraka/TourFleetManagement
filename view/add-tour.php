<?php

include_once '../commons/session.php';
include_once '../model/customer_invoice_model.php';


//get user information from session
$userSession=$_SESSION["user"];

$customerInvoiceObj = new CustomerInvoice();
$pendingInvoiceResult = $customerInvoiceObj->getInvoicesToAssignTours();
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
        <?php $pageName="Tour Management - Add Tour" ?>
        <?php include_once "../includes/header_row_includes.php";?>
        <div class="col-md-3">
            <?php include_once "../includes/tour_management_functions.php"; ?>
        </div>
        <form id="addTourForm" action="../controller/tour_controller.php?status=add_tour" method="post" enctype="multipart/form-data">
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
                <div class="col-md-3">
                    <label class="control-label">Invoice No</label>
                </div>
                <div class="col-md-3">
                    <select name="invoice_id" id="invoice_id" class="form-control" required="required">
                        <option value="">Select Invoice</option>
                            <?php
                                while($pendingInvoiceRow=$pendingInvoiceResult->fetch_assoc()){
                                    ?>
                            <option value="<?php echo $pendingInvoiceRow['invoice_id'];?>" >
                                <?php echo htmlspecialchars($pendingInvoiceRow['invoice_number']);?>
                            </option>
                            <?php
                                }
                                ?>
                    </select>
                </div>
            </div>
            <div id="dynamic_tour_data">

            </div>
            <div class="row">
                &nbsp;
            </div>
            <div class="row">
                <div class="col-md-6 col-md-offset-3">
                    <input type="submit" class="btn btn-primary" value="Submit"/>
                    <input type="reset" class="btn btn-danger" value="Reset"/>
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

    $(document).ready(function () {
        
        $("#invoice_id").on("change", function() {

            var invoiceId = $('#invoice_id').val();

            if(invoiceId!=""){
            
                var url = "../controller/tour_controller.php?status=get_data_to_add_tour";
                
                $.post(url, {invoiceId: invoiceId}, function (data) {

                    $("#dynamic_tour_data").html(data);

                });
            }else{
                $("#dynamic_tour_data").html("");
            }
            
        });

        //validation logic to the form's submit event
        $('#addTourForm').on('submit', function(event) {
            
            event.preventDefault();
            
            if ($('input[name="bus[]"]:checked').length === 0) {

                $("#msg").addClass("alert alert-danger");
                $("#msg").html("No bus is selected.");
                return false;
            }

            //Match selected buses with requested quantities

            //Get the requested quantities from the first table
            let requestedCounts = {};
            $('#requested_buses_table tr').each(function() {
                let category = $(this).find('td:nth-child(1)').text().trim();
                let quantity = parseInt($(this).find('td:nth-child(2)').text().trim(), 10);
                if (category) {
                    requestedCounts[category] = quantity;
                }
            });

            //Count the selected buses by category from the second table
            let selectedCounts = {};
            $('input[name="bus[]"]:checked').each(function() {
                let category = $(this).closest('tr').find('td:nth-child(2)').text().trim();
                if (selectedCounts[category]) {
                    selectedCounts[category]++;
                } else {
                    selectedCounts[category] = 1;
                }
            });

            //Compare the two sets of counts
            //convert both objects to strings to easily compare them.
            if (JSON.stringify(requestedCounts) !== JSON.stringify(selectedCounts)) {
                $("#msg").addClass("alert alert-danger");
                $("#msg").html("The selected buses do not match the requested quantities per category. Please adjust your selection.");
                return false;
            }

            // If all validation passes, show the modal.
            $("#confirmationModal").modal('show');
            
            //set up the confirmation button to perform the actual submission.
            $("#confirmActionBtn").off("click").on("click", function() {
                // To avoid this validation logic from running again in a loop,
                // Remove the handler and then trigger the native form submission.
                $("#addTourForm").off("submit").submit();
            });
        });

        $("#addTourForm").on("reset", function() {
            $("#msg").removeClass("alert alert-danger");
            $("#msg").html("");
            $("#dynamic_tour_data").html("");
            $("#invoice_id").val("");

        });
    });
    

</script>
</html>