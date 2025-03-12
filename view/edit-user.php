<?php

include_once '../commons/session.php';
include_once '../model/user_model.php';

//get user information from session
$userSession=$_SESSION["user"];

$userObj = new User();

$roleResult = $userObj->getAllRoles();

$user_id = $_GET["user_id"];
$user_id = base64_decode($user_id);

$userResult = $userObj->getUser($user_id);
$contactResult = $userObj->getUserContact($user_id);

$mobileRow=$contactResult->fetch_assoc();
$landlineRow=$contactResult->fetch_assoc();

if($mobileRow['contact_type']==2 && !isset($landlineRow)){
    $landlineRow=$mobileRow;
    $mobileRow=null;
}

$userRow = $userResult->fetch_assoc();

//getting already assigned user functions

$functionArray = array();

$userFunctionResult = $userObj->getUserFunctions($user_id);

while($function_row= $userFunctionResult->fetch_Assoc()){
    
    array_push($functionArray,$function_row["function_id"]);
    
}
?>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Management</title>
    <?php include_once "../includes/bootstrap_css_includes.php"?>
</head>
<body>
    <div class="container">
    <?php $pageName="User Management - Edit User" ?>
    <?php include_once "../includes/header_row_includes.php";?>
        <div class="col-md-3">
            <ul class="list-group" style="background-color: transparent;">
                <a href="add-user.php" class="list-group-item" style="background-color: #e4eaeb">
                    <span class="glyphicon glyphicon-plus"></span> &nbsp;
                    Add User
                </a>
                <a href="view-users.php" class="list-group-item" style="background-color: #e4eaeb">
                    <span class="glyphicon glyphicon-search"></span> &nbsp;
                    View Users
                </a>
                <a href="generate-user-reports.php" class="list-group-item" style="background-color: #e4eaeb">
                    <span class="glyphicon glyphicon-book"></span> &nbsp;
                    Generate User Reports
                </a>
            </ul>
        </div>
        <form action="../controller/user_controller.php?status=add_user" method="post" enctype="multipart/form-data">
            <div class="col-md-9">
                <div class="row">
                    <div id="msg" class="col-md-offset-3 col-md-6" style="text-align:center;">
                        <?php if(isset($_GET["msg"])){ ?>
                        
                                <script>
                                    var msgElement = document.getElementById("msg");
                                    msgElement.classList.add("alert", "alert-danger");
                                </script>
                                
                                <b> <p> <?php echo base64_decode($_GET["msg"]);?></p></b>
                                <?php
                        }
                    ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-3">
                        <label class="control-label">First Name</label>
                    </div>
                    <div class="col-md-3">
                        <input type="text" class="form-control" name="fname" id="fname" value="<?php echo $userRow["user_fname"];?>"/>
                    </div>
                    <div class="col-md-3">
                        <label class="control-label">Last Name</label>
                    </div>
                    <div class="col-md-3">
                        <input type="text" class="form-control" name="lname" id="lname" value="<?php echo $userRow["user_lname"];?>"/>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        &nbsp;
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-3">
                        <label class="control-label">Email</label>
                    </div>
                    <div class="col-md-3">
                        <input type="email" class="form-control" name="email" id="email" value="<?php echo $userRow["user_email"];?>"/>
                    </div>
                    <div class="col-md-3">
                        <label class="control-label">Date of Birth</label>
                    </div>
                    <div class="col-md-3">
                        <input type="date" class="form-control" name="dob" id="dob" value="<?php echo $userRow["user_dob"];?>"/>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        &nbsp;
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-3">
                        <label class="control-label">NIC</label>
                    </div>
                    <div class="col-md-3">
                        <input type="text" class="form-control" name="nic" id="nic" value="<?php echo $userRow["user_nic"];?>"/>
                    </div>
                    <div class="col-md-3">
                        <label class="control-label">Image</label>
                    </div>
                    <div class="col-md-3">
                        <input type="file" class="form-control" name="user_image" id="user_image" onchange="displayImage(this);"/>
                        <br/><!-- image preview line breaker -->
                                <img id="img_prev" 
                                    <?php   if($userRow["user_image"]!=""){
                                                $img_path = "../images/userimages/".$userRow["user_image"];
                                    ?>
                                    style="border-radius: 50%" src="<?php echo $img_path;?>" width="60px" height="60px"
                                    <?php
                                        }
                                        ?>
                                />
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        &nbsp;
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-3">
                        <label class="control-label">Mobile Number</label>
                    </div>
                    <div class="col-md-3">
                        <input type="text" class="form-control" name="mno" id="mno" value="<?php if(isset($mobileRow)){ echo $mobileRow['contact_number'];}?>"/>
                    </div>
                    <div class="col-md-3">
                        <label class="control-label">Landline</label>
                    </div>
                    <div class="col-md-3">
                        <input type="text" class="form-control" name="lno" id="lno" value="<?php if(isset($landlineRow)) {echo $landlineRow['contact_number'];}?>"/>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        &nbsp;
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-3">
                        <label class="control-label">User Role</label>
                    </div>
                    <div class="col-md-4">
                        <select name="user_role" id="user_role" class="form-control" required="required">
                            <option value="">--------------------------</option>
                            <?php
                                while($roleRow=$roleResult->fetch_assoc()){
                                    ?>
                            <option value="<?php echo $roleRow['role_id'];?>" 
                                    
                                    <?php   if($roleRow["role_id"]==$userRow["user_role"]){ ?>
                                       
                                                selected
                                    <?php
                                            }
                                            
                                    ?>
                                    
                                    
                                    >
                                            <?php echo $roleRow['role_name'];?>
                                        </option>
                            <?php
                                }
                                ?>
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12"> &nbsp; </div>
                </div>
                <div class="row">
                    <div id="display_functions">
                        <?php
                        //This section re-used from user_controller.php's load_functions case
                        
                        $role_id=$userRow["user_role"];

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
                        
                        if($counter%3!=0){
                            echo '</div>';
                        }
                        
                        //Above section re-used from user_controller.php's load_functions case
                        
                        ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12"> &nbsp; </div>
                </div>
                <div class="row">
                    <div class="col-md-offset-3 col-md-6">
                        <input type="submit" class="btn btn-primary" value="Submit"/>
                        <input type="reset" onclick="resetFunctions('<?php echo $user_id;?>','<?php echo $role_id;?>')"class="btn btn-danger" value="Reset"/>
                    </div>
                </div>
            </div>
        </form>
    </div>
    <script src="../js/jquery-3.7.1.js"></script>
    <script src="../js/uservalidation.js"></script>
    <script>
        function displayImage(input){
            if(input.files && input.files[0])
            {
                var reader = new FileReader();
                reader.onload = function(e){
                    $("#img_prev").attr('src',e.target.result).width(60).height(60).css('border-radius', '50%');
                };
                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
</body>
</html>
