<?php

include '../commons/db_connection.php';

$dbCon= new DbConnection();

class User{

    public function getAllRoles(){

        $con = $GLOBALS["con"];

        $sql = "SELECT * FROM role";
        $result = $con->query($sql) or die ($con->error);
        return $result;
    }
    
    public function getRoleModules($roleId){

        $con = $GLOBALS["con"];

        $sql = "SELECT * FROM role_module r, module m WHERE r.module_id = m.module_id AND r.role_id ='$roleId' ";
        $result = $con->query($sql) or die ($con->error);
        return $result;
    }
    
    public function getModuleFunctions($moduleId){

        $con = $GLOBALS["con"];

        $sql = "SELECT * FROM function WHERE module_id='$moduleId' ";
        $result = $con->query($sql) or die ($con->error);
        return $result;
    }
    
    public function addUser($fname,$lname,$email,$dob,$nic,$user_role,$user_image){

        $con = $GLOBALS["con"];

        $sql = "INSERT INTO user (user_fname, user_lname, user_email, user_dob, user_nic, user_role, user_image) VALUES ('$fname','$lname','$email','$dob','$nic','$user_role','$user_image')";
        $con->query($sql) or die ($con->error);
        $user_id=$con->insert_id;
        return $user_id;
    }
    
}
