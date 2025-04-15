<?php

include_once '../commons/db_connection.php';

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
    
    public function checkIfEmailExist($email){
        
        $con=$GLOBALS["con"];

        $sql="SELECT user_email FROM user WHERE user_email='$email'";
        
        $result = $con->query($sql) or die($con->error);  
        return $result;
    }
    
    public function checkIfNICExist($nic){
        
        $con=$GLOBALS["con"];

        $sql="SELECT user_nic FROM user WHERE user_nic='$nic'";
        
        $result = $con->query($sql) or die($con->error);  
        return $result;
    }
    
    public function addUser($fname,$lname,$email,$dob,$nic,$user_role,$user_image){

        $con = $GLOBALS["con"];

        $sql = "INSERT INTO user (user_fname, user_lname, user_email, user_dob, user_nic, user_role, user_image) "
                . "VALUES ('$fname','$lname','$email','$dob','$nic','$user_role','$user_image')";
        
        $con->query($sql) or die ($con->error);
        $user_id=$con->insert_id;
        return $user_id;
    }
    
    public function addUserFunctions($user_id,$function_id){

        $con = $GLOBALS["con"];

        $sql = "INSERT INTO function_user (function_id,user_id) VALUES ('$function_id','$user_id')";
        
        $con->query($sql) or die ($con->error);
    }
    
    public function getAllUsers(){
        
        $con = $GLOBALS["con"];
        
        $sql="SELECT * FROM user WHERE user_status !='-1'";
        
        $result = $con->query($sql) or die ($con->error);
        return $result;
    }
    
    public function activateUser($user_id){
        
        $con = $GLOBALS["con"];
        
        $sql="UPDATE user SET user_status='1' WHERE user_id='$user_id'";
        
        $con->query($sql) or die ($con->error);   
    }
    
    public function deactivateUser($user_id){
        
        $con = $GLOBALS["con"];
        
        $sql="UPDATE user SET user_status='0' WHERE user_id='$user_id'";
        
        $con->query($sql) or die ($con->error);   
    }
    
    public function deleteUser($user_id){
        
        $con = $GLOBALS["con"];
        
        $sql="UPDATE user SET user_status='-1' WHERE user_id='$user_id'";
        
        $con->query($sql) or die ($con->error);   
    }
    
    public function getUser($user_id){
        
        $con = $GLOBALS["con"];
        
        $sql="SELECT u.user_id, u.user_fname, u.user_lname, u.user_dob, u.user_nic, u.user_role, u.user_image, u.user_email, u.user_status, "
                . "r.role_name, r.role_status, l.login_username, l.login_status "
                . "FROM user u , role r, login l "
                . "WHERE u.user_role = r.role_id AND u.user_id = l.user_id  AND u.user_id='$user_id'";
        
        $result = $con->query($sql) or die ($con->error);
        return $result;
        
    }
    
    public function getUserFunctions($user_id){
        
        $con = $GLOBALS["con"];
        
        $sql="SELECT * FROM function_user WHERE user_id='$user_id'";
        
        $result = $con->query($sql) or die ($con->error);
        return $result;
        
    }
    
    public function addUserContact($user_id,$number,$type){
        
        $con = $GLOBALS["con"];
        
        $sql="INSERT INTO user_contact (contact_type,contact_number,user_id) VALUES ('$type','$number','$user_id')";
        
        $con->query($sql) or die ($con->error);
    }
    
    public function getUserContact($user_id){
        
        $con = $GLOBALS["con"];
        
        $sql="SELECT * FROM user_contact WHERE user_id='$user_id'";
        
        $result = $con->query($sql) or die ($con->error);
        return $result;
    }
    
    public function updateUser($fname,$lname,$email,$dob,$nic,$user_role,$user_image,$user_id){

        $con = $GLOBALS["con"];

        $sql = "UPDATE user SET user_fname='$fname', user_lname='$lname', user_email='$email', user_dob='$dob', user_nic='$nic',"
                . "user_role='$user_role', user_image='$user_image' WHERE user_id='$user_id'";
        
        $con->query($sql) or die ($con->error);
    }
    
    public function removeUserContact($user_id){
        
        $con=$GLOBALS["con"];
        
        $sql="DELETE FROM user_contact WHERE user_id='$user_id'";
        
        $con->query($sql) or die($con->error);
    }
    
    public function removeUserFunctions($user_id){
        
        $con=$GLOBALS["con"];
        
        $sql="DELETE FROM function_user WHERE user_id='$user_id'";
        
        $con->query($sql) or die($con->error);
    }
}
