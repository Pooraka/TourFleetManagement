<?php
include_once '../commons/session.php';
include_once '../model/quotation_model.php';
include_once '../model/customer_model.php';
include_once '../model/customer_invoice_model.php';
include_once '../model/tour_model.php';
include_once '../model/finance_model.php';


//get user information from session
$userSession=$_SESSION["user"];
$userId = $userSession['user_id'];



if(!isset($_GET["status"])){
    ?>
    <script>
        window.location= "../errorpages/403.php";
    </script>
    <?php
}

$customerObj = new Customer();
$quotationObj = new Quotation();
$customerInvoiceObj = new CustomerInvoice();
$tourObj = new Tour();
$financeObj = new Finance();

$status= $_GET["status"];

switch ($status){
    
    case "load_generate_invoice_modal":
    
        $quotationId = $_POST["quotationId"];
        $quotationResult = $quotationObj->getQuotation($quotationId);
        $quotationRow = $quotationResult->fetch_assoc();
        $quotedAmount = (float)$quotationRow["total_amount"]; 

        ?>
        <div class="row">
            <div class="col-md-3">
                <label class="control-label">Quoted Amount</label>
            </div>
            <div class="col-md-3">
                <input type="hidden" value="<?php echo $quotationId;?>" name="quotation_id"/>
                <span>LKR <?php echo number_format($quotedAmount,2);?></span>
            </div>
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
        </div>
        <div class="row">
            &nbsp;
        </div>
        <div class="row">
            <div class="col-md-9">
                <label class="control-label">Advance Payment (Must Be 20% or Higher From The Quoted Amount)</label>
            </div>
            <div class="col-md-3">
                <input type="number" name="advance_payment" id="advance_payment" class="form-control" 
                       value="<?php echo number_format($quotedAmount*0.20,2,'.','');?>" 
                       step="0.01" max="<?php echo $quotedAmount;?>" min="<?php echo number_format($quotedAmount*0.20,2,'.','');?>"
                       pattern="^\d+(\.\d{1,2})?$"/>
            </div>
        </div>
        <div class="row" id="receipt_upload_container" style="display:none; margin-top: 15px;">
            <div class="col-md-3">
                <label class="control-label">Upload Receipt</label>
            </div>
            <div class="col-md-9">
                <input type="file" name="transfer_receipt" class="form-control"/>
            </div>
        </div>
        <?php
        
    break;
    
    case "generate_customer_invoice":
        
        try{
        
            $quotationId = $_POST["quotation_id"];
            $quotationResult = $quotationObj->getQuotation($quotationId);
            $quotationRow = $quotationResult->fetch_assoc();
            $quotationItemResult = $quotationObj->getQuotationItems($quotationId);
            $quotedAmount = (float)$quotationRow["total_amount"];
            $transferReceiptFileName = "";
            
            $advancePaymentStr =$_POST["advance_payment"];
            
            $currencyFormat = "/^\d+(\.\d{1,2})?$/";
            
            if(!preg_match($currencyFormat,$advancePaymentStr)){
                throw new Exception("Please enter the amount with a maximum of 2 decimal points.");
            }
            
            $advancePayment = (float)$advancePaymentStr;
            
            if($advancePayment>$quotedAmount){
                throw new Exception("Advance Amount Cannot Exceed The Quoted Amount.");
            }
            if($advancePayment<$quotedAmount*0.20){
                throw new Exception("The advance payment must be at least 20% of the quoted amount.");
            }
            
            if(!isset($_POST["payment_method"])){
                throw new Exception("Payment Method Cannot Be Empty");
            }
            
            $paymentMethod = $_POST["payment_method"];
            
            if($paymentMethod==2){
                
                if (!isset($_FILES["transfer_receipt"]) || $_FILES["transfer_receipt"]['error'] == UPLOAD_ERR_NO_FILE) {
                    
                    throw new Exception("Funds Transfer Receipt Must Be Attached");
                }
                
                $transferReceiptFile = $_FILES["transfer_receipt"];
            
                $transferReceiptFileName = time()."_".$transferReceiptFile["name"];
                $path="../documents/customerpaymentproofs/$transferReceiptFileName";
                move_uploaded_file($transferReceiptFile["tmp_name"],$path);

            }
            
            $invoiceNumber = "ST-IN-" .strtoupper(bin2hex(random_bytes(2))). "-" . $quotationId;
            
            $invoiceDate = date('Y-m-d');
        
            $invoiceAmount = $quotationRow['total_amount'];

            $customerId = $quotationRow['customer_id'];

            $invoiceDescription = $quotationRow['description'];

            $tourStartDate = $quotationRow['tour_start_date'];

            $tourEndDate = $quotationRow['tour_end_date'];

            $pickup = $quotationRow['pickup_location'];

            $destination = $quotationRow['destination'];

            $dropoff = $quotationRow['dropoff_location'];

            $roundTripMileage = $quotationRow['round_trip_mileage'];
        
            $invoiceId = $customerInvoiceObj->generateCustomerInvoice($invoiceNumber, $quotationId, $invoiceDate, $invoiceAmount, 
                $customerId, $invoiceDescription, $tourStartDate, $tourEndDate, $pickup, $destination, $dropoff, $roundTripMileage,$advancePayment,$advancePayment);
            
            while($quotationItemRow = $quotationItemResult->fetch_assoc()){
            
                $categoryId = $quotationItemRow["category_id"];
                $quantity = $quotationItemRow["quantity"];

                $customerInvoiceObj->addInvoiceItems($invoiceId, $categoryId, $quantity);
            }
            
            $quotationObj->changeQuotationStatus($quotationId, 2);
            
            $receiptNo = "ST-RT-".strtoupper(bin2hex(random_bytes(2)))."-".$invoiceId;
            
            $tourIncomeId = $financeObj->acceptCustomerPayment($invoiceId,$receiptNo,$invoiceDate,$advancePayment,$paymentMethod,$transferReceiptFileName,1,$userId);
            
            $cashBookId = $financeObj->logInCashBook(3,$tourIncomeId,"Advance Booking Payment",$advancePayment,$userId,2);
            
            $msg = "Invoice ".$invoiceNumber ." Generated Successfully";
            $msg = base64_encode($msg);
            ?>

                <script>
                    window.location="../view/pending-quotations.php?msg=<?php echo $msg; ?>&success=true";
                </script>

            <?php
        }
        catch(Exception $e){
            
            $msg= $e->getMessage();
            $msg= base64_encode($msg);
            ?>
    
            <script>
                window.location="../view/pending-quotations.php?msg=<?php echo $msg;?>";
            </script>
            <?php
        }
        
    break;
    
    case "cancel_customer_invoice":
        
        try{
        
            $invoiceId = $_POST["invoice_id"];
            $invoiceResult = $customerInvoiceObj->getInvoice($invoiceId);
            $invoiceRow = $invoiceResult->fetch_assoc();
            
            $initialPaidAmount = (float)$invoiceRow["paid_amount"];
            $receiptNumber = "ST-RF-".strtoupper(bin2hex(random_bytes(2)))."-".$invoiceId;
            
            $refundReason = $_POST["refund_reason"];
            
            if($refundReason==""){
                throw new Exception("Select The Refund Reason");
            }
            
            $refundAmountStr = $_POST["refund_amount_input"];
            
            if($refundAmountStr!=0){
                
                //Taking refund amount as a negative amount
                $refundAmount = -((float)$refundAmountStr);
                
                $tourIncomeId = $financeObj->makeRefundTransaction($receiptNumber, $invoiceId, $refundAmount, 
                    1,3,$userId, $refundReason);
                
                $cashBookId = $financeObj->logInCashBook(3,$tourIncomeId,"Booking Refund",$refundAmount,$userId,1);
                
                $newPaidAmount = $initialPaidAmount + $refundAmount; // Added since refund amount is a negative number
                
                $customerInvoiceObj->updateInvoiceAfterRefund($newPaidAmount,$invoiceId);
            }
            
            $customerInvoiceObj->changeInvoiceStatus($invoiceId,-1);

            
            
            
            $msg = "Invoice Cancelled Successfully";
            $msg = base64_encode($msg);
            ?>

                <script>
                    window.location="../view/pending-quotations.php?msg=<?php echo $msg; ?>&success=true";
                </script>

            <?php
        
        }
        catch(Exception $e){
            
            $msg= $e->getMessage();
            $msg= base64_encode($msg);
            ?>
    
            <script>
                window.location="../view/pending-customer-invoices.php?msg=<?php echo $msg;?>";
            </script>
            <?php
        }
        
    break;
    
    case"load_refund_modal":
        
        $invoiceId = $_POST["invoiceId"];
        $invoiceResult = $customerInvoiceObj->getInvoice($invoiceId);
        $invoiceRow = $invoiceResult->fetch_assoc();
        
        $invoiceAmount = (float)$invoiceRow["invoice_amount"];
        $amountPaid = (float)$invoiceRow["paid_amount"];
        $nonRefundableAmount = $invoiceAmount*0.20;
        
        $defaultAmountToBeRefunded =(float)($amountPaid-$nonRefundableAmount>=0)?$amountPaid-$nonRefundableAmount:0.00;
        
        
        ?>
        
            <div class="row">
                <div class="col-md-3">
                    <label class="control-label">Invoice Amount</label>
                </div>
                <div class="col-md-3">
                    <span><?php echo "LKR ".number_format($invoiceAmount,2);?></span>
                </div>
                <div class="col-md-3">
                    <label class="control-label">Amount Paid</label>
                </div>
                <div class="col-md-3">
                    <span><?php echo "LKR ".number_format($amountPaid,2);?></span>
                </div>
            </div>
            <div class="row">
                &nbsp;
            </div>
            <div class="row">
                <div class="col-md-3">
                    <label class="control-label">Select Reason</label>
                </div>
                <div class="col-md-3">
                    <select class="form-control" name="refund_reason" id="refund_reason"
                            data-full-refund="<?php echo $amountPaid; ?>"
                            data-partial-refund="<?php echo $defaultAmountToBeRefunded; ?>">
                            
                        <option value="" selected>Select Reason</option>
                        <option value="1">Requested By Customer</option>
                        <option value="2">Due To Bus Unavailability</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="control-label">Refund Amount</label>
                </div>
                <div class="col-md-3">
                    <span id="refund_amount_span">LKR 0.00</span>
                    <input type="hidden" name="refund_amount_input" id="refund_amount_input" value="0.00">
                    <input type="hidden" name="invoice_id" value="<?php echo $invoiceId;?>"/>
                </div>
            </div>
            
        <?php
        
    break;

    case "booking-history-filtered":
        
        $invoiceDate = $_POST["invoiceDate"];
        $invoiceStatus = $_POST["invoiceStatus"];
        
        $invoiceResult = $customerInvoiceObj->getBookingHistoryFiltered($invoiceDate, $invoiceStatus);
        
        while($invoiceRow = $invoiceResult->fetch_assoc()){

            $invoiceId = $invoiceRow["invoice_id"];

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
                <a href="../reports/quotation.php?quotation_id=<?php echo base64_encode($invoiceRow['quotation_id']);?>" 
                    class="btn btn-xs btn-info" style="margin:2px;display:<?php echo checkPermissions(78);?>" target="_blank">
                     <span class="fa-solid fa-file-signature"></span>                                                  
                     Quotation
                </a>
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
        <?php }
        
    break;
    
    case "pending_customer_invoices_filtered":

        $dateFrom = $_POST["dateFrom"];
        $dateTo = $_POST["dateTo"];
        
        $pendingInvoiceResult = $customerInvoiceObj->getPendingCustomerInvoicesFiltered($dateFrom,$dateTo);
        
        while($pendingInvoiceRow = $pendingInvoiceResult->fetch_assoc()){
                                
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
            <td style="white-space: nowrap;text-align: right"><?php echo number_format($pendingInvoiceRow['invoice_amount'],2);?></td>
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
        <?php }
        
    break;
}