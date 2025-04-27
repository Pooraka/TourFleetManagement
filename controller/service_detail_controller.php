<?php
include '../commons/session.php';
include_once '../model/bus_model.php';
include_once '../model/service_detail_model.php';

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

            
            $busResult = $busObj->getBus($busId);
            $busRow = $busResult->fetch_assoc();
            
            $startDate = date('Y-m-d');
            $currentMileageAsAt = date('Y-m-d H:i:s', time());
            
            $serviceDetailObj->initiateService($busId, $serviceStationId, $startDate, $currentMileage,$busRow['bus_status']);
            
            $busObj->updateBusMileage($busId, $currentMileage, $currentMileageAsAt);
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
        
        $previousBusStatus = $serviceDetailRow['previous_bus_status'];
        $busId = $serviceDetailRow['bus_id'];
        
        $busObj->changeBusStatus($busId, $previousBusStatus);
        
        $serviceDetailObj->cancelService($serviceId);
        
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
            
            if($cost==""){
                throw new Exception("Cost Cannot Be Empty");
            }
            if($cost<=0){
                throw new Exception("Cost Must Be Higher Than 0 LKR");
            }
            if($invoice['size'] <= 0){
                throw new Exception("Invoice Must Be Attached");
            }
            
            $fileName=time()."_".$invoice["name"];
            $path="../documents/busserviceinvoices/$fileName";
            move_uploaded_file($invoice["tmp_name"],$path);
            
            $serviceDetailObj->completeService($serviceId, $completedDate, $cost, $fileName);
            $busObj->updateServicedBus($busId, $mileageAtService, $completedDate);
            
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
}
