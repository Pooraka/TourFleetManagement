<?php

include_once '../commons/db_connection.php';

$dbCon= new DbConnection();

class ServiceDetail{
    
    public function initiateService($busId, $serviceStationId,$startDate,$currentMileage){
        
        $con = $GLOBALS["con"];
        
        $sql ="INSERT INTO service_detail (bus_id,service_station_id,start_date,mileage_at_service) "
                . "VALUES ('$busId','$serviceStationId','$startDate','$currentMileage')";
        
        $con->query($sql) or die ($con->error);
        $serviceId=$con->insert_id;
        return $serviceId;
    }
    
    public function getOngoingServices(){
        
        $con = $GLOBALS["con"];
        
        $sql = "SELECT * FROM service_detail WHERE service_status='1'";
        
        $result = $con->query($sql) or die ($con->error);
        return $result;
    }
}