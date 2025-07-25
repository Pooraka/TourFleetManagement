<?php

include_once '../commons/session.php';

//get user information from session
$userSession=$_SESSION["user"];
?>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer</title>
    <?php include_once "../includes/bootstrap_css_includes.php"?>
</head>
<body>
    <div class="container">
        <?php $pageName="Customer - Add Customer" ?>
        <?php include_once "../includes/header_row_includes.php";?>
        <div class="col-md-3">
            <?php include_once "../includes/customer_functions.php"; ?>
        </div>
        <form action="../controller/customer_controller.php?status=add_customer" method="post" enctype="multipart/form-data">
            <div class="col-md-9">
                <div class="row">
                    <div class="col-md-6 col-md-offset-3" id="msg" style="text-align:center">
                        <?php
                        if (isset($_GET["msg"]) && isset($_GET["success"]) && $_GET["success"] == true) {

                            $msg = base64_decode($_GET["msg"]);
                            ?>
                            <div class="row">
                                <div class="alert alert-success" style="text-align:center">
                                    <?php echo $msg; ?>
                                </div>
                            </div>
                            <?php
                        } elseif (isset($_GET["msg"])) {

                            $msg = base64_decode($_GET["msg"]);
                            ?>
                            <div class="row">
                                <div class="alert alert-danger" style="text-align:center">
                                    <?php echo $msg; ?>
                                </div>
                            </div>
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
                        <label class="control-label">NIC</label>
                    </div>
                    <div class="col-md-3">
                        <input type="text" class="form-control" name="nic" id="nic"/>
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
                    <div class="col-md-offset-3 col-md-6">
                        <input type="submit" class="btn btn-primary" value="Submit"/>
                        <input type="reset" class="btn btn-danger" value="Reset"/>
                    </div>
                </div>
            </div>
        </form>
    </div>
</body>
<script src="../js/jquery-3.7.1.js"></script>
<script src="../js/customerValidation.js"></script>
</html>