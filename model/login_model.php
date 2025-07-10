<?php 

include_once '../commons/db_connection.php';

$dbcon = new DbConnection();

class Login{

    public function validateUser($login_username, $login_password){

        $con=$GLOBALS["con"];
        $login_password = sha1($login_password);
        
        $sql = "SELECT u.user_id, u.user_role, r.role_name, u.user_fname, u.user_lname, u.user_status, l.login_status, l.otp "
            . "FROM user u, login l, role r WHERE u.user_id=l.user_id AND r.role_id=u.user_role AND "
            . "l.login_username=? AND l.login_password=?";
        
        $stmt = $con->prepare($sql);

        $stmt->bind_param("ss", $login_username, $login_password);

        $stmt->execute();

        $result = $stmt->get_result();

        $stmt->close();

        return $result;
    }
    
    public function checkIfUsernameExist($user_name){
        
        $con=$GLOBALS["con"];
        
        $sql="SELECT login_username, user_id FROM login WHERE login_username=?";
    
        $stmt = $con->prepare($sql);

        $stmt->bind_param("s", $user_name);

        $stmt->execute();

        $result = $stmt->get_result();

        $stmt->close();

        return $result;
    }
    
    public function addUserLogin($user_id, $user_name, $nic){
        
        $con=$GLOBALS["con"];
        $pwd = sha1($nic);
        
        $sql="INSERT INTO login (login_username,login_password,user_id) VALUES (?,?,?)";
    
        $stmt = $con->prepare($sql);

        $stmt->bind_param("ssi", $user_name, $pwd, $user_id); 

        $stmt->execute();

        $stmt->close(); 

    }
    
    public function addOTP($otp,$expiry,$user_id){
        
        $con=$GLOBALS["con"];
        
        $sql="UPDATE login SET otp=?, otp_expiry=? WHERE user_id=?";
    
        $stmt = $con->prepare($sql);

        $stmt->bind_param("ssi", $otp, $expiry, $user_id); 

        $stmt->execute();

        $stmt->close(); 
    }
    
    public function getOTP($user_id){
        
        $con=$GLOBALS["con"];

        $sql="SELECT otp, otp_expiry FROM login WHERE user_id=?";
    
        $stmt = $con->prepare($sql);

        $stmt->bind_param("i", $user_id);

        $stmt->execute();

        $result = $stmt->get_result();

        $stmt->close();

        return $result;
    }
    
    public function removeOTP($user_id) {
        
        $con=$GLOBALS["con"];
        
        $sql="UPDATE login SET otp='', otp_expiry='', login_status='1' WHERE user_id=?";
        
        $stmt = $con->prepare($sql);
        
        $stmt->bind_param("i",$user_id);
        
        $stmt->execute();
        
        $stmt->close();  
    }
    
    public function updatePassword($password, $user_id){
        
        $con=$GLOBALS["con"];
        
        $password = sha1($password);

        $sql="UPDATE login SET login_password=? WHERE user_id=?";
    
        $stmt = $con->prepare($sql);

        $stmt->bind_param("si", $password, $user_id); 

        $stmt->execute();

        $stmt->close();
    }
    
    public function changeLoginStatusToOtpSent($user_id){
        
        $con=$GLOBALS["con"];

        $sql="UPDATE login SET login_status='2' WHERE user_id=?";
    
        $stmt = $con->prepare($sql);

        $stmt->bind_param("i", $user_id); 

        $stmt->execute();

        $stmt->close();
    }
}