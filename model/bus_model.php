<?php

include_once '../commons/db_connection.php';

$dbCon= new DbConnection();

class Bus{
    
    public function getAllBuses(){
        
        $con = $GLOBALS["con"];

        $sql = "SELECT b.bus_id, b.category_id, b.vehicle_no, b.make, b.model, b.year, b.capacity, "
                . "b.ac_available, b.service_interval_km, b.current_mileage_km, b.current_mileage_as_at, b.last_service_milage_km, "
                . "b.service_interval_months, b.last_service_date, b.bus_status, c.category_name  "
                . "FROM bus b, bus_category c WHERE b.category_id = c.category_id";
        
        $result = $con->query($sql) or die ($con->error);
        return $result;
    }
    
    public function getAllBusCategories(){
        
        $con = $GLOBALS["con"];
        
        $sql="SELECT * FROM bus_category";
        
        $result = $con->query($sql) or die ($con->error);
        return $result;
    }
    
    public function checkIfBusIsAlreadyExist($vehicleNo){
        
        $con = $GLOBALS["con"];
        
        $sql="SELECT * FROM bus WHERE vehicle_no = '$vehicleNo'";
        
        $result = $con->query($sql) or die ($con->error);
        return $result;
    }
    
    public function addBus($category, $vehicleNo, $make, $model, $year, $capacity, $ac, $serviceIntervalKM, $lastServiceKM, $serviceIntervalMonths, $lastServiceDate){
        
        $con = $GLOBALS["con"];
        
        $sql = "INSERT INTO bus (category_id, vehicle_no, make, model, year, capacity, ac_available, service_interval_km, last_service_mileage_km, service_interval_months, last_Service_date)"
                . " VALUES ('$category','$vehicleNo','$make','$model','$year','$capacity','$ac','$serviceIntervalKM','$lastServiceKM','$serviceIntervalMonths','$lastServiceDate')";
        
        $con->query($sql) or die ($con->error);
        $busId=$con->insert_id;
        return $busId;
    }
    
    public function addBusMileage($busId, $currentMileage, $currentMileageAsAt){
        
        $con = $GLOBALS["con"];
        
        $sql = "UPDATE bus SET current_mileage_km = '$currentMileage', current_mileage_as_at = '$currentMileageAsAt' WHERE bus_id ='$busId'";
        
        $con->query($sql) or die ($con->error);
    }
}