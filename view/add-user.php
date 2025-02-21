<?php

include_once '../commons/session.php';
include_once '../model/user_model.php';

//get user information from session
$userRow=$_SESSION["user"];

$userObj = new User();

$roleResult = $userObj->getAllRoles();

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
    <?php $pageName="User Management" ?>
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
                        <input type="text" class="form-control" name="fname" id="fname"/>
                    </div>
                    <div class="col-md-3">
                        <label class="control-label">Last Name</label>
                    </div>
                    <div class="col-md-3">
                        <input type="text" class="form-control" name="lname" id="lname"/>
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
                        <input type="email" class="form-control" name="email" id="email"/>
                    </div>
                    <div class="col-md-3">
                        <label class="control-label">Date of Birth</label>
                    </div>
                    <div class="col-md-3">
                        <input type="date" class="form-control" name="dob" id="dob"/>
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
                        <input type="text" class="form-control" name="nic" id="nic"/>
                    </div>
                    <div class="col-md-3">
                        <label class="control-label">Image</label>
                    </div>
                    <div class="col-md-3">
                        <input type="file" class="form-control" name="user_image" id="user_image" onchange="displayImage(this);"/>
                        <br/><!-- image preview line breaker -->
                        <img id="img_prev" style="">
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
                        <input type="text" class="form-control" name="mno" id="mno"/>
                    </div>
                    <div class="col-md-3">
                        <label class="control-label">Landline</label>
                    </div>
                    <div class="col-md-3">
                        <input type="text" class="form-control" name="lno" id="lno"/>
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
                            <option value="<?php echo $roleRow['role_id'];?>">
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
                        
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12"> &nbsp; </div>
                </div>
                <div class="row">
                    <div class="col-md-offset-3 col-md-6">
                        <input type="submit" class="btn btn-primary" value="Submit"/>
                        <input type="reset" class="btn btn-danger" value="Reset"/>
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
                    $("#img_prev").attr('src',e.target.result).width(80).height(80);
                };
                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
</body>
</html>