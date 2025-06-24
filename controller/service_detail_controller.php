<?php
include '../commons/session.php';
include_once '../model/bus_model.php';
include_once '../model/service_detail_model.php';

//get user information from session
$userSession=$_SESSION["user"];
$userId = $userSession['user_id'];

$busObj = new Bus();
$serviceDetailObj = new ServiceDetail();

if(!isset($_GET["status"])){
    ?>
    <script>
        window.location= "../view/login.php";
    </script>
    <?php
}

$status= $_GET["status"];

switch ($status){
    
    case "initiate_service":
        
        try{
        
            $serviceStationId = $_POST["service_station_id"];
            $busId = $_POST["bus_id"];
            $currentMileage = $_POST["currentmileage"];
            
            if($serviceStationId==""){
                throw new Exception("Select a Service Station");
            }
            if($busId==""){
                throw new Exception("Select a Vehicle");
            }
            if($currentMileage==""){
                throw new Exception("Enter the Current Mileage");
            }
            if($currentMileage<0){
                throw new Exception("Current Mileage Cannot Be Less Than 0 Km");
            }

            
            $busResult = $busObj->getBus($busId);
            $busRow = $busResult->fetch_assoc();
            
            $startDate = date('Y-m-d');
//            $currentMileageAsAt = date('Y-m-d H:i:s', time());
            
            $serviceDetailObj->initiateService($busId, $serviceStationId, $startDate, $currentMileage,$busRow['bus_status'],$userId);
            
//            $busObj->updateBusMileage($busId, $currentMileage, $currentMileageAsAt);
            $busObj->changeBusStatus($busId,3);
            
            $msg= $busRow['vehicle_no']." Service Initiated";
            $msg= base64_encode($msg);
            ?>
    
            <script>
                window.location="../view/view-ongoing-services.php?msg=<?php echo $msg;?>&success=true";
            </script>
            <?php
        }
        catch(Exception $e){
            
            $msg= $e->getMessage();
            $msg= base64_encode($msg);
            ?>
    
            <script>
                window.location="../view/initiate-service.php?msg=<?php echo $msg;?>";
            </script>
            <?php
        }
    break;
    
    case "cancel_service":
        
        $serviceId = $_GET['service_id'];
        $serviceId = base64_decode($serviceId);
        
        $serviceDetailResult = $serviceDetailObj->getServiceDetail($serviceId);
        $serviceDetailRow = $serviceDetailResult->fetch_assoc();
        
        //if someone tries to cancel a service that is not ongoing(by URL) they will be directed to view ongoing services
        if ($serviceDetailRow['service_status'] != 1) {
            ?>
                
                <script>
                    window.location="/tourfleetmanagement/view/view-ongoing-services.php";
                </script>
            <?php
            exit();
        }

        $previousBusStatus = $serviceDetailRow['previous_bus_status'];
        $busId = $serviceDetailRow['bus_id'];
        
        $busObj->changeBusStatus($busId, $previousBusStatus);
        
        $cancelledDate = date('Y-m-d');
        
        $serviceDetailObj->cancelService($serviceId,$userId,$cancelledDate);
        
        $msg = "Service Cancelled Successfully";
        $msg = base64_encode($msg);
        
        ?>
            <script>
                window.location="../view/view-ongoing-services.php?msg=<?php echo $msg;?>&success=true";
            </script>
        <?php
        
    break;

    case "complete_service":
        
        try{
            
            $serviceId = $_POST['service_id'];
            $cost = $_POST['cost'];
            $completedDate = date('Y-m-d');
            $invoice = $_FILES['invoice'];
            $mileageAtService = $_POST['mileage_at_service'];
            $busId = $_POST['bus_id'];
            $extension="";
            $invoiceNumber = $_POST['invoice_number'];
            
            if($invoiceNumber==""){
                throw new Exception("Invoice Number Cannot Be Empty");
            }
            if($cost==""){
                throw new Exception("Cost Cannot Be Empty");
            }
            if($cost<=0){
                throw new Exception("Cost Must Be Higher Than 0 LKR");
            }
            if($invoice['size'] <= 0){
                throw new Exception("Invoice Must Be Attached");
            }
            if($invoice['type']=="image/jpeg"){
                $extension=".jpg";
            }elseif ($invoice['type']=="image/png") {
                $extension=".jpg";
            }elseif ($invoice['type']=="application/pdf") {
                $extension=".pdf";
            }else{
                throw new Exception("Invoice File Type Not Supported, Please Attach a PDF/PNG/JPEG");
            }
            
            $fileName= uniqid('svsinv_').$extension;
            $path="../documents/busserviceinvoices/$fileName";
            move_uploaded_file($invoice["tmp_name"],$path);
            
            $serviceDetailObj->completeService($serviceId, $completedDate, $cost, $fileName, $userId,$invoiceNumber);
            $busObj->updateServicedBus($busId, $mileageAtService, $completedDate);
            
            $currentMileageAsAt = date('Y-m-d H:i:s', time());
            $busObj->updateBusMileage($busId, $mileageAtService, $currentMileageAsAt);
            
            $msg = "Service Completed Successfully";
            $msg = base64_encode($msg);
            ?>
                <script>
                    window.location="../view/view-ongoing-services.php?msg=<?php echo $msg; ?>&success=true";
                </script>
            <?php
        }
        catch(Exception $e){
            
            $serviceId = base64_encode($serviceId);
            
            $msg= $e->getMessage();
            $msg= base64_encode($msg);
            ?>
    
            <script>
                window.location="../view/complete-service.php?msg=<?php echo $msg;?>&service_id='<?php echo $serviceId;?>'";
            </script>
            <?php
        }
        
    break;
    
    case "update_service":
        
        try{
        
            $serviceId = $_POST['service_id'];
            $cost = $_POST['cost'];
            $invoiceNumber = $_POST['invoice_number'];
            $invoice = $_FILES['invoice'];
            $extension="";
            
            $serviceDetailResult = $serviceDetailObj->getServiceDetail($serviceId);
            $serviceDetailRow = $serviceDetailResult->fetch_assoc();
            
            $prevInvoice = $serviceDetailRow['invoice'];
            
            if($invoiceNumber==""){
                throw new Exception("Invoice Number Cannot Be Empty");
            }
            if($cost==""){
                throw new Exception("Cost Cannot Be Empty");
            }
            if($cost<=0){
                throw new Exception("Cost Must Be Higher Than 0 LKR");
            }
            
            
            if($invoice['name']!=""){
                
                if($invoice['type']=="image/jpeg"){
                    
                $extension=".jpg";
                
                }elseif ($invoice['type']=="image/png") {
                    
                $extension=".jpg";
                
                }elseif ($invoice['type']=="application/pdf") {
                    
                $extension=".pdf";
                
                }else{
                    
                throw new Exception("Invoice File Type Not Supported, Please Attach a PDF/PNG/JPEG");
                }
                
                //Adding new invoice
                $fileName= uniqid('svsinv_').$extension;
                $path="../documents/busserviceinvoices/$fileName";
                move_uploaded_file($invoice["tmp_name"],$path);
                
                //remove old invoice
                unlink("../documents/busserviceinvoices/"."$prevInvoice");
            }else{
                
                $fileName = $prevInvoice;
            }
        
            $serviceDetailObj->updatePastService($serviceId, $cost, $fileName,$invoiceNumber);
            
            $msg = "Service Record Updated Successfully";
            $msg = base64_encode($msg);
            
            ?>
            
            <script>
                window.location="../view/service-history.php?msg=<?php echo $msg;?>&success=true";
            </script>
            
            <?php
        }
        catch(Exception $e){
            
            $serviceId = base64_encode($serviceId);
            
            $msg= $e->getMessage();
            $msg= base64_encode($msg);
            ?>
    
            <script>
                window.location="../view/edit-service-record.php?msg=<?php echo $msg;?>&service_id='<?php echo $serviceId;?>'";
            </script>
            <?php
        }
    break;
    
    case "service_cost_trend":
        
        try{
        
            $startMonth =  $_POST['start_month'];
            $endMonth =  $_POST['end_month'];
            
            if($startMonth==""){
                throw new Exception("Start month should be selected");
            }
            if($endMonth==""){
                throw new Exception("End month should be selected");
            }
            
            if($startMonth>$endMonth){
                throw new Exception("End month should be greater than start month");
            }
            
            ?>
            
            <script>
                window.location="../view/service-cost-trend.php?start_month=<?php echo $startMonth;?>&end_month=<?php echo $endMonth;?>";
            </script>
            
            <?php
        
        }
        catch(Exception $e){
            
            $msg= $e->getMessage();
            $msg= base64_encode($msg);
            ?>
    
            <script>
                window.location="../view/service-cost-trend.php?msg=<?php echo $msg;?>";
            </script>
            <?php
            
        }
    
    break;
}
