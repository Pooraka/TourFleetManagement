<?php

include_once '../commons/session.php';
include_once '../model/customer_invoice_model.php';
include_once '../model/finance_model.php';


//get user information from session
$userSession=$_SESSION["user"];

$customerInvoiceObj = new CustomerInvoice();
$pendingInvoiceResult = $customerInvoiceObj->getPendingCustomerInvoices();

$financeObj = new Finance();
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
        <?php $pageName="Booking Management - Pending Invoices" ?>
        <?php include_once "../includes/header_row_includes.php";?>
        <div class="col-md-3">
            <ul class="list-group">
                <a href="generate-quotation.php" class="list-group-item">
                    <span class="glyphicon glyphicon-plus"></span> &nbsp;
                    Generate Quotation
                </a>
                <a href="pending-quotations.php" class="list-group-item">
                    <span class="glyphicon glyphicon-search"></span> &nbsp;
                    Pending Quotations
                </a>
                <a href="pending-customer_invoices.php" class="list-group-item">
                    <span class="glyphicon glyphicon-search"></span> &nbsp;
                    Pending Invoice
                </a>
            </ul>
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
                    <table class="table" id="invoicetable">
                        <thead>
                            <tr>
                                <th>Invoice Date</th>
                                <th>Invoice No</th>
                                <th>Customer</th>
                                <th>Amount (LKR)</th>
                                <th>Advance Payment Method</th>
                                <th>Tour Date</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while($pendingInvoiceRow = $pendingInvoiceResult->fetch_assoc()){
                                
                                $invoiceId = $pendingInvoiceRow['invoice_id'];
                                
                                $tourIncomeResult = $financeObj->getTourIncomeRecordByInvoiceIdAndTourIncomeType($invoiceId,1);
                                $tourIncomeRow = $tourIncomeResult->fetch_assoc();
                                
                                $paymentMethod = match((int)$tourIncomeRow["payment_method"]){
                                    
                                    1=>"Cash",
                                    2=>"Funds Transfer"
                                };
                                
                                $invoiceStatus = match((int)$pendingInvoiceRow['invoice_status']){
    
                                    -1=>"Cancelled",
                                    1=>"Tour to be assigned",
                                    2=>"Tour Assigned",
                                    3=>"Tour Completed",
                                    4=>"Paid",
                                };     
                                ?>
                            <tr>
                                <td style="white-space: nowrap"><?php echo $pendingInvoiceRow['invoice_date'];?></td>
                                <td style="white-space: nowrap"><?php echo $pendingInvoiceRow['invoice_number'];?></td>
                                <td><?php echo $pendingInvoiceRow['customer_fname']." ".$pendingInvoiceRow['customer_lname'];?></td>
                                <td style="white-space: nowrap"><?php echo number_format($pendingInvoiceRow['invoice_amount'],2);?></td>
                                <td><?php echo $paymentMethod?></td>
                                <td style="white-space: nowrap"><?php echo $pendingInvoiceRow['tour_start_date'];?></td>
                                <td><?php echo $invoiceStatus;?></td>
                                <td>
                                    <a href="../reports/pendingInvoice.php?invoice_id=<?php echo base64_encode($invoiceId);?>" target="_blank" 
                                       class="btn btn-xs btn-info" style="margin:2px;display:<?php echo checkPermissions(157); ?>">
                                        <span class="fa-solid fa-eye"></span>                                                  
                                        Booking Confirmation
                                    </a>
                                    <?php if($tourIncomeRow["payment_method"]==2){ ?>
                                    <a href="../documents/customerpaymentproofs/<?php echo $tourIncomeRow['payment_proof'];?>" target="_blank" 
                                       class="btn btn-xs btn-info" style="margin:2px;display:<?php echo checkPermissions(157); ?>">
                                        <span class="fa-solid fa-eye"></span>                                                  
                                        Advance Payment Proof
                                    </a>
                                    <?php }?>
                                    <?php if($pendingInvoiceRow['invoice_status']==3){ ?>
                                    <a href="accept-customer-payment.php?invoice_id=<?php echo base64_encode($invoiceId);?>" 
                                       class="btn btn-xs btn-success" style="margin:2px;display:<?php echo checkPermissions(156); ?>">
                                        <span class="glyphicon glyphicon-ok"></span>                                                  
                                        Accept Payment
                                    </a>
                                    <?php } ?>
                                    <?php if($pendingInvoiceRow['invoice_status']==1){?>
                                    <a href="#" data-toggle="modal" data-target="#refundModal" onclick="setupRefundModal(<?php echo $invoiceId;?>)"
                                       class="btn btn-xs btn-danger" style="margin:2px;display:<?php echo checkPermissions(138); ?>">
                                        <span class="glyphicon glyphicon-remove"></span>                                                  
                                        Cancel & Refund
                                    </a>
                                    <?php }?>
                                </td>
                            </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</body>
<div class="modal fade" id="refundModal" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form action="../controller/customer_invoice_controller.php?status=cancel_customer_invoice" method="post" id="refund_form" enctype="multipart/form-data">
                <div class="modal-header"><b><h4>Cancel Invoice & Refund</h4></b></div>
            <div class="modal-body">
                <div id="display_data">
                </div>
            </div>
            <div class="modal-footer">
                <input type="submit" class="btn btn-success" value="Cancel Invoice & Refund" id="initialRefundButton"/>
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
            </form>
        </div>
    </div>
</div>
<div class="modal fade" id="confirmModal" role="dialog">
  <div class="modal-dialog modal-sm">
    <div class="modal-content">
      <div class="modal-body">
        <p>Are you sure you want to refund?</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-success" id="confirmRefundButton">Yes, Refund</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">No</button>
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

//        $("#invoicetable").DataTable();
        
        $('#invoicetable').DataTable({
            
            "scrollX": true
        });
    });
    
    function setupRefundModal(invoiceId){
        
        var url ="../controller/customer_invoice_controller.php?status=load_refund_modal";

        $.post(url,{invoiceId:invoiceId},function(data){
            $("#display_data").html(data).show();
            
        });
    }
    
    
//    $("#refund_reason").on('change',function(){
    $(document).on('change', '#refund_reason', function(){
        
        var reason = $(this).val();
        var refundAmount = 0.00;
        
        if (reason == "1") {
  
            refundAmount = $(this).data('partial-refund');
            
        } else if (reason == "2") {

            refundAmount = $(this).data('full-refund');
            
        }
        
        var formattedAmount = parseFloat(refundAmount).toLocaleString('en-LK', {
            style: 'currency',
            currency: 'LKR',
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        });
        
        $("#refund_amount_span").text(formattedAmount);
        $("#refund_amount_input").val(refundAmount);
    });
    
    $('#initialRefundButton').on('click', function(e) {
        
        e.preventDefault();
        $('#refundModal').modal('hide');     
        $('#confirmModal').modal('show');    
    });
    
    $(document).ready(function() {
        
        $("#confirmRefundButton").on('click', function() {
            
            $("#refund_form").submit();
        });
        
    });
</script>
</html>