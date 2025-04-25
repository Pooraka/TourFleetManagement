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
            
            $currentMileageAsAt = date('Y-m-d H:i:s', time());
            
            $busObj->updateBusMileage($busId, $currentMileage, $currentMileageAsAt);
            $busObj->changeBusStatus($busId,3);
            
            $startDate = date('Y-m-d');
            
            $serviceDetailObj->initiateService($busId, $serviceStationId, $startDate, $currentMileage);
            
            $busResult = $busObj->getBus($busId);
            $busRow = $busResult->fetch_assoc();
            
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
}
