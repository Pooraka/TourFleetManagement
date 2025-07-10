<?php

include_once '../commons/session.php';
include_once '../model/supplier_model.php';


//get user information from session
$userSession=$_SESSION["user"];

$supplierObj = new Supplier();
$supplierResult = $supplierObj->getSuppliers();
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
        <?php $pageName="Tender Management - View Suppliers" ?>
        <?php include_once "../includes/header_row_includes.php";?>
        <div class="col-md-3">
            <ul class="list-group">
                <a href="add-supplier.php" class="list-group-item" style="display:<?php echo checkPermissions(61); ?>">
                    <span class="glyphicon glyphicon-plus"></span> &nbsp;
                    Add Supplier
                </a>
                <a href="view-suppliers.php" class="list-group-item" style="display:<?php echo checkPermissions(62); ?>">
                    <span class="glyphicon glyphicon-search"></span> &nbsp;
                    View Suppliers
                </a>
                <a href="add-tender.php" class="list-group-item" style="display:<?php echo checkPermissions(67); ?>">
                    <span class="glyphicon glyphicon-plus"></span> &nbsp;
                    Add Tender
                </a>
                <a href="open-tenders.php" class="list-group-item" style="display:<?php echo checkPermissions(68); ?>">
                    <span class="glyphicon glyphicon-search"></span> &nbsp;
                    Open Tenders
                </a>
                <a href="tender-status-report.php" class="list-group-item" style="display:<?php echo checkPermissions(150); ?>">
                    <span class="glyphicon glyphicon-search"></span> &nbsp;
                    Tender Status Report
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
                    <table class="table" id="suppliertable">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Contact</th>
                                <th>Email</th>                               
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while($supplierRow = $supplierResult->fetch_assoc()){ 
                                
                                $supplierStatus = match((int)$supplierRow['supplier_status']){
                                    
                                    -1=>"Removed",
                                    0=>"Deactivated",
                                    1=>"Active",
                                };
                                
                                ?>
                                
                            <tr>
                                <td><?php echo $supplierRow['supplier_name'];?></td>
                                <td><?php echo $supplierRow['supplier_contact'];?></td>
                                <td><?php echo $supplierRow['supplier_email'];?></td>
                                <td><?php echo $supplierStatus;?></td>
                                <td>
                                    <a href="edit-supplier.php?supplier_id=<?php echo base64_encode($supplierRow['supplier_id']);?>" 
                                       class="btn btn-xs btn-warning" style="margin:2px;display:<?php echo checkPermissions(63); ?>">
                                        <span class="glyphicon glyphicon-pencil"></span>
                                        Edit
                                    </a>
                                    <?php if($supplierRow['supplier_status']==1){?>
                                    <a href="../controller/supplier_controller.php?status=deactivate_supplier&supplier_id=<?php echo base64_encode($supplierRow['supplier_id']);?>" 
                                       class="btn btn-xs btn-danger" style="margin:2px;display:<?php echo checkPermissions(65); ?>">
                                        <span class="glyphicon glyphicon-remove"></span>
                                        Deactivate
                                    </a>
                                    <?php } elseif($supplierRow['supplier_status']==0){ ?>
                                    <a href="../controller/supplier_controller.php?status=activate_supplier&supplier_id=<?php echo base64_encode($supplierRow['supplier_id']);?>" 
                                       class="btn btn-xs btn-success" style="margin:2px;display:<?php echo checkPermissions(64); ?>">
                                        <span class="glyphicon glyphicon-ok"></span>
                                        Activate
                                    </a>
                                    <?php } ?>
                                    <a href="../controller/supplier_controller.php?status=remove_supplier&supplier_id=<?php echo base64_encode($supplierRow['supplier_id']);?>" 
                                       class="btn btn-xs btn-danger" style="margin:2px;display:<?php echo checkPermissions(66); ?>">
                                        <span class="glyphicon glyphicon-trash"></span>
                                        Remove
                                    </a>
                                </td>
                            </tr>
                            <?php } ?>
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

        $("#suppliertable").DataTable();
    });
</script>
</html>