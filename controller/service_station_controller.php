<?php
include_once '../commons/session.php';
include_once '../model/service_station_model.php';

$serviceStationObj = new ServiceStation();

if(!isset($_GET["status"])){
    ?>
    <script>
        window.location= "../view/login.php";
    </script>
    <?php
}

$status= $_GET["status"];

switch ($status){
    
    case "add_service_station":
        
        try{
        
            $stationName = ucwords(strtolower($_POST["stationname"]));
            $address = ucwords(strtolower($_POST["address"]));
            $mobile = $_POST["mobile"];
            $landline = $_POST["landline"];
            
            if($stationName==""){
                throw new Exception("Station Name Cannot Be Empty!");
            }
            if($address==""){
                throw new Exception("Address Cannot Be Empty!");
            }
            if($mobile==""){
                throw new Exception("Mobile Number Cannot Be Empty!");
            }
            
            $mobilePattern ="/^07[0-9]{8}$/";
            
            if(!preg_match($mobilePattern, $mobile)){
                throw new Exception("Invalid Mobile Number");
            }
            
            if($landline==""){
                throw new Exception("Landline Cannot Be Empty!");
            }
            
            $landlinePattern = "/^0[0-9]{9}$/";
            
            if(!preg_match($landlinePattern, $landline)){
                throw new Exception("Invalid Landline");
            }
            
            $serviceStationId = $serviceStationObj->addServiceStation($stationName, $address);
            
            if ($mobile != "") {
                $serviceStationObj->addServiceStationContact($serviceStationId, $mobile, 1);
            }
            if ($landline != "") {
                $serviceStationObj->addServiceStationContact($serviceStationId, $landline, 2);
            }
            
            $msg = "Service Station $stationName Added Successfully";
            $msg = base64_encode($msg);
            ?>
                                
                <script>
                    window.location="../view/add-service-station.php?msg=<?php echo $msg; ?>&success=true";
                </script>
                            
            <?php
        }
        catch(Exception $e){
            
            $msg= $e->getMessage();
            $msg= base64_encode($msg);
            ?>
    
            <script>
                window.location="../view/add-service-station.php?msg=<?php echo $msg;?>";
            </script>
            <?php
            
        }
        
    break;
    
    case "remove_station":
        
        $serviceStationId = $_GET["station_id"];
        $serviceStationId = base64_decode($serviceStationId);

        $serviceStationObj->removeServiceStation($serviceStationId);

        $msg = "Service Station Removed Successfully";
        $msg = base64_encode($msg);
        ?>
            <script>
                window.location="../view/view-service-stations.php?msg=<?php echo $msg; ?>&success=true";
            </script>
        <?php
        
    break;   
        
    case "update_service_station":
        
        try{
            
            $serviceStationId = $_POST["service_station_id"];
            $stationName = ucwords(strtolower($_POST["stationname"]));
            $address = ucwords(strtolower($_POST["address"]));
            $mobile = $_POST["mobile"];
            $landline = $_POST["landline"];
            
            if($stationName==""){
                throw new Exception("Station Name Cannot Be Empty!");
            }
            if($address==""){
                throw new Exception("Address Cannot Be Empty!");
            }
            if($mobile==""){
                throw new Exception("Mobile Number Cannot Be Empty!");
            }
            
            $mobilePattern ="/^07[0-9]{8}$/";
            
            if(!preg_match($mobilePattern, $mobile)){
                throw new Exception("Invalid Mobile Number");
            }
            
            if($landline==""){
                throw new Exception("Landline Cannot Be Empty!");
            }
            
            $landlinePattern = "/^0[0-9]{9}$/";
            
            if(!preg_match($landlinePattern, $landline)){
                throw new Exception("Invalid Landline");
            }
            
            $serviceStationObj->updateServiceStation($serviceStationId, $stationName, $address);
            
            //remove existing numbers
            $serviceStationObj->removeServiceStationContact($serviceStationId);
            
            $serviceStationObj->addServiceStationContact($serviceStationId, $mobile, 1);
            $serviceStationObj->addServiceStationContact($serviceStationId, $landline, 2);
            
            $msg = "Service Station $stationName Updated Successfully";
            $msg = base64_encode($msg);
            
            ?>
            
            <script>
                window.location="../view/view-service-stations.php?msg=<?php echo $msg;?>&success=true";
            </script>
            
            <?php
        
        }
        catch(Exception $e){
            
            $msg= $e->getMessage();
            $msg= base64_encode($msg);
            ?>
    
            <script>
                window.location="../view/edit-service-station.php?msg=<?php echo $msg;?>";
            </script>
            <?php
            
        }
}
