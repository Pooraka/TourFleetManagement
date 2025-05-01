<?php

include_once '../commons/db_connection.php';
include_once '../model/bus_model.php';

$dbCon= new DbConnection();

class BackgroundTask{
    
    public function changeBusStatusToServiceDue(){
        
        $busObj = new Bus();
        $busResult = $busObj->getOperationalBuses();
        
        while($busRow = $busResult->fetch_assoc()){
            
            $busId = $busRow['bus_id'];
            $lastServiceMileage = (int) $busRow['last_service_mileage_km'];
            $lastServiceDate = $busRow['last_service_date'];
            $lastServiceTimestamp = strtotime($lastServiceDate);
            
            $serviceIntervalKM = (int) $busRow['service_interval_km'];
            $serviceIntervalMonths = (int) $busRow['service_interval_months'];
            $serviceDueTimestamp = strtotime("+".$serviceIntervalMonths." months",$lastServiceTimestamp);
            
            $currentMileage = (int) $busRow['current_mileage_km'];
            $todayTimestamp = strtotime('today');
            
            if($currentMileage>=$lastServiceMileage+$serviceIntervalKM){
                
                $busObj->changeBusStatus($busId, 2);
                
            }elseif($todayTimestamp>=$serviceDueTimestamp){
                
                $busObj->changeBusStatus($busId, 2);  
            }
        }
    }
    
}