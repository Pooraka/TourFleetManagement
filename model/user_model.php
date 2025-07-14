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

        $sql = "SELECT * FROM role_module r, module m WHERE r.module_id = m.module_id AND r.role_id =? ORDER BY module_order ASC";
        
        $stmt = $con->prepare($sql);
        
        $stmt->bind_param("i",$roleId);
        
        $stmt->execute();
        
        $result = $stmt->get_result();
        
        $stmt->close();
        
        return $result;
    }
 
    public function getModuleFunctions($moduleId){

        $con = $GLOBALS["con"];

        $sql = "SELECT * FROM function WHERE module_id=? ";
    
        $stmt = $con->prepare($sql);

        $stmt->bind_param("i", $moduleId);

        $stmt->execute();

        $result = $stmt->get_result();

        $stmt->close();

        return $result;
    }
    
    public function checkIfEmailExist($email){
        
        $con=$GLOBALS["con"];

        $sql="SELECT user_email FROM user WHERE user_email=?";
    
        $stmt = $con->prepare($sql);

        $stmt->bind_param("s", $email);

        $stmt->execute();

        $result = $stmt->get_result();

        $stmt->close();

        return $result;
    }
    
    public function checkIfNICExist($nic){
        
        $con=$GLOBALS["con"];

        $sql="SELECT user_nic FROM user WHERE user_nic=?";
    
        $stmt = $con->prepare($sql);

        $stmt->bind_param("s", $nic);

        $stmt->execute();

        $result = $stmt->get_result();

        $stmt->close();

        return $result;
    }
    
    public function addUser($fname,$lname,$email,$dob,$nic,$user_role,$user_image){

        $con = $GLOBALS["con"];
        
        $sql = "INSERT INTO user (user_fname, user_lname, user_email, user_dob, user_nic, user_role, user_image) "
            . "VALUES (?,?,?,?,?,?,?)";
    
        $stmt = $con->prepare($sql);


        $stmt->bind_param("sssssis", $fname, $lname, $email, $dob, $nic, $user_role, $user_image); 

        $stmt->execute();

        $user_id=$con->insert_id;

        $stmt->close();

        return $user_id;
    }
    
    public function addUserFunctions($user_id,$function_id){

        $con = $GLOBALS["con"];

        $sql = "INSERT INTO function_user (function_id,user_id) VALUES (?,?)";
    
        $stmt = $con->prepare($sql);

        $stmt->bind_param("ii", $function_id, $user_id); 

        $stmt->execute();

        $stmt->close();
    }
    
    public function getAllUsers(){
        
        $con = $GLOBALS["con"];
        
        $sql="SELECT u.user_id, u.user_fname, u.user_lname, u.user_dob, u.user_nic, u.user_role, "
                . "u.user_image, u.user_email, u.user_status, r.role_name "
                . "FROM user u, role r WHERE user_status !='-1' AND u.user_role=r.role_id";
        
        $result = $con->query($sql) or die ($con->error);
        return $result;
    }
    
    public function activateUser($user_id){
        
        $con = $GLOBALS["con"];
        
        $sql="UPDATE user SET user_status='1' WHERE user_id=?";
    
        $stmt = $con->prepare($sql);

        $stmt->bind_param("i", $user_id);

        $stmt->execute();

        $stmt->close();   
    }
    
    public function deactivateUser($user_id){
        
        $con = $GLOBALS["con"];

        $sql="UPDATE user SET user_status='0' WHERE user_id=?";
    
        $stmt = $con->prepare($sql);

        $stmt->bind_param("i", $user_id); // user_id is INT(10)

        $stmt->execute();

        $stmt->close();  
    }
    
    public function deleteUser($user_id){
        
        $con = $GLOBALS["con"];

        $sql="UPDATE user SET user_status='-1' WHERE user_id=?";
    
        $stmt = $con->prepare($sql);

        $stmt->bind_param("i", $user_id);

        $stmt->execute();

        $stmt->close();  
    }
    
    public function getUser($user_id){
        
        $con = $GLOBALS["con"];
        
        $sql="SELECT u.user_id, u.user_fname, u.user_lname, u.user_dob, u.user_nic, u.user_role, u.user_image, u.user_email, u.user_status, "
                . "r.role_name, r.role_status, l.login_username, l.login_status "
                . "FROM user u , role r, login l "
                . "WHERE u.user_role = r.role_id AND u.user_id = l.user_id  AND u.user_id=?";
        
        $stmt = $con->prepare($sql);
    
        $stmt->bind_param("i", $user_id);

        $stmt->execute();

        $result = $stmt->get_result();

        $stmt->close();

        return $result;
        
    }
    
    public function getUserFunctions($user_id){
        
        $con = $GLOBALS["con"];
        
        $sql="SELECT * FROM function_user WHERE user_id=?";
        
        $stmt = $con->prepare($sql);
    
        $stmt->bind_param("i", $user_id);

        $stmt->execute();

        $result = $stmt->get_result();

        $stmt->close();

        return $result;
        
    }
    
    public function addUserContact($user_id,$number,$type){
        
        $con = $GLOBALS["con"];

        $sql="INSERT INTO user_contact (contact_type,contact_number,user_id) VALUES (?,?,?)";
    
        $stmt = $con->prepare($sql);

        $stmt->bind_param("isi", $type, $number, $user_id); 

        $stmt->execute();

        $stmt->close();
    }
    
    public function getUserContact($user_id){
        
        $con = $GLOBALS["con"];
        
        $sql="SELECT * FROM user_contact WHERE user_id=?";
        
        $stmt = $con->prepare($sql);
    
        $stmt->bind_param("i", $user_id);

        $stmt->execute();

        $result = $stmt->get_result();

        $stmt->close();

        return $result;
    }
    
    public function updateUser($fname,$lname,$email,$dob,$nic,$user_role,$user_image,$user_id){

        $con = $GLOBALS["con"];

        $sql = "UPDATE user SET user_fname=?, user_lname=?, user_email=?, user_dob=?, user_nic=?,"
            . "user_role=?, user_image=? WHERE user_id=?";
    
        $stmt = $con->prepare($sql);

        $stmt->bind_param("sssssisi", $fname, $lname, $email, $dob, $nic, $user_role, $user_image, $user_id); 

        $stmt->execute();

        $stmt->close();
    }
    
    public function removeUserContact($user_id){
        
        $con=$GLOBALS["con"];
        
        $sql="DELETE FROM user_contact WHERE user_id=?";
        
        $stmt = $con->prepare($sql);
    
        $stmt->bind_param("i", $user_id); 

        $stmt->execute();

        $stmt->close();
    }
    
    public function removeUserFunctions($user_id){
        
        $con=$GLOBALS["con"];
        
        $sql="DELETE FROM function_user WHERE user_id=?";
        
        $stmt = $con->prepare($sql);
    
        $stmt->bind_param("i", $user_id);

        $stmt->execute();

        $stmt->close();
    }
    
    public function getUsersToSendBusServiceDueEmail(){
        
        $con=$GLOBALS["con"];
        
        $sql="SELECT user_fname, user_lname, user_email FROM user WHERE user_role IN ('1','3','4','5','6') AND user_status='1'";
        
        $result = $con->query($sql) or die($con->error);
        return $result;
    }
    
    public function getUsersToSendPendingInspections(){
        
        $con=$GLOBALS["con"];
        
        $sql="SELECT user_fname, user_lname, user_email FROM user WHERE user_role IN ('1','3','4','5','6') AND user_status='1'";
        
        $result = $con->query($sql) or die($con->error);
        return $result;
    }
    
    public function getUsersToSendSparePartListBelowReorderLevel(){
        
        $con=$GLOBALS["con"];
        
        $sql="SELECT user_fname, user_lname, user_email FROM user WHERE user_role IN (1,7,8) AND user_status='1'";
        
        $result = $con->query($sql) or die($con->error);
        return $result;
    }
    
    public function getActiveUserCount(){
        
        $con=$GLOBALS["con"];
        
        $sql = "SELECT * FROM user WHERE user_status=1";
        
        $result = $con->query($sql) or die($con->error);
        
        $count = $result->num_rows;
        
        return $count;
    }
    
    public function getDeactivatedUserCount(){
        
        $con=$GLOBALS["con"];
        
        $sql = "SELECT * FROM user WHERE user_status=0";
        
        $result = $con->query($sql) or die($con->error);
        
        $count = $result->num_rows;
        
        return $count;
    }
}
