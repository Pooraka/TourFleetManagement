<?php

include_once '../commons/db_connection.php';

$dbCon= new DbConnection();

class ServiceStation{
    
    public function addServiceStation($stationName,$address){
        
        $con = $GLOBALS["con"];

        $sql = "INSERT INTO service_station(service_station_name,address) VALUES ('$stationName','$address')";
        
        $con->query($sql) or die ($con->error);
        $serviceStationId=$con->insert_id;
        return $serviceStationId;
    }
    
    public function addServiceStationContact($serviceStationId,$number,$type){
        
        $con = $GLOBALS["con"];

        $sql = "INSERT INTO service_station_contact(service_station_contact_number,contact_type,service_station_id) "
                . "VALUES ('$number','$type','$serviceStationId')";
        
        $con->query($sql) or die ($con->error);
    }
    
    public function getServiceStations(){
        
        $con = $GLOBALS["con"];

        $sql = "SELECT * FROM service_station WHERE service_station_status != '-1'";
        
        $result = $con->query($sql) or die ($con->error);
        return $result;
    }
    
    public function getServiceStationContact($serviceStationId){
        
        $con = $GLOBALS["con"];

        $sql = "SELECT * FROM service_station_contact WHERE service_station_id= '$serviceStationId'";
        
        $result = $con->query($sql) or die ($con->error);
        return $result;
    }
    
    public function removeServiceStation($serviceStationId){
        
        $con = $GLOBALS["con"];

        $sql = "UPDATE service_station SET service_station_status='-1' WHERE service_station_id= '$serviceStationId'";
        
        $con->query($sql) or die ($con->error);
    }
    
    public function getServiceStation($serviceStationId){
        
        $con = $GLOBALS["con"];

        $sql = "SELECT * FROM service_station WHERE service_station_id ='$serviceStationId'";
        
        $result = $con->query($sql) or die ($con->error);
        return $result;
    }
    
    public function updateServiceStation($serviceStationId,$stationName,$address){
        
        $con = $GLOBALS["con"];

        $sql = "UPDATE service_station SET service_station_name='$stationName', address='$address' WHERE service_station_id= '$serviceStationId'";
        
        $con->query($sql) or die ($con->error);
    }
    
    public function removeServiceStationContact($serviceStationId){
        
        $con = $GLOBALS["con"];

        $sql = "DELETE FROM service_station_contact WHERE service_station_id= '$serviceStationId'";
        
        $con->query($sql) or die ($con->error);
    }
}