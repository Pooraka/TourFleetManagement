<?php 

include '../commons/db_connection.php';

$dbcon = new DbConnection();

class Login{

    public function validateUser($login_username, $login_password){

        $con=$GLOBALS["con"];
        $login_password = sha1($login_password);

        $sql = "SELECT u.user_id, u.user_role, r.role_name, u.user_fname, u.user_lname FROM user u, login l, role r 
                WHERE u.user_id=l.user_id AND r.role_id=u.user_role
                AND l.login_username='$login_username' AND l.login_password='$login_password'";
        
        $result = $con->query($sql) or die($con->error);
        return $result;
    }
}