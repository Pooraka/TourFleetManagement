<?php

include_once '../commons/session.php';
include_once '../model/customer_model.php';

//get user information from session
$userSession=$_SESSION["user"];

$customerObj = new Customer();
$customerResult = $customerObj->getCustomers();
?>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer</title>
    <link rel="stylesheet" type="text/css" href="../css/dataTables.bootstrap.min.css"/>
    <?php include_once "../includes/bootstrap_css_includes.php"?>
</head>
<body>
    <div class="container">
        <?php $pageName="Customer - View Customers" ?>
        <?php include_once "../includes/header_row_includes.php";?>
        <div class="col-md-3">
            <ul class="list-group">
                <a href="add-customer.php" class="list-group-item">
                    <span class="glyphicon glyphicon-plus"></span> &nbsp;
                    Add Customer
                </a>
                <a href="view-customers.php" class="list-group-item">
                    <span class="glyphicon glyphicon-search"></span> &nbsp;
                    View Customers
                </a>
            </ul>
        </div>
        <div class="col-md-9">
            <div class="row">
                <div class="col-md-6 col-md-offset-3">
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
                <div class="col-md-12">
                    <table class="table" id="customertable">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>NIC</th>
                                <th>Email</th>
                                <th>Contact Number</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php   while($customerRow = $customerResult->fetch_assoc()){
                                
                                $status = ($customerRow['customer_status']==1)?"Active":"Deactivated";
                                $mobile="";
                                $landline="";
                                $customerId = $customerRow['customer_id'];
                                $mobileResult = $customerObj->getCustomerContact($customerId, 1);
                                if($mobileResult->num_rows==1){
                                    $mobileRow = $mobileResult->fetch_assoc();
                                    $mobile = $mobileRow['contact_number'];
                                }
                                
                                $landlineResult = $customerObj->getCustomerContact($customerId, 2);
                                if($landlineResult->num_rows==1){
                                    $landlineRow = $landlineResult->fetch_assoc();
                                    $landline = $landlineRow['contact_number'];
                                }
                                $customerId = base64_encode($customerId);
                                ?>
                            
                            <tr>
                                <td><?php echo $customerRow['customer_fname']." ".$customerRow['customer_lname'];?> </td>
                                <td><?php echo $customerRow['customer_nic'];?> </td>
                                <td><?php echo $customerRow['customer_email'];?> </td>
                                <td><?php if($mobile=="" && $landline==""){
                                            echo "No Contact Number";
                                        } elseif ($mobile!="" && $landline=="") {
                                            echo $mobile;
                                        } elseif ($mobile=="" && $landline!=""){
                                            echo $landline;
                                        } elseif($mobile!="" && $landline!=""){
                                            echo $mobile." / ".$landline;
                                        }
                                        ?>
                                <td><?php echo $status;?></td>
                                <td>
                                    <a href="edit-customer.php?customer_id=<?php echo $customerId;?>" 
                                       class="btn btn-warning" style="margin:2px;display:<?php echo checkPermissions(51); ?>">
                                        <span class="glyphicon glyphicon-pencil"></span>
                                        Edit
                                    </a>
                                    <a href="../controller/customer_controller.php?status=remove_customer&customer_id=<?php echo $customerId; ?>" 
                                       class="btn btn-danger" style="margin:2px;display:<?php echo checkPermissions(52); ?>">
                                        <span class="glyphicon glyphicon-trash"></span>
                                        Remove
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

        $("#customertable").DataTable();
    });
</script>
</html>