<?php 

include '../commons/db_connection.php';

$dbcon = new DbConnection();

class Login{

    public function validateUser($login_username, $login_password){

        $con=$GLOBALS["con"];
        $login_password = sha1($login_password);

        $sql = "SELECT u.user_id,user_role FROM user u, login l WHERE u.user_id=l.user_id 
            AND l.login_username='$login_username' AND l.login_password='$login_password'";
        
        $result = $con->query($sql) or die($con->error);
        return $result;
    }
}