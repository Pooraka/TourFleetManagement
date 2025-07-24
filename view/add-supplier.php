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
                    <div id="msg" class="col-md-offset-3 col-md-6" style="text-align:center;">
                        <?php if (isset($_GET["msg"])) { ?>

                            <script>
                                var msgElement = document.getElementById("msg");
                                msgElement.classList.add("alert", "alert-danger");
                            </script>

                            <b> <p> <?php echo base64_decode($_GET["msg"]); ?></p></b>
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
</html>