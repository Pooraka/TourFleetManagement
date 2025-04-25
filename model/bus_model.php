<?php

include_once '../commons/db_connection.php';

$dbCon= new DbConnection();

class Bus{
    
    public function getAllBuses(){
        
        $con = $GLOBALS["con"];

        $sql = "SELECT b.bus_id, b.category_id, b.vehicle_no, b.make, b.model, b.year, b.capacity, "
                . "b.ac_available, b.service_interval_km, b.current_mileage_km, b.current_mileage_as_at, b.last_service_mileage_km, "
                . "b.service_interval_months, b.last_service_date, b.bus_status, c.category_name  "
                . "FROM bus b, bus_category c WHERE b.category_id = c.category_id AND b.bus_status != '-1'";
        
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
    
    public function updateBusMileage($busId, $currentMileage, $currentMileageAsAt){
        
        $con = $GLOBALS["con"];
        
        $sql = "UPDATE bus SET current_mileage_km = '$currentMileage', current_mileage_as_at = '$currentMileageAsAt' WHERE bus_id ='$busId'";
        
        $con->query($sql) or die ($con->error);
    }
    
    public function removeBus($busId){
        
        $con = $GLOBALS["con"];
        
        $sql = "UPDATE bus SET bus_status = '-1' WHERE bus_id = '$busId'";
        
        $con->query($sql) or die ($con->error);
    }
    
    public function getBus($busId){
        
        $con = $GLOBALS["con"];
        
        $sql = "SELECT b.bus_id, b.category_id, b.vehicle_no, b.make, b.model, b.year, b.capacity, "
                . "b.ac_available, b.service_interval_km, b.current_mileage_km, b.current_mileage_as_at, b.last_service_mileage_km, "
                . "b.service_interval_months, b.last_service_date, b.bus_status, c.category_name  "
                . "FROM bus b, bus_category c WHERE b.category_id = c.category_id AND b.bus_id = '$busId'";
        
        $result = $con->query($sql) or die ($con->error);
        return $result;
    }
    
    public function updateBus($busId, $category, $vehicleNo, $make, $model, $year, $capacity, $ac, $serviceIntervalKM, $lastServiceKM, $serviceIntervalMonths, $lastServiceDate){
        
        $con = $GLOBALS["con"];
        
        $sql = "UPDATE bus SET category_id='$category', vehicle_no='$vehicleNo', make='$make', model='$model', year='$year', capacity='$capacity' "
                . ", ac_available='$ac', service_interval_km='$serviceIntervalKM', last_service_mileage_km='$lastServiceKM', service_interval_months='$serviceIntervalMonths' "
                . ", last_service_date='$lastServiceDate' WHERE bus_id ='$busId' ";
        
        $con->query($sql) or die ($con->error);
    }
    
    public function getAllBusesToService(){
        
        $con = $GLOBALS["con"];

        $sql = "SELECT b.bus_id, b.category_id, b.vehicle_no, b.make, b.model, b.year, b.capacity, "
                . "b.ac_available, b.service_interval_km, b.current_mileage_km, b.current_mileage_as_at, b.last_service_mileage_km, "
                . "b.service_interval_months, b.last_service_date, b.bus_status, c.category_name  "
                . "FROM bus b, bus_category c WHERE b.category_id = c.category_id "
                . "AND b.bus_status != '-1' AND b.bus_status != '3' ORDER BY b.vehicle_no ASC";
        
        $result = $con->query($sql) or die ($con->error);
        return $result;
    }
    
    public function changeBusStatus($busId, $status){
        
        $con = $GLOBALS["con"];
        
        $sql = "UPDATE bus SET bus_status='$status' WHERE bus_id='$busId'";
        
        $con->query($sql) or die ($con->error);
    }
}