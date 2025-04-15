<?php
require_once '../vendor/autoload.php';
include_once '../commons/session.php';
include_once '../model/login_model.php';
include_once '../model/user_model.php';
include_once '../services/mailer.php';

$loginObj = new Login();
$userObj = new User();

if(!isset($_GET["status"])){
    ?>
    <script>
        window.location= "../view/login.php";
    </script>
    <?php
}

$status= $_GET["status"];

switch ($status) {
    
    case "login":
    
        $login_username = strtolower(trim($_POST["loginusername"]));
        $login_password = $_POST["loginpassword"];

        try {

            if ($login_username == "") {
                throw new Exception("Username cannot be empty !");
            }
            if ($login_password == "") {
                throw new Exception("Password cannot be empty !");
            }

            $loginResult = $loginObj->validateUser($login_username, $login_password);

            //if matching records found
            if ($loginResult->num_rows > 0) {

                //converting $loginResult to an array
                $userSession = $loginResult->fetch_assoc();

                if ($userSession['user_status'] == -1) {
                    throw new Exception("User is deleted");
                }
                if ($userSession['user_status'] == 0) {
                    throw new Exception("User is deactivated");
                }

                //assign $userSessionRow to a session
                $_SESSION["user"] = $userSession;
                ?>
                    <script>
                        window.location = "../view/dashboard.php";
                    </script>

                <?php
            } 
            else {
                throw new Exception("Invalid Credentials");
            }
            
        } 
        catch (Exception $e) {
            
            $msg = $e->getMessage();
            $msg = base64_encode($msg);
            ?>
                <script>
                    window.location ="../view/login.php?msg=<?php echo $msg ?>";
                </script>
            <?php
        }
        
    break;

    case "logout":
        
        session_destroy();
        ?>
            <script>
                window.location="../view/login.php";
            </script>
        <?php
    break;

    case "forgot-password";
    
        $login_username = $_POST["loginusername"];
        
        try{
            
            if($login_username==""){
                
                throw new Exception("Username cannot be empty");
            }
            
            $loginResult = $loginObj->checkIfUsernameExist($login_username);
            
            //if matching records found
            if($loginResult->num_rows==1){
                
                $loginRow = $loginResult->fetch_assoc();
                $user_id = $loginRow['user_id'];
                
                $userResult = $userObj->getUser($user_id);
                $userRow = $userResult->fetch_assoc();
                
                if($userRow['user_status']==0){
                    
                    throw new Exception("User is deactivated");
                }
                
                if($userRow['user_status']==-1){
                    
                    throw new Exception("User is deleted");
                }
                
                $email = $userRow['user_email'];
                $name = $userRow['user_fname']." ".$userRow['user_lname'];
                
                $otpNumber = random_int(1, 999999);
                $otp = str_pad($otpNumber,6,'0',STR_PAD_LEFT);
                
                $expiryTime = date('Y-m-d H:i:s', time() + 300);
                
                $loginObj->addOTP($otp,$expiryTime,$user_id);
                
                $mailObj = new Mailer ();
                $emailSent = $mailObj->sendOtpMail($email,$otp,$name);
                
                if($emailSent){
                    
                    $_SESSION['otp_requested_user_id'] = $user_id;
                    
                    $msg="OTP Sent to your email";
                    $msg = base64_encode($msg);
                    
                    ?>
                        <script>
                            window.location ="../view/otp-verification.php?msg=<?php echo $msg; ?>&success=true";
                        </script>
                    <?php
                    
                    exit();
                    
                }
                else{
                    throw new Exception ("OTP Sending Failed");
                }
            }
            else{
                
                throw new Exception ("User name does not exist");
            }
            
        } 
        catch (Exception $e) {
            
            $msg = $e->getMessage();
            $msg = base64_encode($msg);
            
            ?>
                <script>
                    window.location ="../view/forgot-password.php?msg=<?php echo $msg?>";
                </script>
            <?php
        }
        
    break;
    
    case "verify-otp":
        
        try{
        
            $User_entered_otp = $_POST["otp"];

            if ($User_entered_otp == "") {

                throw new Exception("OTP Cannot Be Empty");
            }
            
            if (!isset($_SESSION['otp_requested_user_id'])) {

                throw new Exception("OTP Expired, Please Request a New OTP");
            }
            
            $user_id = $_SESSION['otp_requested_user_id'];
            
            $loginResult = $loginObj->getOTP($user_id);
            $loginRow = $loginResult->fetch_assoc();
            
            if ($loginRow['otp'] == null) {
                
                throw new Exception("OTP Expired, Please Request a New OTP");
            }
            
            $loginOtp = $loginRow['otp'];
            
            $otpExpiryString = $loginRow['otp_expiry'];

            $expiryDateTime = new DateTime($otpExpiryString);
            $currentDateTime = new DateTime();
            
            if ($currentDateTime > $expiryDateTime) {

                throw new Exception("OTP Expired, Please Request Another OTP");
            }
            
            if ($User_entered_otp != $loginOtp) {

                throw new Exception("Invalid OTP");
            }
            
            $loginObj->removeOTP($user_id);
            $_SESSION['otp_verified'] = true;
            $_SESSION['user_id_for_reset'] = $user_id;
            unset($_SESSION['otp_requested_user_id']);
            session_regenerate_id(true);
            
            ?>
                <script>
                    window.location ="../view/reset-forgotten-password.php";
                </script>
            <?php
        
        }
        catch(Exception $e){
            
            $msg = $e->getMessage();
            $msg = base64_encode($msg);
            ?>
                <script>
                    window.location ="../view/otp-verification.php?msg=<?php echo $msg; ?>";
                </script>
            <?php  
        }
    break;
    
    case "reset-forgotten-password":
        
        try{
            
            $user_id = $_SESSION["user_id_for_reset"];
            $password = $_POST["new_password"];
            $confirmedPassword = $_POST["confirm_password"];
            
            if(!isset($_SESSION['otp_verified']) || $_SESSION['otp_verified']!=true ){
                
                session_destroy();
                $msg = "Unauthorized Access";
                $msg = base64_encode($msg);
                ?>
                    <script>
                        window.location="../view/login.php?msg=<?php echo $msg;?>";
                    </script>
                <?php
            }
            
            if($password==""){
                throw new Exception ("New password cannot be empty");
            }
            if($confirmedPassword==""){
                throw new Exception ("Password confirmation failed");
            }
            if($password!==$confirmedPassword){
                throw new Exception ("Passwords do not match");
            }
            if (strlen($password) < 10) {
                throw new Exception("New password should be more than 10 characters");
            }

            if (!preg_match('/[A-Za-z]/', $password)) {
                throw new Exception("New password should contain a letter");
            }

            if (!preg_match('/\d/', $password)) {
                throw new Exception("New password should contain a number");
            }
            
            $loginObj->updatePassword($password, $user_id);
            
            session_destroy();
            
            $msg = "Your Password Has Been Reset";
            $msg = base64_encode($msg);
            
            ?>
                <script>
                    window.location="../view/login.php?msg=<?php echo $msg; ?>&success=true";
                </script>
            <?php
        
        } 
        catch (Exception $e) {
            
            $msg = $e->getMessage();
            $msg = base64_encode($msg);
            
            ?>
                <script>
                    window.location ="../view/reset-forgotten-password.php?msg=<?php echo $msg; ?>";
                </script>
            <?php

        }
    break;
}   
