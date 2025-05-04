<?php

include_once '../commons/session.php';
include_once '../model/user_model.php';

//get user information from session
$userSession=$_SESSION["user"];

$user_id = $_GET["user_id"];
$user_id = base64_decode($user_id);

$userObj = new User();

$userResult = $userObj->getUser($user_id);
$userRow = $userResult->fetch_assoc();

$contactResult = $userObj->getUserContact($user_id);

$mobileRow=$contactResult->fetch_assoc();
$landlineRow=$contactResult->fetch_assoc();

if($mobileRow!=null && $mobileRow['contact_type']==2){
    $landlineRow=$mobileRow;
    $mobileRow=null;
}

$userFunctionResult = $userObj->getUserFunctions($user_id);

$UserFunctionArray = array();

while($functionRow= $userFunctionResult->fetch_Assoc()){
    
    array_push($UserFunctionArray,$functionRow["function_id"]);
    
}

$user_role = $userRow['user_role'];
$moduleResult=$userObj->getRoleModules($user_role);

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
    <?php $pageName="User Management - View User" ?>
    <?php include_once "../includes/header_row_includes.php";?>
        <div class="col-md-3">
            <ul class="list-group">
                <a href="add-user.php" class="list-group-item">
                    <span class="glyphicon glyphicon-plus"></span> &nbsp;
                    Add User
                </a>
                <a href="view-users.php" class="list-group-item">
                    <span class="glyphicon glyphicon-search"></span> &nbsp;
                    View Users
                </a>
                <a href="generate-user-reports.php" class="list-group-item">
                    <span class="glyphicon glyphicon-book"></span> &nbsp;
                    Generate User Reports
                </a>
            </ul>
        </div>
        <form action="../controller/user_controller.php?status=update_user" method="post" enctype="multipart/form-data">
            <div class="col-md-9">
                <div class="panel panel-info" style="height:auto">
                    <div class="panel-heading">
                        <div class="row">
                            <div class="col-xs-3 col-md-2">
                                <img src="
                                     <?php if($userRow['user_image']){
                                     ?>
                                        ../images/userimages/<?php echo $userRow['user_image'];?>
                                    <?php
                                    } else{
                                    ?>
                                        ../images/userimages/user.png
                                    <?php
                                    }
                                    ?>
                                     
                                     " alt="User Image" class="img-responsive" style="border-radius: 50%;max-height: 100%"/>
                            </div>
                            <div class="col-xs-9 col-md-10" >
                                <h3 style="margin-top: 7px;"><b><?php echo $userRow['user_fname']." ".$userRow['user_lname'];?></b> </h3>
                                <h4 style="color:black; margin-top: 0;"><?php echo $userRow['role_name'];?></h4>
                                <span>Username : <?php echo $userRow['login_username'];?> </span>
                            </div>
                        </div>
                    </div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-6">
                                <h4><span class="glyphicon glyphicon-user"></span> &nbsp; Personal Information</h4>
                                </br>
                                <span style="color:grey;font-size: 16px;">First Name :</span>
                                <span style="font-size: 16px;"><?php echo $userRow['user_fname'];?></span>
                                </br>
                                </br>
                                <span style="color:grey;font-size: 16px;">Last Name :</span>
                                <span style="font-size: 16px;"><?php echo $userRow['user_lname'];?></span>
                                </br>
                                </br>
                                <span style="color:grey;font-size: 16px;">NIC :</span>
                                <span style="font-size: 16px;"><?php echo $userRow['user_nic'];?></span>
                                </br>
                                </br>
                                <span style="color:grey;font-size: 16px;">Date Of Birth :</span>
                                <span style="font-size: 16px;"><?php echo $userRow['user_dob'];?></span>
                                </br>
                                </br>
                            </div>
                            <div class="col-md-6">
                                <h4><span class="glyphicon glyphicon-phone-alt"></span> &nbsp; Contact Information</h4>
                                </br>
                                <span style="color:grey;font-size: 16px;">Email :</span>
                                <span style="font-size: 16px;"><?php echo $userRow['user_email'];?></span>
                                </br>
                                </br>
                                <span style="color:grey;font-size: 16px;">Mobile :</span>
                                <span style="font-size: 16px;"><?php if($mobileRow!=null){ echo $mobileRow['contact_number'];}?></span>
                                </br>
                                </br>
                                <span style="color:grey;font-size: 16px;">Landline :</span>
                                <span style="font-size: 16px;"><?php if($landlineRow!=null) {echo $landlineRow['contact_number'];}?></span>
                                </br>
                                </br>
                            </div>
                        </div>
                        <hr/>
                        <div class="row">
                            <div class="col-md-12">
                                <h4><span class="glyphicon glyphicon-tasks"></span> &nbsp; User Functions</h4>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <?php
                                $counter = 0;

                                while ($moduleRow = $moduleResult->fetch_assoc()) {

                                    $module_id = $moduleRow["module_id"];
                                    $moduleFunctionResult = $userObj->getModuleFunctions($module_id);

                                    $counter++;

                                    if ($counter % 3 == 1) {
                                        echo '<div class="row">';
                                    }
                                    ?>
                                    <div class="col-md-4">
                                        <h4><b><?php echo $moduleRow['module_name']; echo "</br>"; ?></b></h4>
                                        <?php
                                        while ($function_row = $moduleFunctionResult->fetch_assoc()) {
                                            ?>

                                            <input type="checkbox" name="function[]" onclick="return false;" value="<?php echo $function_row["function_id"]; ?>"
                                            <?php
                                            if (in_array($function_row["function_id"], $UserFunctionArray)) {
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
                                    if ($counter % 3 == 0) {
                                        echo '</div>';
                                    }
                                }

                                if ($counter % 3 != 0) {
                                    echo '</div>';
                                }
                                ?>
                            </div>
                        </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
    <script src="../js/jquery-3.7.1.js"></script>
</body>
</html>


