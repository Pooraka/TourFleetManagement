<?php
session_start();

include '../model/login_model.php';
$loginObj = new Login ();

if(!isset($_GET["status"])){
    ?>
    <script>
        window.location="../view/login.php";
    </script>
    <?php
}

$status = $_GET["status"];

switch($status)
{
    case "login";
        $login_username = $_POST["loginusername"];
        $login_password = $_POST["loginpassword"];

        try{
            if($login_username==""){
                throw new Exception("Username cannot be empty");
            }
            if($login_password==""){
                throw new Exception("Password cannot be empty");
            }

            $loginResult = $loginObj->validateUser($login_username,$login_password);

            //if matching record is found
            if($loginResult->num_rows>0){

                //converting $loginResult to an associate array
                $userrow = $loginResult->fetch_assoc();

                $_SESSION["user"]=$userrow;

                ?>
                <script>
                    window.location = "../view/dashboard.php";
                </script>

                <?php
            }
            else{
                throw new Exception ("Invalid Credentials");
            }
        }

        catch(Exception $ex){
            $msg = $ex->getMessage();
            $msg = base64_encode($msg);
            ?>
                <script>
                    window.location="../view/login.php?msg=<?php echo $msg?>";
                </script>
            <?php
        }
    break;
}