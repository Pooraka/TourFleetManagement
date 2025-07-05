<?php
include_once '../commons/session.php';
include_once '../model/quotation_model.php';
include_once '../model/customer_model.php';
include_once '../model/customer_invoice_model.php';
include_once '../model/tour_model.php';
include_once '../model/finance_model.php';


//get user information from session
$userSession=$_SESSION["user"];
$user_id = $userSession['user_id'];



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
    
    case "generate_customer_invoice":
        
        $quotationId = base64_decode($_GET['quotation_id']);
        
        $quotationResult = $quotationObj->getQuotation($quotationId);
        $quotationRow = $quotationResult->fetch_assoc();
        
        $quotationItemResult = $quotationObj->getQuotationItems($quotationId);
        
        $invoiceNumber = "ST-I-" . date('Ymd') . "-" . $quotationId;
        
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
                $customerId, $invoiceDescription, $tourStartDate, $tourEndDate, $pickup, $destination, $dropoff, $roundTripMileage);
        
        while($quotationItemRow = $quotationItemResult->fetch_assoc()){
            
            $categoryId = $quotationItemRow["category_id"];
            $quantity = $quotationItemRow["quantity"];
            
            $customerInvoiceObj->addInvoiceItems($invoiceId, $categoryId, $quantity);
        }
        
        $quotationObj->changeQuotationStatus($quotationId, 2);
        
        $msg = "Invoice ".$invoiceNumber ." Generated Successfully";
        $msg = base64_encode($msg);
        ?>

            <script>
                window.location="../view/pending-quotations.php?msg=<?php echo $msg; ?>&success=true";
            </script>

        <?php
    break;
    
    case "cancel_customer_invoice":
        
        try{
        
            $invoiceId = base64_decode($_GET['invoice_id']);

            $tourResult = $tourObj->checkIfInvoiceHasAnActiveTour($invoiceId);

            if($tourResult->num_rows>0){
                throw New Exception ("Tour is assigned already. Please Cancel The Tour First, This Will Be Cancelled Automatically");
            }
            
            $customerInvoiceObj->changeInvoiceStatus($invoiceId, -1);
            
            $customerInvoiceResult = $customerInvoiceObj->getInvoiceItems($invoiceId);
            $customerInvoiceRow = $customerInvoiceResult->fetch_assoc();
            
            $quotationId = $customerInvoiceRow["quotation_id"];
            
            $quotationObj->changeQuotationStatus($quotationId, -1);

            $msg = "Invoice ".$customerInvoiceRow['invoice_number']." Cancelled Successfully";
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
    
    case "accept_payment":
        
        try{
        
            $invoiceId = base64_decode($_GET['invoice_id']);

            $actualFare = $_POST["actual_fare"];

            if($actualFare<=0){
                throw new Exception ("Actual Fair Cannot Be LKR 0.00 or Below");
            }
            
            if (!isset($_FILES["proof"]) || $_FILES["proof"]['error'] == UPLOAD_ERR_NO_FILE) {
                throw new Exception("Attach The Payment Proof To Accept The Payment");
            }
            
            if (!isset($_POST['payment_method'])) {
                throw new Exception("Select A Payment Method");
            }

            $paymentMethod = $_POST['payment_method'];
            
            $proofFile = $_FILES["proof"];
            
            $paymentProof = time()."_".$proofFile["name"];
            $path="../documents/customerpaymentproofs/$paymentProof";
            move_uploaded_file($proofFile["tmp_name"],$path);
            
            $customerInvoiceObj->addActualFare($invoiceId, $actualFare);
            
            $paymentDate = date('Y-m-d');
            
            $financeObj->acceptCustomerPayment($invoiceId, $paymentDate, $actualFare, $paymentMethod, $paymentProof, $user_id);
            
            $customerInvoiceObj->changeInvoiceStatus($invoiceId,4);
            
            $msg = "Payment Accepted Successfully";
            $msg = base64_encode($msg);
            ?>

                <script>
                    window.location="../view/pending-customer-invoices.php?msg=<?php echo $msg; ?>&success=true";
                </script>

            <?php

        }
        catch(Exception $e){
            
            $msg= $e->getMessage();
            $msg= base64_encode($msg);
            
            $invoiceId = base64_encode($invoiceId);
            ?>
    
            <script>
                window.location="../view/accept-customer-payment.php?msg=<?php echo $msg;?>&invoice_id=<?php echo $invoiceId?>";
            </script>
            <?php
        }
    break;
}