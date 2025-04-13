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
    case "load_functions":
    
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
        
        try{
        
            $fname = $_POST["fname"];
            $lname = $_POST["lname"];
            $email = strtolower(trim($_POST["email"]));
            $dob = $_POST["dob"];
            $nic = strtoupper(trim($_POST["nic"]));
            $mno = $_POST["mno"];
            $lno = $_POST["lno"];
            $user_name = strtolower(trim($_POST["username"]));
            $user_role = $_POST["user_role"];
            $user_image = $_FILES["user_image"];

            if(isset($_POST["function"]) && !empty($_POST["function"]) ){
                
                $user_functions = $_POST["function"];
            }
            else{
                throw new Exception("User functions are not selected");
            }

            $patnic = "/^([0-9]{9}[VX]{1}|[0-9]{12})$/";
        
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
            if(!preg_match($patnic, $nic)){
                 throw new Exception("Invalid NIC");
            }
            if($mno==""){
                throw new Exception("Mobile Number cannot be Empty!!!!");
            }
            if($lno==""){
                throw new Exception("Landline cannot be Empty!!!!");
            }
            if($user_name==""){
                throw new Exception("Username cannot be Empty!!!!");
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
            
            //check for existing emails
            $existingEmailResult = $userObj->checkIfEmailExist($email);
            
            if($existingEmailResult->num_rows>0){
                
                throw new Exception("Email already exist");
            }
            
            //check for existing NICs
            $existingNICResult = $userObj->checkIfNICExist($nic);
            
            if($existingNICResult->num_rows>0){
                
                throw new Exception("NIC already exist");
            }
            
            //check if username is already taken
            $existingUsernameResult = $loginObj->checkIfUsernameExist($user_name);
            
            if($existingUsernameResult->num_rows>0){
                throw new Exception("Username already taken");
            }
            
            $user_id = $userObj->addUser($fname,$lname,$email,$dob,$nic,$user_role,$file_name);
            
            //creating a login account
            
            if($user_id>0){
                
                $loginObj->addUserLogin($user_id,$user_name,$nic);
                
                //insert user contact numbers
                if($mno!=""){
                    $userObj->addUserContact($user_id,$mno,1);
                }
                if($lno!=""){
                    $userObj->addUserContact($user_id,$lno,2);
                }
                
                
                //insert user functions
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

    case "reset_functions":
        
        $functionArray = array();
        $user_id = $_POST["user_id"];
        
        $userFunctionResult = $userObj->getUserFunctions($user_id);

        while($function_row= $userFunctionResult->fetch_Assoc()){
    
            array_push($functionArray,$function_row["function_id"]);
    
        }
        
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
                <h5><b><?php echo $moduleRow['module_name'];echo "</br>";?></b></h5>
                <?php

                    while($function_row=$functionResult->fetch_assoc()){
                        ?>
                        <input type="checkbox" name="function[]" value="<?php echo $function_row["function_id"];?>" 

                            <?php
                                if(in_array($function_row["function_id"],$functionArray)){
                                    ?>    
                                        checked
                                    <?php
                                }
                            ?>

                               />
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

        if($counter%3==0){
            echo '</div>';
        }
        
        
    break;
    
    case "update_user":
        
        try{
        
            $user_id = $_POST["user_id"];
            $fname = $_POST["fname"];
            $lname = $_POST["lname"];
            $email = strtolower(trim($_POST["email"]));
            $dob = $_POST["dob"];
            $nic = strtoupper(trim($_POST["nic"]));
            $mno = $_POST["mno"];
            $lno = $_POST["lno"];
            $user_role = $_POST["user_role"];
            $user_image = $_FILES["user_image"];
            $patnic = "/^([0-9]{9}[VX]{1}|[0-9]{12})$/";

            if (isset($_POST["function"]) && !empty($_POST["function"])) {

                $user_functions = $_POST["function"];
            } else {
                throw new Exception("User functions are not selected");
            }

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
            if(!preg_match($patnic, $nic)){
                 throw new Exception("Invalid NIC");
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
            

            $userResult = $userObj->getUser($user_id);
            $userRow = $userResult->fetch_assoc();
            $prev_image = $userRow["user_image"];

            if($user_image["name"]!=""){

                //uploading new image
                $file_name = time()."_".$user_image["name"];
                $path = "../images/userimages/";
                move_uploaded_file($user_image["tmp_name"],$path.$file_name);

                //remove previous image
                if($prev_image!="" && file_exists($path.$prev_image)){
                    unlink($path.$prev_image);
                }
            }
            else{
                $file_name = $prev_image;
            }
            
            if ($email != $userRow["user_email"]) {
                
                //check for existing emails
                $existingEmailResult = $userObj->checkIfEmailExist($email);

                if ($existingEmailResult->num_rows > 0) {

                    throw new Exception("Email already exist");
                }
            }

            if ($nic != $userRow["user_nic"]) {
                //check for existing NICs
                $existingNICResult = $userObj->checkIfNICExist($nic);

                if ($existingNICResult->num_rows > 0) {

                    throw new Exception("NIC already exist");
                }
            }

            //update user
            $userObj->updateUser($fname, $lname, $email, $dob, $nic, $user_role,$file_name, $user_id);

            //remove existing user contact details
            $userObj->removeUserContact($user_id);

            //insert new contact details
            if($mno!=""){
                $userObj->addUserContact($user_id,$mno,1);
            }
            if($lno!=""){
                $userObj->addUserContact($user_id,$lno,2);
            }
            
            //remove existing user functions
            $userObj->removeUserFunctions($user_id);
            
            //insert new user functions
            foreach($user_functions as $fun_id){
                    
                $userObj->addUserFunctions($user_id,$fun_id);

            }
            
            $msg = "User $fname Updated Successfully";
            $msg = base64_encode($msg);
            
            ?>
            
            <script>
                window.location="../view/view-users.php?msg=<?php echo $msg;?>";
            </script>
            
            <?php
        }
        catch(Exception $e){
            
            $msg= $e->getMessage();
            $msg= base64_encode($msg);
            
            $user_id= base64_encode($user_id);
            ?>
    
            <script>
                window.location="../view/edit-user.php?msg=<?php echo $msg;?>&user_id=<?php echo $user_id;?>";
            </script>
            <?php
        }
        
    break;
        
}

