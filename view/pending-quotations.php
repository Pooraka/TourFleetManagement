<?php

include_once '../commons/session.php';
include_once '../model/quotation_model.php';

//get user information from session
$userSession=$_SESSION["user"];

$dateFrom = "";
$dateTo = "";

$quotationObj = new Quotation();
$pendingQuotationResult = $quotationObj->getPendingQuotationsFitered($dateFrom,$dateTo);

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
        <?php $pageName="Booking Management - Pending Quotations" ?>
        <?php include_once "../includes/header_row_includes.php";?>
        <div class="col-md-3">
            <?php include_once "../includes/booking_functions.php"; ?>
        </div>
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
                <div class="col-md-8">
                    <label class="control-label">Select Quotation Issued Date Range To Filter (Keep Blank for All)</label>
                </div>
                <div class="col-md-4 text-right">
                    <button type="button" class="btn btn-success" id="filter_button">Filter</button>
                </div>
            </div>
            <div class="row">
                &nbsp;
            </div>
            <div class="row">
                <div class="col-md-3">
                    <label class="control-label">From Date</label>
                </div>
                <div class="col-md-3">
                    <input type="date" class="form-control" id="dateFrom" name="dateFrom" max="<?php echo date("Y-m-d"); ?>"/>
                </div>
                <div class="col-md-3">
                    <label class="control-label">To Date</label>
                </div>
                <div class="col-md-3">
                    <input type="date" class="form-control" id="dateTo" name="dateTo" max="<?php echo date("Y-m-d"); ?>"/>
                </div>
            </div>
            <div class="row">
                &nbsp;
            </div>
            <div class="row">
                <div class="col-md-12">
                    <table class="table" id="quotationtable" style="">
                        <thead>
                            <tr>
                                <th>Quotation Id</th>
                                <th>Issued Date</th>
                                <th>Customer</th>
                                <th>Amount</th>
                                <th>Tour Date</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody id="quotationtableBody">
                            <?php while($pendingQuotationRow = $pendingQuotationResult->fetch_assoc()){?>
                            <tr>
                                <td style="text-align: center"><?php echo $pendingQuotationRow['quotation_id'];?></td>
                                <td style="white-space: nowrap"><?php echo $pendingQuotationRow['issued_date'];?></td>
                                <td><?php echo htmlspecialchars($pendingQuotationRow['customer_fname']." ".$pendingQuotationRow['customer_lname']);?></td>
                                <td style="white-space: nowrap;text-align: right"><?php echo "LKR ".number_format($pendingQuotationRow['total_amount'],2);?></td>
                                <td style="white-space: nowrap"><?php echo $pendingQuotationRow['tour_start_date'];?></td>
                                <td>
                                    <a href="../reports/quotation.php?quotation_id=<?php echo base64_encode($pendingQuotationRow['quotation_id']);?>" 
                                       class="btn btn-xs btn-info" style="margin:2px;display:<?php echo checkPermissions(78);?>" target="_blank">
                                        <span class="glyphicon glyphicon-search"></span>                                                  
                                        View
                                    </a>
                                    <a href="#" class="btn btn-xs btn-success" data-toggle="modal" data-target="#generateInvoiceModal" 
                                       onclick="generateInvoiceModal(<?php echo $pendingQuotationRow['quotation_id'];?>)" 
                                       style="margin:2px;display:<?php echo checkPermissions(79);?>">
                                        <span class="glyphicon glyphicon-ok"></span>                                                  
                                        Generate Invoice
                                    </a>
                                    <a href="../controller/quotation_controller.php?status=cancel_quotation&quotation_id=<?php echo base64_encode($pendingQuotationRow['quotation_id']);?>" 
                                       class="btn btn-xs btn-danger cancel-quotation-btn" style="margin:2px;display:<?php echo checkPermissions(80);?>">
                                        <span class="glyphicon glyphicon-remove"></span>                                                  
                                        Cancel
                                    </a>
                                </td>
                            </tr>
                            <?php } ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <th>Page Total:</th>
                                <th style="white-space: nowrap;text-align: right"></th>
                                <th style="white-space: nowrap;">Total Amt:</th>
                                <th style="white-space: nowrap;text-align: right"></th>
                                <th colspan="2"></th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</body>
<div class="modal fade" id="generateInvoiceModal" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form id="generateInvoiceModalForm" action="../controller/customer_invoice_controller.php?status=generate_customer_invoice" method="post" enctype="multipart/form-data">
                <div class="modal-header"><b><h4>Generate Invoice</h4></b></div>
            <div class="modal-body">
                <div id="display_data">
                </div>
            </div>
            <div class="modal-footer">
                <input type="submit" id="generateInvoiceSubmitBtn" class="btn btn-success" value="Generate Invoice"/>
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
            </form>
        </div>
    </div>
</div>
<div class="modal fade" id="generateInvoiceConfirmationModal" tabindex="-1" role="dialog" aria-labelledby="modalLabel">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="modalLabel">Confirm Action</h4>
            </div>
            <div class="modal-body">
                Are you sure you want to approve the quotation and generate the invoice?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="generateInvoiceConfirmActionBtn">Confirm</button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="cancelQuotationConfirmationModal" tabindex="-1" role="dialog" aria-labelledby="modalLabel">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="modalLabel">Confirm Action</h4>
            </div>
            <div class="modal-body">
                Are you sure you want to cancel the quotation?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="cancelQuotationConfirmActionBtn">Confirm</button>
            </div>
        </div>
    </div>
