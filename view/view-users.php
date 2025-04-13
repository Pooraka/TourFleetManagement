<?php

include_once '../commons/session.php';
include_once '../model/module_model.php';
include_once '../model/user_model.php';

//get user information from session
$userSession=$_SESSION["user"];

$userObj = new User();

$userResult = $userObj->getAllUsers();

?>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Management</title>
    
    <link rel="stylesheet" type="text/css" href="../css/dataTables.bootstrap.min.css"/>
    <?php include_once "../includes/bootstrap_css_includes.php"?>
</head>
<body>
    <div class="container">
        <?php $pageName="User Management - View Users" ?>
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
        <div class="col-md-9">
            <?php
            
                if(isset($_GET["msg"])){
                    
                    $msg = base64_decode($_GET["msg"]);
            ?>
                    <div class="row">
                        <div class="alert alert-success" style="text-align:center">
                            <?php echo $msg; ?>
                        </div>
                    </div>
            <?php
                }
            ?>
            
            <div class="row">
                <div class="col-md-12">
                    <table class="table" id="usertable">
                        <thead>
                            <tr>
                                <th>Image</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Status</th>
                                <th>&nbsp;</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                while($userRow=$userResult->fetch_assoc()){
                                    
                                    $status = "Active";
                                    
                                    if($userRow["user_status"]==0){
                                        
                                        $status ="Deactive";
                                    }
                                    
                                    $img_path="../images/userimages/";
                                    
                                    if($userRow["user_image"]==""){
                                        
                                        $img_path=$img_path."user.png"; 
                                        
                                    }else{
                                        
                                        $img_path=$img_path.$userRow["user_image"];
                                    }
                                    
                                    $user_id = $userRow["user_id"];
                                    $user_id = base64_encode($user_id);
                            ?>
                                    <tr
                                        <?php
                                                
                                                if($userRow["user_status"]==1){
                                            ?>
                                                    style="background-color:#92bdbf;color: black;"
                                            <?php
                                                }elseif($userRow["user_status"]==0){
                                            ?>        
                                                    style="background-color:#d8bfd8;color: black;"
                                            <?php
                                                }
                                            ?>
                                        >
                                        <td>
                                            <img src="<?php echo $img_path;?>" style="border-radius: 50%" height="60px" width="60px"/>
                                        </td>
                                        <td><?php echo $userRow["user_fname"]." ".$userRow["user_lname"];?></td>
                                        <td><?php echo $userRow["user_email"];?></td>
                                        <td><?php echo $status;?></td>
                                        <td>
                                            <a href="view-user.php?user_id=<?php echo $user_id;?>" class="btn btn-info" style="margin:2px">
                                                <span class="glyphicon glyphicon-search"></span>                                                  
                                                View
                                            </a>
                                            <a href="edit-user.php?user_id=<?php echo $user_id; ?>" class="btn btn-warning" style="margin:2px">
                                                <span class="glyphicon glyphicon-pencil"></span>
                                                Edit
                                            </a>
                                            <?php
                                            if($userRow["user_status"]==0){
                                            ?>
                                            <a href="../controller/user_controller.php?status=activate&user_id=<?php echo $user_id; ?>" class="btn btn-success" style="margin:2px">
                                                <span class="glyphicon glyphicon-ok"></span>
                                                Activate
                                            </a>
                                            <?php
                                            }elseif ($userRow["user_status"]==1) {     
                                            ?>
                                            <a href="../controller/user_controller.php?status=deactivate&user_id=<?php echo $user_id; ?>" class="btn btn-danger" style="margin:2px">
                                                <span class="glyphicon glyphicon-remove"></span>
                                                Deactivate
                                            </a>
                                            <?php
                                            }
                                            ?>
                                            <a href="../controller/user_controller.php?status=delete&user_id=<?php echo $user_id; ?>" class="btn btn-danger" style="margin:2px">
                                                <span class="glyphicon glyphicon-trash"></span>
                                                Delete
                                            </a>   
                                        </td>
                                    </tr>
                            <?php
                                }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</body>
<script src="../js/datatable/jquery-3.5.1.js"></script>
<script src="../js/datatable/jquery.dataTables.min.js"></script>
     <script src="../js/datatable/dataTables.bootstrap.min.js"></script>
     <script>
         $(document).ready(function(){
             
             $("#usertable").DataTable();
         });
     </script>
</html>