<?php

include_once '../commons/db_connection.php';

$dbCon= new DbConnection();

class ServiceStation{
    
    public function addServiceStation($stationName,$address){
        
        $con = $GLOBALS["con"];
        
        $sql = "INSERT INTO service_station(service_station_name,address) VALUES (?,?)";
    
        $stmt = $con->prepare($sql);

        $stmt->bind_param("ss", $stationName, $address); 

        $stmt->execute();

        $serviceStationId=$con->insert_id;

        $stmt->close();

        return $serviceStationId;
    }
    
    public function addServiceStationContact($serviceStationId,$number,$type){
        
        $con = $GLOBALS["con"];
        
        $sql = "INSERT INTO service_station_contact(service_station_contact_number,contact_type,service_station_id) "
            . "VALUES (?,?,?)";
    
        $stmt = $con->prepare($sql);

        $stmt->bind_param("sii", $number, $type, $serviceStationId); 

        $stmt->execute();

        $stmt->close();
    }
    
    public function getServiceStations(){
        
        $con = $GLOBALS["con"];

        $sql = "SELECT * FROM service_station WHERE service_station_status != '-1'";
        
        $result = $con->query($sql) or die ($con->error);
        return $result;
    }
    
    public function getServiceStationContact($serviceStationId){
        
        $con = $GLOBALS["con"];
        
        $sql = "SELECT * FROM service_station_contact WHERE service_station_id= ?";
    
        $stmt = $con->prepare($sql);

        $stmt->bind_param("i", $serviceStationId); 

        $stmt->execute();

        $result = $stmt->get_result();

        $stmt->close();

        return $result;
    }
    
    public function removeServiceStation($serviceStationId){
        
        $con = $GLOBALS["con"];
        
        $sql = "UPDATE service_station SET service_station_status='-1' WHERE service_station_id= ?";
    
        $stmt = $con->prepare($sql);

        $stmt->bind_param("i", $serviceStationId); 

        $stmt->execute();

        $stmt->close();
    }
    
    public function getServiceStation($serviceStationId){
        
        $con = $GLOBALS["con"];
        
        $sql = "SELECT * FROM service_station WHERE service_station_id =?";
    
        $stmt = $con->prepare($sql);

        $stmt->bind_param("i", $serviceStationId); 

        $stmt->execute();

        $result = $stmt->get_result();

        $stmt->close();

        return $result;
    }
    
    public function updateServiceStation($serviceStationId,$stationName,$address){
        
        $con = $GLOBALS["con"];

        $sql = "UPDATE service_station SET service_station_name=?, address=? WHERE service_station_id= ?";
    
        $stmt = $con->prepare($sql);

        $stmt->bind_param("ssi", $stationName, $address, $serviceStationId); 

        $stmt->execute();

        $stmt->close();
    }
    
    public function removeServiceStationContact($serviceStationId){
        
        $con = $GLOBALS["con"];

        $sql = "DELETE FROM service_station_contact WHERE service_station_id= ?";
    
        $stmt = $con->prepare($sql);

        $stmt->bind_param("i", $serviceStationId); 

        $stmt->execute();

        $stmt->close();
    }
    
    
    public function getAllServiceStationsIncludingRemoved(){
        
        $con = $GLOBALS["con"];

        $sql = "SELECT * FROM service_station ORDER BY service_station_name ASC";
        
        $result = $con->query($sql) or die ($con->error);
        return $result;
    }
}