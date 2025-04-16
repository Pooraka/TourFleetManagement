<?php

include '../commons/session.php';

if (!isset($_SESSION['otp_verified']) || $_SESSION['otp_verified']!=true ) {
    
    http_response_code(403);
    ?>
        <script>
            window.location="/tourfleetmanagement/errorpages/403.php";
        </script>
    <?php
    session_destroy();
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <!-- <meta http-equiv="refresh" content="3"> -->
        <title>Reset Password</title>
        <?php include_once "../includes/bootstrap_css_includes.php" ?>
    </head>
    <body>
        <div class="container">
            <div class="row" style="height:100px;">
                </br>
                <div class="hidden-sm hidden-md hidden-lg" style="text-align:center;font-size:25px">
                    <span><b>Tour Fleet Management System</b></span>
                </div>
                <div id="msg" class="col-md-6 col-md-offset-3 col-sm-8 col-sm-offset-2 col-xs-8 col-xs-offset-2" style="text-align:center;">
                    <?php if (isset($_GET["msg"]) && isset($_GET["success"]) && $_GET["success"]==true) {
                        ?>
                        <script>
                            var msgElement = document.getElementById("msg");
                            msgElement.classList.add("alert", "alert-success");
                        </script>
                        <b> 
                            <p align="center">
                                <?php
                                echo base64_decode($_GET["msg"]);
                                ?>
                            </p>
                        </b>
                        <?php
                    } elseif (isset($_GET["msg"])) {
                        ?>
                        <script>
                            var msgElement = document.getElementById("msg");
                            msgElement.classList.add("alert", "alert-danger");
                        </script>
                        <b> 
                            <p align="center">
                                <?php
                                echo base64_decode($_GET["msg"]);
                                ?>
                            </p>
                        </b>
                        <?php
                    }
                    ?>
                </div>
            </div>
            <form action="../controller/login_controller.php?status=reset-forgotten-password" method="post">
                <div class="row" style="height:500px;">
                    <div class="col-md-5 col-md-offset-1 col-sm-7 panel panel-default hidden-xs" 
                         style="height:100%;background-image:url('../images/loginimage.jpg');
                         background-size: cover; background-repeat: no-repeat;border-radius:35px 0px 0px 35px; background-position: center center;box-shadow: 10px 10px 10px grey">
                    </div> 
                    <div class="col-md-5 col-sm-5 col-xs-12 panel panel-default login-panel" style="height:100%;border-radius:0px 35px 35px 0px;   box-shadow: 10px 10px 10px grey" >
                        <div class="row">
                            <div style="text-align:center">
                                <a href="../index.php">
                                    <img src="../images/logo.png" alt="" style="height:170px">
                                </a>
                            </div>
                        </div>
                        </br>
                        <div class="row">
                            <span>&nbsp;&nbsp;&nbsp;&nbsp;</span>
                            <label style="font-size:15px">Reset Your Password</label>  
                        </div>
                        <div class="row">
                            &nbsp;
                        </div>
                        <div class="row">
                            <div class="col-md-10 col-md-offset-1">
                                <label> Password must be at least 10 characters and contain letters and numbers</label>
                            </div>
                        </div>
                        <div class="row">
                            &nbsp;
                        </div>
                        <div class="row">
                            &nbsp;
                        </div>
                        <div class="row">
                            <div class="col-md-10 col-md-offset-1 form-group" id="newPasswordGroup">
                                <span class="input-group">
                                    <span class="input-group-addon">
                                        <span class="glyphicon glyphicon-lock"></span>
                                    </span>
                                    <input type="password" id="new_password" name="new_password" class="form-control" placeholder="Enter New Password"/>
                                </span>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-10 col-md-offset-1 form-group" id="confirmPasswordGroup">
                                <span class="input-group">
                                    <span class="input-group-addon">
                                        <span class="glyphicon glyphicon-lock"></span>
                                    </span>
                                    <input type="password" id="confirm_password" name="confirm_password" class="form-control" placeholder="Confirm Your Password"/>
                                </span>
                            </div>
                        </div>
                        <div class="row">
                            &nbsp;
                        </div>
                        <div class="row">
                            <div class="col-md-10 col-md-offset-1">
                                <input type="submit" name="submit" class="btn btn-primary btn-block" style="background-color:#0d778a" value="Change Password"/>
                            </div>
                        </div>
                        <div class="row">
                            &nbsp;
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </body>
    <script src="../js/jquery-3.7.1.js"></script>
    <script src="../js/resetPasswordValidation.js"></script>
</html>