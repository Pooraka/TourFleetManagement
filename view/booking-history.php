<?php

include_once '../commons/session.php';
include_once '../model/customer_invoice_model.php';
include_once '../model/finance_model.php';


//get user information from session
$userSession=$_SESSION["user"];

$customerInvoiceObj = new CustomerInvoice();
$invoiceResult = $customerInvoiceObj->getBookingHistory();

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
        <?php $pageName="Booking Management - Booking History" ?>
        <?php include_once "../includes/header_row_includes.php";?>
        <div class="col-md-3">
            <ul class="list-group">
                <a href="generate-quotation.php" class="list-group-item" style="display:<?php echo checkPermissions(76); ?>">
                    <span class="fa-solid fa-plus"></span> &nbsp;
                    Generate Quotation
                </a>
                <a href="pending-quotations.php" class="list-group-item" style="display:<?php echo checkPermissions(77); ?>">
                    <span class="fa-solid fa-hourglass-half"></span> &nbsp;
                    Pending Quotations
                </a>
                <a href="pending-customer-invoices.php" class="list-group-item" style="display:<?php echo checkPermissions(149); ?>">
                    <span class="fa-solid fa-file-invoice"></span> &nbsp;
                    Pending Invoices
                </a>
                <a href="customer-receipts.php" class="list-group-item" style="display:<?php echo checkPermissions(81); ?>">
                    <span class="fa-solid fa-receipt"></span> &nbsp;
                    Booking History
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
                <div class="col-md-2">
                    <label class="control-label">Invoice Date</label>
                </div>
                <div class="col-md-3">
                    <input type="date" value="" id="invoice_date" class="form-control" max="<?php echo date('Y-m-d'); ?>"/>
                </div>
                <div class="col-md-2">
                    <label class="control-label">Invoice Status</label>
                </div>
                <div class="col-md-3">
                    <select id="invoice_status" class="form-control">
                        <option value="">All Statuses</option>
                        <option value="4">Completed</option>
                        <option value="-1">Cancelled</option>
                    </select>
                </div>
                <div class="col-md-2 text-right">
                    <button type="button" class="btn btn-success" id="filter_button">Filter</button>
                </div>
            </div>
            <div class="row">
                &nbsp;
            </div>
            <div class="row">
                <div class="col-md-12">
                    <table class="table" id="invoicetable">
                        <thead>
                            <tr>
                                <th>Invoice Date</th>
                                <th>Invoice No</th>
                                <th>Customer</th>
                                <th>Amount Paid</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <?php $totalAmount = (float)0.00;?>
                        <tbody id="tbody_tag">
                            <?php while($invoiceRow = $invoiceResult->fetch_assoc()){
                                
                                $invoiceId = $invoiceRow["invoice_id"];
                                
                                $totalAmount += $invoiceRow["paid_amount"];
                                
                                $advancePaymentResult = $financeObj->getTourIncomeRecordByInvoiceIdAndTourIncomeType($invoiceId,1);
                                $advancePaymentRow = $advancePaymentResult->fetch_assoc();
                                
                                $finalPaymentResult = $financeObj->getTourIncomeRecordByInvoiceIdAndTourIncomeType($invoiceId,2);
                                
                                if($finalPaymentResult->num_rows==1){
                                    $finalPaymentRow = $finalPaymentResult->fetch_assoc();
                                }
                                
                                $status = match((int)$invoiceRow["invoice_status"]){
                                    -1=>"Cancelled",
                                    4=>"Completed"
                                };
                                
                                ?>
                            <tr>
                                <td style="white-space:nowrap"><?php echo $invoiceRow["invoice_date"];?></td>
                                <td style="white-space:nowrap"><?php echo $invoiceRow["invoice_number"];?></td>
                                <td><?php echo $invoiceRow["customer_fname"]." ".$invoiceRow["customer_lname"];?></td>
                                <td style="text-align: right;white-space:nowrap"><?php echo "LKR ".number_format($invoiceRow["paid_amount"],2);?></td>
                                <td><?php echo $status;?></td>
                                <td>
                                    <a href="../reports/pendingInvoice.php?invoice_id=<?php echo base64_encode($invoiceId);?>" target="_blank" 
                                       class="btn btn-xs btn-info" style="margin:2px">
                                        <span class="fa-solid fa-eye"></span>                                                  
                                        Booking Confirmation
                                    </a>
                                    <?php if($advancePaymentRow["payment_method"]==2){ ?>
                                    <a href="../documents/customerpaymentproofs/<?php echo $advancePaymentRow['payment_proof'];?>" target="_blank" 
                                       class="btn btn-xs btn-info" style="margin:2px">
                                        <span class="fa-solid fa-eye"></span>                                                  
                                        Advance Payment Proof
                                    </a>
                                    <?php }?>
                                    <?php if($invoiceRow["invoice_status"]==4){ ?>
                                    <a href="../reports/receipt.php?invoice_id=<?php echo base64_encode($invoiceId);?>" target="_blank" 
                                       class="btn btn-xs btn-primary" style="margin:2px">
                                        <span class="fa-solid fa-eye"></span>                                                  
                                        Final Receipt
                                    </a>
                                    <?php }?>
                                    <?php if($invoiceRow["invoice_status"]==4 && $finalPaymentResult->num_rows==1 && $finalPaymentRow["payment_method"]==2){ ?>
                                    <a href="../documents/customerpaymentproofs/<?php echo $finalPaymentRow['payment_proof'];?>" target="_blank" 
                                       class="btn btn-xs btn-primary" style="margin:2px">
                                        <span class="fa-solid fa-eye"></span>                                                  
                                        Final Payment Proof
                                    </a>
                                    <?php }?>
                                    <?php if($invoiceRow["invoice_status"]==-1){ ?>
                                    <a href="../reports/refund-note.php?invoice_id=<?php echo base64_encode($invoiceId);?>" target="_blank" 
                                       class="btn btn-xs btn-warning" style="margin:2px">
                                        <span class="fa-solid fa-eye"></span>                                                  
                                        Refund Note
                                    </a>
                                    <?php }?>
                                </td>
                            </tr>
                            <?php }?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <th style="text-align:right;">Page Total:</th>
                                <th style="text-align:right;white-space: nowrap"></th> 
                                <th style="text-align:right;">Overall Total:</th>
                                <th style="text-align:right;white-space: nowrap"></th> 
                                <th></th>
                                <th></th>
                            </tr>
                        </tfoot>
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

        var dataTableOptions = {
            "pageLength": 5,
            "order": [
                [ 0, "desc" ] //Desc order by invoice date
            ],
            // "scrollX": true,
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
        
        var table = $("#invoicetable").DataTable(dataTableOptions);
        

        $('#filter_button').on('click', function(){
            
            var invoiceDate = $("#invoice_date").val();
            var invoiceStatus = $("#invoice_status").val();
            
            var url = "../controller/customer_invoice_controller.php?status=booking-history-filtered";
            
            $.post(url, {invoiceDate:invoiceDate, invoiceStatus:invoiceStatus}, function (data) {

                // Destroy the old DataTable instance.
                table.destroy();

                // Update the table body with the new filtered data.
                $("#tbody_tag").html(data);

                // Re-initialize the DataTable with the new content.
                table = $("#invoicetable").DataTable(dataTableOptions);
            });
        });
    });
</script>
</html>