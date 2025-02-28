<?php
include '../commons/session.php';
include '../model/user_model.php';
include '../model/login_model.php';
$userObj = new User();
$loginObj = new Login();

if(!isset($_GET["status"])){
    ?>
    <script>
        window.location= "../view/login.php";
    </script>
    <?php
}

$status= $_GET["status"];

switch ($status)
{
    case "load_functions";
    
        $role_id=$_POST["role"];
        
        $moduleResult=$userObj->getRoleModules($role_id);
        $counter=0;
        
        while($moduleRow=$moduleResult->fetch_assoc())
        {
            $module_id=$moduleRow["module_id"];
            $functionResult=$userObj->getModuleFunctions($module_id);
            $counter++;
            
            if($counter%3==1){
                echo '<div class="row">';
            }
            ?>
            <div class="col-md-4" id="module_functions">
                <h4><b><?php echo $moduleRow['module_name'];echo "</br>";?></b></h4>
                <?php
               
                    while($function_row=$functionResult->fetch_assoc()){
                        ?>
                        <input type="checkbox" name="function[]" value="<?php echo $function_row["function_id"];?>" checked/>
                        <?php
                            echo $function_row["function_name"];
                            ?>
                        </br>
                        <?php
                    }
                    ?>
            </div>
        <?php
            if($counter%3==0){
                echo '</div>';
            }
        }
        
        if($counter%3!=0){
            echo '</div>';
        }
    break;

    case "add_user":
        
        $fname = $_POST["fname"];
        $lname = $_POST["lname"];
        $email = $_POST["email"];
        $dob = $_POST["dob"];
        $nic = $_POST["nic"];
        $mno = $_POST["mno"];
        $lno = $_POST["lno"];
        $user_role = $_POST["user_role"];
        $user_image = $_FILES["user_image"];
        $user_functions = $_POST["function"];
        
        
        try{
            
            if($fname==""){
                throw new Exception("First Name cannot be Empty!!!!");
            }
            if($lname==""){
                throw new Exception("Last Name cannot be Empty!!!!");
            }
            if($email==""){
                throw new Exception("Email Name cannot be Empty!!!!");
            }
            if($dob==""){
                throw new Exception("Date of Birth cannot be Empty!!!!");
            }
            if($nic==""){
                throw new Exception("NIC cannot be Empty!!!!");
            }
            if($mno==""){
                throw new Exception("Mobile Number cannot be Empty!!!!");
            }
            if($lno==""){
                throw new Exception("Landline cannot be Empty!!!!");
            }
            if($user_role==""){
                throw new Exception("User Role cannot be Empty!!!!");
            }
            
            $file_name="";
            
            if(isset($_FILES["user_image"])){
                
                if($user_image["name"]!=""){
                    
                    $file_name=time()."_".$user_image["name"];
                    $path="../images/userimages/$file_name";
                    move_uploaded_file($user_image["tmp_name"],$path);
                    
                }
                
            }
            
            $user_id = $userObj->addUser($fname,$lname,$email,$dob,$nic,$user_role,$file_name);
            
            //creating a login account
            
            if($user_id>0){
                
                $loginObj->addUserLogin($user_id,$email,$nic);
                
                foreach($user_functions as $fun_id){
                    
                    $userObj->addUserFunctions($user_id,$fun_id);
                    
                }
                
                $msg = "User $fname $lname Added Successfully";
                $msg = base64_encode($msg);
                
                ?>
                    
                <script>
                    window.location="../view/view-users.php?msg=<?php echo $msg;?>";
                </script>
                
                <?php
                
                
            }
            
            
              
        }
        
        catch(Exception $e){
            
            $msg= $e->getMessage();
            $msg= base64_encode($msg);
            ?>
    
            <script>
                window.location="../view/add-user.php?msg=<?php echo $msg;?>";
            </script>
            <?php
        }
        
    break;

    case "activate":
        
        $user_id = $_GET["user_id"];
        $user_id = base64_decode($user_id);
        
        $userObj->activateUser($user_id);
        
        $msg = "User Activated Successfully";
        $msg = base64_encode($msg);
        
        ?>
            <script>
                window.location="../view/view-users.php?msg=<?php echo $msg;?>";
            </script>
        <?php
        
    break;


    case "deactivate":
        
        $user_id = $_GET["user_id"];
        $user_id = base64_decode($user_id);
        
        $userObj->deactivateUser($user_id);
        
        $msg = "User De-activated Successfully";
        $msg = base64_encode($msg);
        
        ?>
            <script>
                window.location="../view/view-users.php?msg=<?php echo $msg;?>";
            </script>
        <?php
        
    break;


    case "delete":
        
        $user_id = $_GET["user_id"];
        $user_id = base64_decode($user_id);
        
        $userObj->deleteUser($user_id);
        
        $msg = "User Deleted Successfully";
        $msg = base64_encode($msg);
        
        ?>
            <script>
                window.location="../view/view-users.php?msg=<?php echo $msg;?>";
            </script>
        <?php
        
    break;
        
}

