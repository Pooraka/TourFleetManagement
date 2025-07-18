<?php

include_once '../commons/db_connection.php';

$dbCon= new DbConnection();

class Module{

    function getAllModules($role_id){

        $con = $GLOBALS["con"];
        
        $sql = "SELECT m.module_id, m.module_name, m.module_icon, m.module_url, m.module_status "
            . "FROM module m, role_module r WHERE m.module_id = r.module_id AND r.role_id= ?";
    
        $stmt = $con->prepare($sql);

        $stmt->bind_param("i", $role_id);

        $stmt->execute();

        $result = $stmt->get_result();

        $stmt->close();

        return $result;
    }
}