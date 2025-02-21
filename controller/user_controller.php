<?php
include '../commons/session.php';
include '../model/user_model.php';
$userObj = new User();

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
            
            $file_name=time()."_".$user_image["name"];
            $path ="../images/userimages/$file_name";
            move_uploaded_file($user_image["tmp_name"],$path);
            $user_id = $userObj->addUser($fname,$lname,$email,$dob,$nic,$user_role,$file_name);
            
              
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
        
}

