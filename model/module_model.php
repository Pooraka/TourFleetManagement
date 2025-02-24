<?php

include_once '../commons/db_connection.php';

$dbCon= new DbConnection();

class Module{

    function getAllModules(){

        $con = $GLOBALS["con"];

        $sql = "SELECT * FROM module";
        $result = $con->query($sql) or die ($con->error);
        return $result;
    }
}