</div>
<script src="../js/datatable/jquery-3.5.1.js"></script>
<script src="../js/datatable/jquery.dataTables.min.js"></script>
<script src="../js/datatable/dataTables.bootstrap.min.js"></script>
<script src="../bootstrap/js/bootstrap.min.js"></script>
<script>
    $(document).ready(function(){
        
        var dataTableOptions = {
            
            "pageLength": 5,
            "order": [
                [ 1, "desc" ] //Desc order by quotation date
            ],
             //"scrollX": true,
            "footerCallback": function ( row, data, start, end, display ) {
                var api = this.api();
                
                // Calculate Total for the Current Page
                var pageTotal = 0;
                var pageData = api.column(3, { page: 'current' }).data(); // Get data for current page only
                
                for (var i = 0; i < pageData.length; i++) {
                    var amount = pageData[i];
                    var numericValue = parseFloat(String(amount).replace(/LKR /g, '').replace(/,/g, ''));
                    if (!isNaN(numericValue)) {
                       pageTotal += numericValue;
                    }
                }
                
                // Format and display the page total in the 2nd footer cell
                var formattedPageTotal = 'LKR ' + pageTotal.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ",");
                $(api.column(1).footer()).html(formattedPageTotal);
                
                
                //Calculate Overall Total for All Filtered Pages
                
                var overallTotal = 0;
                var overallData = api.column(3, { search: 'applied' }).data(); // Get data for all filtered pages
                
                for (var i = 0; i < overallData.length; i++) {
                    
                    var amount = overallData[i];
                    var numericValue = parseFloat(String(amount).replace(/LKR /g, '').replace(/,/g, ''));
                    
                    if (!isNaN(numericValue)) {
                       overallTotal += numericValue;
                    }
                }
                
                // Format and display the overall total in the 4th footer cell
                var formattedOverallTotal = 'LKR ' + overallTotal.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ",");
                $(api.column(3).footer()).html(formattedOverallTotal);
            }
        };
        
        var table = $("#quotationtable").DataTable(dataTableOptions);
        
        $('#filter_button').on('click', function(){

            $("#msg").html("");
            $("#msg").removeClass("alert alert-danger");
            
            var dateFrom = $("#dateFrom").val();
            var dateTo = $("#dateTo").val();

            if(dateFrom!="" || dateTo!=""){

                if(dateFrom ==""){
                    $("#msg").html("Both Dates Must Be Selected To Get The Report For A Period");
                    $("#msg").addClass("alert alert-danger");
                    return false;
                }
                if(dateTo ==""){
                    $("#msg").html("Both Dates Must Be Selected To Get The Report For A Period");
                    $("#msg").addClass("alert alert-danger");
                    return false;
                }
                
                if(dateFrom>dateTo){
                    $("#msg").html("'From' Date Cannot Be Greater Than 'To' Date");
                    $("#msg").addClass("alert alert-danger");
                    return false;
                }

            }
            
            var url = "../controller/quotation_controller.php?status=pending_quotation_filtered";

            $.post(url, {dateFrom:dateFrom, dateTo:dateTo}, function (data) {

                // Destroy the old DataTable instance.
                table.destroy();

                // Update the table body with the new filtered data.
                $("#quotationtableBody").html(data);

                // Re-initialize the DataTable with the new content.
                table = $("#quotationtable").DataTable(dataTableOptions);
            });
        });

        $('#generateInvoiceModalForm').on('submit', function(event) {

            //Validate that a payment method has been selected
            const paymentMethod = $('input[name="payment_method"]:checked'); //
            if (paymentMethod.length === 0) {
                alert('Please select a payment method.');
                event.preventDefault();
                return false;
            }

            //Validate that an advance payment amount has been entered
            const advancePayment = parseFloat($('#advance_payment').val()); //
            if (isNaN(advancePayment) || advancePayment <= 0) {
                alert('Please enter a valid advance payment amount.');
                event.preventDefault();
                return false;
            }

            
            //Conditionally validate that a receipt is uploaded for Funds Transfer
            if (paymentMethod.val() == '2') { //
                const receiptInput = $('input[name="transfer_receipt"]'); //
                if (receiptInput.val() === '') {
                    alert('Please upload the funds transfer receipt to proceed.');
                    event.preventDefault();
                    return false;
                }
            }
        });

        $("#quotationtable").on("click", ".cancel-quotation-btn", function(e) {
            e.preventDefault();
            
            var cancelUrl = $(this).attr("href");
            $("#cancelQuotationConfirmationModal").modal("show");
            $("#cancelQuotationConfirmActionBtn").off("click").on("click", function() {
                window.location.href = cancelUrl;
            });
        });


        $("#generateInvoiceSubmitBtn").on("click", function(e) {

            e.preventDefault();
            $("#generateInvoiceModal").modal("hide");
            $("#generateInvoiceConfirmationModal").modal("show");
        });

        $("#generateInvoiceConfirmActionBtn").on("click", function() {

            $("#generateInvoiceModalForm").submit();
        });



    });
    
    function generateInvoiceModal(quotationId){
        var url ="../controller/customer_invoice_controller.php?status=load_generate_invoice_modal";

        $.post(url,{quotationId:quotationId},function(data){
            $("#display_data").html(data).show();
            
        });
    }
    
    $(document).on('change', 'input[name="payment_method"]', function(){
        
        if ($(this).val() == '2') {
            
            $('#receipt_upload_container').slideDown();
        } else {
            
            $('#receipt_upload_container').slideUp();
        }
    });
    
    
</script>
</html>
