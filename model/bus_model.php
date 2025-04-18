<?php

include_once '../commons/db_connection.php';

$dbCon= new DbConnection();

class Bus{
    
    public function getAllBuses(){
        
        $con = $GLOBALS["con"];

        $sql = "SELECT * FROM bus";
        
        $result = $con->query($sql) or die ($con->error);
        return $result;
    }
}