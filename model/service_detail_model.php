<?php

include_once '../commons/db_connection.php';

$dbCon= new DbConnection();

class ServiceDetail{
    
    public function initiateService($busId, $serviceStationId,$startDate,$currentMileage,$previousBusStatus){
        
        $con = $GLOBALS["con"];
        
        $sql ="INSERT INTO service_detail (bus_id,service_station_id,start_date,mileage_at_service,previous_bus_status) "
                . "VALUES ('$busId','$serviceStationId','$startDate','$currentMileage','$previousBusStatus')";
        
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
    
    public function cancelService($serviceId){
        
        $con = $GLOBALS["con"];
        
        $sql = "UPDATE service_detail SET service_status ='-1' WHERE service_id ='$serviceId'";
        
        $con->query($sql) or die ($con->error);
    }
    
    public function getServiceDetail($serviceId){
        
        $con = $GLOBALS["con"];
        
        $sql = "SELECT * FROM service_detail WHERE service_id='$serviceId'";
        
        $result = $con->query($sql) or die ($con->error);
        return $result;
    }
    
    public function completeService($serviceId,$completedDate,$cost,$invoice){
        
        $con = $GLOBALS["con"];
        
        $sql = "UPDATE service_detail SET service_status ='2', completed_date='$completedDate', "
                . "cost='$cost', invoice='$invoice' WHERE service_id ='$serviceId'";
        
        $con->query($sql) or die ($con->error);
    }
}