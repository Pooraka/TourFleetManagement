<?php
include_once '../commons/session.php';
include_once '../model/finance_model.php';
include_once '../model/service_detail_model.php';

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

$financeObj = new Finance();
$serviceDetailObj = new ServiceDetail();

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
            $path="../documents/servicepayments/$fileName";
            move_uploaded_file($paymentDocument["tmp_name"],$path);
            
            $totalPayment = $_POST['totalpayment'];
            
            $paymentId = $financeObj->makeSupplierPayment($date, $totalPayment, $reference, $paymentMethod,'1', $fileName, $userId);
            
            foreach($serviceIdArray as $serviceId){
                
                $serviceDetailObj->updatePaidService($serviceId, $paymentId);
            
            }
            
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
}
