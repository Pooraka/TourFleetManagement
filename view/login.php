<html>
    <head>
    <link rel="stylesheet" type="text/css" href="../bootstrap/css/bootstrap.css"/>
    </head>
    <body>
        <!-- Login container -->
        <div class="container">
            <div class="row" style="height:100px">
            </div>
            <form action="../controller/login_controller.php?status=login" method="post">
                <!-- Error message display row -->
                <div class="row">
                    <div class="col-md-6 col-md-offset-3" id="msg"></div>
                    <?php
                    if(isset($_GET["message"])){
                    ?>
                    <div class="col-md-6 col-md-offset-3 alert alert-danger">
                        <?php 
                            echo base64_decode($_GET["message"]);
                        ?>
                    </div>
                    <?php
                        }
                    ?>
                </div>

                <!-- End error message row -->
                <div class="row">
                    <!-- Panel -->
                    <div class="col-md-8 col-md-offset-2 panel panel-default" style="height:300px">

                        <!-- Left box image -->
                        <div class="col-md-6 col-md-offset-0 col-sm-offset-3 col-sm-6 col-xs-offset-2 col-xs-6" style="height:300px">
                            <img src="../images/logo.png" height="300px"/>
                        </div>

                        <!-- Right box login data -->
                        <div class="col-md-6 col-md-offset-0 col-sm-12 col-xs-12" style="height:300px">
                            <div class="row">
                                &nbsp;
                            </div>
                            <div class="row">
                                <label style="font-size:18px">Sign Into Your Account</label>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <span class="input-group">
                                        <span class="input-group-addon">
                                            <span class="glyphicon glyphicon-user"></span>
                                        </span>
                                        <input type="email" id="loginusername" name="loginusername" class="form-control" placeholder="Username" />
                                    </span>
                                </div>
                            </div>
                            <div class="row">
                                &nbsp;
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <span class="input-group">
                                        <span class="input-group-addon">
                                            <span class="glyphicon glyphicon-lock"></span>
                                        </span>
                                        <input type="password" id="loginpassword" name="loginpassword" class="form-control" placeholder="Password"/>
                                    </span>
                                </div>
                            </div>
                            <div class="row">
                                &nbsp;
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <input type="submit" name="submit" class="btn btn-primary btn-block" style="background-color:#170680"/>
                                </div>
                            </div>
                            <div class="row">
                                &nbsp;
                            </div>
                            <div class="row">
                                <a href="">Forgot password ?</a>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <!-- Login container end -->
    </body>
    <!-- <script src="../js/jquery-3.7.1.js"></script> -->
    <script src="../js/loginValidation.js"></script>
</html>