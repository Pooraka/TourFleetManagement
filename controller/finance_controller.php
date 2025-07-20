<?php
include_once '../commons/session.php';
include_once '../model/finance_model.php';
include_once '../model/service_detail_model.php';
include_once '../model/purchase_order_model.php';
include_once '../model/quotation_model.php';
include_once '../model/customer_model.php';
include_once '../model/customer_invoice_model.php';
include_once '../model/tour_model.php';

//get user information from session
$userSession=$_SESSION["user"];
$userId = $userSession['user_id'];
$date = date('Y-m-d');
$dateTime = date('Y-m-d H:i:s');

if(!isset($_GET["status"])){
    ?>
    <script>
        window.location= "../view/login.php";
    </script>
    <?php
}

$customerObj = new Customer();
$quotationObj = new Quotation();
$customerInvoiceObj = new CustomerInvoice();
$tourObj = new Tour();
$financeObj = new Finance();
$serviceDetailObj = new ServiceDetail();
$poObj = new PurchaseOrder();

$status= $_GET["status"];

switch ($status){
    
    case "make_service_payment":
        
        try{
        
            $serviceStationId = $_POST['service_station_id'];

            if(!empty($_POST['service'])){
                $serviceIdArray = $_POST['service'];
            }
            else{
                throw new Exception("Select at least 1 service");
            }
            
            if(!empty($_POST['payment_method'])){
                $paymentMethod = $_POST['payment_method'];
            }
            else{
                throw new Exception("Select the payment type");
            }
            
            $reference = $_POST['reference'];
            
            if($reference==""){
                throw new Exception("Please enter cheque number or funds transfer reference");
            }
            
            $paymentDocument = $_FILES['payment_document'];
            $extension="";
            
            if($paymentDocument['size'] <= 0){
                throw new Exception("Payment document Must Be Attached");
            }
            if($paymentDocument['type']=="image/jpeg"){
                $extension=".jpg";
            }elseif ($paymentDocument['type']=="image/png") {
                $extension=".jpg";
            }elseif ($paymentDocument['type']=="application/pdf") {
                $extension=".pdf";
            }else{
                throw new Exception("Payment document File Type Not Supported, Please Attach a PDF/PNG/JPEG");
            }
            
            $fileName= uniqid('svspmt_').$extension;
            $path="../documents/paymentsmade/$fileName";
            move_uploaded_file($paymentDocument["tmp_name"],$path);
            
            $totalPayment = (float)$_POST['totalpayment'];
            
            $paymentId = $financeObj->makeServiceStationPayment($date, $totalPayment, $reference, $paymentMethod,'1', $fileName, $userId);
            
            foreach($serviceIdArray as $serviceId){
                
                $serviceDetailObj->updatePaidService($serviceId, $paymentId);
            
            }
            
            $txnAmount = -($totalPayment);
            
            $cashBookId = $financeObj->logInCashBook(1,$paymentId,"Service Payment",$txnAmount,$userId,1);
            
            $msg = "Payment Updated Successfully";
            $msg = base64_encode($msg);
            ?>
                <script>
                    window.location="../view/pending-service-payments.php?msg=<?php echo $msg; ?>&success=true";
                </script>
            <?php

        }
        catch(Exception $e){
            
            $msg= $e->getMessage();
            $msg= base64_encode($msg);
            
            $serviceStationId = base64_encode($serviceStationId);
            ?>
    
            <script>
                window.location="../view/make-service-payment.php?service_station_id=<?php echo $serviceStationId;?>&msg=<?php echo $msg;?>";
            </script>
            <?php
            
        }
    break; 
    
    case "make_supplier_payment":
        
        try{
        
            $supplierId = $_POST['supplier_id'];
            
            if(!empty($_POST['invoice'])){
                $poIdArray = $_POST['invoice'];
            }
            else{
                throw new Exception("Select at least 1 Invoice");
            }
            
            if(!empty($_POST['payment_method'])){
                $paymentMethod = $_POST['payment_method'];
            }
            else{
                throw new Exception("Select the payment type");
            }
            
            $reference = $_POST['reference'];
            
            if($reference==""){
                throw new Exception("Please enter cheque number or funds transfer reference");
            }
            
            $paymentDocument = $_FILES['payment_document'];
            $extension="";
            
            if($paymentDocument['size'] <= 0){
                throw new Exception("Payment document Must Be Attached");
            }
            if($paymentDocument['type']=="image/jpeg"){
                $extension=".jpg";
            }elseif ($paymentDocument['type']=="image/png") {
                $extension=".jpg";
            }elseif ($paymentDocument['type']=="application/pdf") {
                $extension=".pdf";
            }else{
                throw new Exception("Payment document File Type Not Supported, Please Attach a PDF/PNG/JPEG");
            }
            
            $fileName= uniqid('suppmt_').$extension;
            $path="../documents/paymentsmade/$fileName";
            move_uploaded_file($paymentDocument["tmp_name"],$path);
            
            $totalPayment = (float)$_POST['totalpayment'];
            
            $paymentId = $financeObj->makeSupplierPayment($date,$totalPayment,$reference,$paymentMethod,2,$fileName,$userId);
            
            foreach($poIdArray as $poId){
                
                $poObj->updatePaidPOs($poId, $paymentId);
            }
            
            $txnAmount = -($totalPayment);
            
            $cashBookId = $financeObj->logInCashBook(2,$paymentId,"Supplier Payment",$txnAmount,$userId,1);
            
            $msg = "Payment Updated Successfully";
            $msg = base64_encode($msg);
            ?>
                <script>
                    window.location="../view/pending-supplier-payments.php?msg=<?php echo $msg; ?>&success=true";
                </script>
            <?php
        
        }
        catch(Exception $e){
            
            $msg= $e->getMessage();
            $msg= base64_encode($msg);
            
            $supplierId = base64_encode($supplierId);
            ?>
    
            <script>
                window.location="../view/make-supplier-payment.php?supplier_id=<?php echo $supplierId;?>&msg=<?php echo $msg;?>";
            </script>
            <?php
            
        }
        
    break;
    
    case "accept_payment":
        
        try{
        
            $invoiceId = $_POST["invoice_id"];
            $invoiceResult = $customerInvoiceObj->getInvoice($invoiceId);
            $invoiceRow = $invoiceResult->fetch_assoc();
            $transferReceiptFileName="";
            
            $advancePayment = (float)$invoiceRow["advance_payment"];
            
            $invoiceAmount = (float)$invoiceRow["invoice_amount"];
            
            $actualFare = (float)$_POST["actual_fare"];
            
            if($actualFare==0){
                throw new Exception("Actual Fare Cannot Be LKR 0.00");
            }
            
            if($actualFare<$invoiceAmount){
                throw new Exception ("Actual Fare Cannot Be Less Than Invoice Value");
            }
            
            $paymentMade = (float)$_POST["paymentMade"];
            
            if($paymentMade>0){
                
                if(!isset($_POST["payment_method"])){
                    throw new Exception("Select The Payment Method");
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
                
                $receiptNo = "ST-RT-".strtoupper(bin2hex(random_bytes(2)))."-".$invoiceId;
                
                $tourIncomeId = $financeObj->acceptCustomerPayment($invoiceId,$receiptNo,$date,$paymentMade,$paymentMethod,$transferReceiptFileName,2,$userId);
                
                $cashBookId = $financeObj->logInCashBook(3,$tourIncomeId,"Final Booking Payment",$paymentMade,$userId,2);
            }
            
            $totalPaidAmount = $advancePayment+$paymentMade;
            
            $customerInvoiceObj->updateInvoiceAfterFinalPayment($totalPaidAmount,$actualFare);
            
            $msg = "Invoice Completed Successfully";
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

    case "tour_income_trend":
        
        header('Content-Type: application/json');
        
        try{
            
            $startDate =  $_POST['startDate'];
            $endDate =  $_POST['endDate'];
            
            if (empty($startDate) || empty($endDate)) {
                throw new Exception("Start and End dates are required.");
            }
            
            if ($startDate > $endDate) {
                throw new Exception("End date should be greater than start date.");
            }
            
            $tourIncomeResult = $financeObj->getTourIncomeForAPeriod($startDate, $endDate);
            
            $dates = [];
            $income = [];
            
            if($tourIncomeResult->num_rows>0){
                
                while($tourIncomeRow = $tourIncomeResult->fetch_assoc()){
                    
                    array_push($dates,$tourIncomeRow["date"]);
                    array_push($income,$tourIncomeRow["total_income"]);
                }
            }
            
            echo json_encode(['dates' => $dates, 'income' => $income]);
            
            
            
        }
        catch(Excpetion $e){
            
            echo json_encode(['error' => $e->getMessage()]);
        }
        
    break;
}
