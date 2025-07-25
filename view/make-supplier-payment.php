<?php

include_once '../commons/session.php';
include_once '../model/supplier_model.php';
include_once '../model/purchase_order_model.php';


//get user information from session
$userSession=$_SESSION["user"];

if(!isset($_GET['supplier_id'])||$_GET['supplier_id']==""){?>
    <script>
        window.location="pending-supplier-payments.php";
    </script>
<?php
}

$supplierId = base64_decode($_GET['supplier_id']);

$supplierObj = new Supplier();
$supplierResult = $supplierObj->getSupplier($supplierId);
$supplierRow = $supplierResult->fetch_assoc();

$poObj = new PurchaseOrder();
$poResult = $poObj->getPaymentPendingInvoices($supplierId);


?>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Finance Management</title>
    <?php include_once "../includes/bootstrap_css_includes.php"?>
</head>
<body>
    <div class="container">
        <?php $pageName="Finance Management - Make Supplier Payments" ?>
        <?php include_once "../includes/header_row_includes.php";?>
        <div class="col-md-3">
            <?php include_once "../includes/finance_functions.php"; ?>
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
                <form id="makeSupplierPaymentForm" action="../controller/finance_controller.php?status=make_supplier_payment" method="post" enctype="multipart/form-data"> 
                <div class="panel panel-info">
                    <div class="panel-heading"><?php echo "<b>".$supplierRow['supplier_name']."</b>"." Payments";?> </div>
                    <div class="panel-body">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Select</th>
                                    <th>PO Number</th>
                                    <th>PO Date</th>
                                    <th>Amount</th>
                                    <th>Invoice Number</th>                                    
                                    <th>View Invoice</th>                                    
                                </tr>
                            </thead>
                            <tbody>
                                <?php while($poRow = $poResult->fetch_assoc()){ ?>
                                <tr>
                                    <td style="text-align: center"><input name="invoice[]" type="checkbox" class="amount-check" data-amount="<?php echo $poRow['total_amount'];?>" value="<?php echo $poRow['po_id'];?>"></td>
                                    <td><?php echo $poRow['po_number'];?></td>
                                    <td><?php echo $poRow['order_date'];?></td>
                                    <td><?php echo "LKR ".number_format($poRow['total_amount'],2);?></td>
                                    <td><?php echo $poRow['supplier_invoice_number'];?></td>
                                    <td>
                                        <a href="../documents/supplierinvoices/<?php echo $poRow['supplier_invoice']; ?>" target="_blank" class="btn btn-primary">
                                            <span class="glyphicon glyphicon-file"></span> View Invoice
                                        </a>
                                    </td>
                                </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="panel-heading">Process Payment</div>
                    <div class="panel-footer">
                        <div class="row">
                            <div class="col-md-12">
                                <h4><b>Total Amount (LKR):<span id="total"></span></b></h4>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3">
                                <h4>Payment type</h4>
                            </div>
                            <div class="col-md-6">
                                <h4>
                                    <input type="radio" name="payment_method" value="Cheque"/>
                                    <label>Cheque</label>
                                    &nbsp;&nbsp;
                                    <input type="radio" name="payment_method" value="Transfer"/>
                                    <label>Funds Transfer</label>
                                </h4>
                            </div>
                            <div class="col-md-3">&nbsp;</div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <h4><span>Cheque Number or Funds Transfer reference</span></h4>
                            </div>
                            <div class="col-md-6">
                                 <input type="text" class="form-control" id="reference" name="reference" placeholder="FT125485 or 254785"/>
                            </div>
                        </div>
                        <div class="row">
                            &nbsp;
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <h4><span>Attach payment document</span></h4>
                            </div>
                            <div class="col-md-6">
                                <input type="file" class="form-control" id="payment_document" name="payment_document"/>
                            </div>
                        </div>
                        <div class="row">
                            <input type="hidden" name="supplier_id" value="<?php echo $supplierId; ?>"/>
                            <input type="hidden" id="totalpaymenttosubmit" name="totalpayment" value=""/>
                            &nbsp;
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <input type="submit" class="btn btn-success" value="Payment Made"/>
                                <input type="reset" class="btn btn-danger" value="Reset" style="width:130px"/>
                            </div>
                        </div>
                    </div>
                </div>
                </form>
            </div>
        </div>
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
    $(document).ready(function(){
        
        function calculateTotal(){
            
            let total = 0;
            
            $('.amount-check:checked').each(function () {
                
                
                total += parseFloat(String($(this).data('amount')));
                
                $('input[name="totalpayment"]').val(total);
            });
            
            total = total.toLocaleString('en-US', {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
            });
            $('#total').text(total);
            
        }
        
        $('.amount-check').on('change',function(){
            calculateTotal();
        });
        
        calculateTotal();

        $("#makeSupplierPaymentForm").on("submit", function(e) {

            e.preventDefault();
            

            if($('input[name="invoice[]"]:checked').length === 0) {

                $("#msg").addClass("alert alert-danger");
                $("#msg").html("No invoice is selected.");
                return false;
            }

            var paymentMethod = $('input[name="payment_method"]:checked').val();

            if (!paymentMethod) {
                $("#msg").addClass("alert alert-danger");
                $("#msg").html("Please select a payment method.");
                return false;
            }

            var reference = $('#reference').val().trim();

            if(reference == "") {
                $("#msg").addClass("alert alert-danger");
                $("#msg").html("Please enter a reference number.");
                return false;
            }

            var paymentDocument = $('#payment_document').val();

            if(paymentDocument == "") {
                $("#msg").addClass("alert alert-danger");
                $("#msg").html("Please attach a payment document.");
                return false;
            }

            // If all validation passes, show the modal.
            $("#confirmationModal").modal('show');
            
            //set up the confirmation button to perform the actual submission.
            $("#confirmActionBtn").off("click").on("click", function() {
                // To avoid this validation logic from running again in a loop,
                // Remove the handler and then trigger the native form submission.
                $("#makeSupplierPaymentForm").off("submit").submit();
            });
        });
        
    });
</script>
</html>