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
}