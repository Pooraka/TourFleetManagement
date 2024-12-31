<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- <meta http-equiv="refresh" content="3"> -->
    <title>Welcome</title>
    <?php include_once "../includes/bootstrap_css_includes.php"?>
</head>
<body style="background-color:#f2f3ee" class="login-page-body">
    <div class="container">
        <div class="row" style="height:100px;">
            </br>
            <div class="hidden-sm hidden-md hidden-lg" style="text-align:center;font-size:25px">
                <span><b>Tour Fleet Management System</b></span>
            </div>
            <div id="msg" class="col-md-6 col-md-offset-3 col-sm-8 col-sm-offset-2 col-xs-8 col-xs-offset-2" style="text-align:center;">
                <?php if(isset($_GET["msg"])){
                    ?>
                    <script>
                        $(document).ready(function(){
                            $("#msg").addClass("alert alert-danger");
                        });
                    </script>
                    <b> <p align="center">
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
        <form action="../controller/login_controller.php?status=login" method="post">
            <div class="row" style="height:500px;">
                <div class="col-md-5 col-md-offset-1 col-sm-7 panel panel-default hidden-xs" 
                style="height:100%;background-image:url('../images/loginimage.jpg');
                background-size: cover; background-repeat: no-repeat;border-radius:35px 0px 0px 35px; background-position: center center">
                </div> 
                <div class="col-md-5 col-sm-5 col-xs-12 panel panel-default login-panel" style="height:100%;border-radius:0px 35px 35px 0px" >
                    <div class="row">
                        <div style="text-align:center">
                            <a href="../index.php">
                                <img src="../images/logo.png" alt="" style="height:170px">
                            </a>
                        </div>
                    </div>
                    </br>
                    </br>
                    <div class="row">
                        <span>&nbsp;&nbsp;&nbsp;&nbsp;</span>
                        <label style="font-size:20px">Sign Into Your Account</label>  
                    </div>
                    <div class="row">
                        &nbsp;
                    </div>
                    <div class="row">
                        <div class="col-md-10 col-md-offset-1">
                            <span class="input-group">
                                <span class="input-group-addon">
                                    <span class="glyphicon glyphicon-user"></span>
                                </span>
                                <input type="email" id="loginusername" name="loginusername" class="form-control"/>
                            </span>
                        </div>
                    </div>
                    <div class="row">
                        &nbsp;
                    </div>
                    <div class="row">
                        <div class="col-md-10 col-md-offset-1">
                            <span class="input-group">
                                <span class="input-group-addon">
                                    <span class="glyphicon glyphicon-lock"></span>
                                </span>
                                <input type="password" id="loginpassword" name="loginpassword" class="form-control"/>
                            </span>
                        </div>
                    </div>
                    <div class="row">
                        &nbsp;
                    </div>
                    <div class="row">
                        <div class="col-md-10 col-md-offset-1">
                            <input type="submit" name="submit" class="btn btn-primary btn-block" style="background-color:#0d778a"/>
                        </div>
                    </div>
                    <div class="row">
                        &nbsp;
                    </div>
                    <div class="row">
                        <div class="col-md-10 col-md-offset-1">
                            <a href="">Forgot your password?</a>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</body>
<script src="../js/jquery-3.7.1.js"></script>
<!-- <script src="../js/loginValidation.js"></script> -->
</html>