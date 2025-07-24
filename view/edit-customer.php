<?php

include_once '../commons/session.php';
include_once '../model/customer_model.php';

//get user information from session
$userSession=$_SESSION["user"];

$customerId = $_GET["customer_id"];
$customerId = base64_decode($customerId);

$customerObj = new Customer();
$customerResult = $customerObj->getCustomer($customerId);
$customerRow = $customerResult->fetch_assoc();

$existingNIC = $customerRow['customer_nic'];

$mobile="";
$mobileResult = $customerObj->getCustomerContact($customerId, 1);
if ($mobileResult->num_rows == 1) {
    $mobileRow = $mobileResult->fetch_assoc();
    $mobile = $mobileRow['contact_number'];
}

$landline="";
$landlineResult = $customerObj->getCustomerContact($customerId, 2);
if ($landlineResult->num_rows == 1) {
    $landlineRow = $landlineResult->fetch_assoc();
    $landline = $landlineRow['contact_number'];
}
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
        <?php $pageName="Customer - Edit Customer" ?>
        <?php include_once "../includes/header_row_includes.php";?>
        <div class="col-md-3">
            <?php include_once "../includes/customer_functions.php"; ?>
        </div>
        <form action="../controller/customer_controller.php?status=update_customer" method="post" enctype="multipart/form-data">
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
                        <label class="control-label">First Name</label>
                    </div>
                    <div class="col-md-3">
                        <input type="hidden" name="customer_id" value="<?php echo $customerId;?>" />
                        <input type="hidden" name="existing_nic" value="<?php echo $existingNIC;?>" />
                        <input type="text" class="form-control" name="fname" id="fname" value="<?php echo $customerRow['customer_fname'];?>"/>
                    </div>
                    <div class="col-md-3">
                        <label class="control-label">Last Name</label>
                    </div>
                    <div class="col-md-3">
                        <input type="text" class="form-control" name="lname" id="lname" value="<?php echo $customerRow['customer_lname'];?>"/>
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
                        <input type="email" class="form-control" name="email" id="email" value="<?php echo $customerRow['customer_email'];?>"/>
                    </div>
                    <div class="col-md-3">
                        <label class="control-label">NIC</label>
                    </div>
                    <div class="col-md-3">
                        <input type="text" class="form-control" name="nic" id="nic" value="<?php echo $customerRow['customer_nic'];?>"/>
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
                        <input type="text" class="form-control" name="mno" id="mno" value="<?php echo $mobile;?>"/>
                    </div>
                    <div class="col-md-3">
                        <label class="control-label">Landline</label>
                    </div>
                    <div class="col-md-3">
                        <input type="text" class="form-control" name="lno" id="lno" value="<?php echo $landline;?>"/>
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