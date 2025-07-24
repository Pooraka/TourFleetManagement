<?php

include_once '../commons/session.php';


//get user information from session
$userSession=$_SESSION["user"];
?>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tender</title>
    <?php include_once "../includes/bootstrap_css_includes.php"?>
</head>
<body>
    <div class="container">
        <?php $pageName="Tender Management - Add Supplier" ?>
        <?php include_once "../includes/header_row_includes.php";?>
        <div class="col-md-3">
            <?php include_once "../includes/tender_functions.php"; ?>
        </div>
        <form action="../controller/supplier_controller.php?status=add_supplier" method="post" enctype="multipart/form-data">
            <div class="col-md-9">
                <div class="row">
                    <div class="col-md-6 col-md-offset-3" id="msg" >
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
                        <label class="control-label">Name</label>
                    </div>
                    <div class="col-md-4">
                        <input type="text" class="form-control" name="name" id="name"/>
                    </div>
                    <div class="col-md-2">
                        <label class="control-label">Contact</label>
                    </div>
                    <div class="col-md-3">
                        <input type="text" class="form-control" name="contact" id="contact"/>
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
                    <div class="col-md-4">
                        <input type="email" class="form-control" name="email" id="email"/>
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
<script>
    $(document).ready(function () {

        

        $("form").submit(function () {

            var name = $("#name").val();
            var contact = $("#contact").val();
            var email = $("#email").val();

            var contactPattern = /^(07[0-9]{8}|0[0-9]{9})$/;

            if(name==""){
                $("#msg").addClass("alert alert-danger");
                $("#msg").html("<b>Please enter supplier name</b>");
                return false;
            }

            if(contact==""){
                $("#msg").addClass("alert alert-danger");
                $("#msg").html("<b>Please enter supplier contact</b>");
                return false;
            }

            if(!contactPattern.test(contact)){
                $("#msg").addClass("alert alert-danger");
                $("#msg").html("<b>Please enter a valid contact number</b>");
                return false;
            }

            if(email==""){
                $("#msg").addClass("alert alert-danger");
                $("#msg").html("<b>Please enter supplier email</b>");
                return false;
            }
        });
    });
</script>
</html>