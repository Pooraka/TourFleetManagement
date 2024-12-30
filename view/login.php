<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- <meta http-equiv="refresh" content="3"> -->
    <title>Welcome</title>
    <?php include_once "../includes/bootstrap_css_includes.php"?>
</head>
<body style="background-color:#f2f3ee">
    <div class="container">
        <div class="row" style="height:100px;">
            </br>
            <div id="msg" class="col-md-6 col-md-offset-3">
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
                <div class="col-md-5 col-md-offset-1 col-sm-7" 
                style="height:100%;background-image:url('../images/loginimage.jpg');
                background-size: cover; background-repeat: no-repeat;border-radius:35px 0px 0px 35px; background-position: center center">
                </div> 
                <div class="col-md-5 col-sm-5 panel panel-default" style="height:100%;border-radius:0px 35px 35px 0px" >
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
    <script src="../js/jquery-3.7.1.js"></script>
    <script src="../js/loginValidation.js"></script>
</body>
</html>