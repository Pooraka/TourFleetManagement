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
            <?php include_once "../includes/tender_functions.php"; ?>
        </div>
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

                                $supplierHasActiveTenders = $supplierObj->checkIfASupplierHasBidsInAnActiveTender($supplierRow['supplier_id']);

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
                                    <?php if($supplierRow['supplier_status']==1 && !$supplierHasActiveTenders){?>
                                    <a href="../controller/supplier_controller.php?status=deactivate_supplier&supplier_id=<?php echo base64_encode($supplierRow['supplier_id']);?>" 
                                       class="btn btn-xs btn-danger deactivate-supplier-btn" style="margin:2px;display:<?php echo checkPermissions(65); ?>">
                                        <span class="glyphicon glyphicon-remove"></span>
                                        Deactivate
                                    </a>
                                    <?php } elseif($supplierRow['supplier_status']==0){ ?>
                                    <a href="../controller/supplier_controller.php?status=activate_supplier&supplier_id=<?php echo base64_encode($supplierRow['supplier_id']);?>" 
                                       class="btn btn-xs btn-success activate-supplier-button" style="margin:2px;display:<?php echo checkPermissions(64); ?>">
                                        <span class="glyphicon glyphicon-ok"></span>
                                        Activate
                                    </a>
                                    <?php } ?>
                                    <?php if(!$supplierHasActiveTenders){?>
                                    <a href="../controller/supplier_controller.php?status=remove_supplier&supplier_id=<?php echo base64_encode($supplierRow['supplier_id']);?>" 
                                       class="btn btn-xs btn-danger remove-supplier-btn" style="margin:2px;display:<?php echo checkPermissions(66); ?>">
                                        <span class="glyphicon glyphicon-trash"></span>
                                        Remove
                                    </a>
                                    <?php } ?>
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
<div class="modal fade" id="activateSupplierConfirmationModal" tabindex="-1" role="dialog" aria-labelledby="modalLabel">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="modalLabel">Confirm Action</h4>
            </div>
            <div class="modal-body">
                Are you sure you want to activate this supplier?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="activateSupplierConfirmActionBtn">Confirm</button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="deactivateSupplierConfirmationModal" tabindex="-1" role="dialog" aria-labelledby="modalLabel">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="modalLabel">Confirm Action</h4>
            </div>
            <div class="modal-body">
                Are you sure you want to deactivate this supplier?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="deactivateSupplierConfirmActionBtn">Confirm</button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="removeSupplierConfirmationModal" tabindex="-1" role="dialog" aria-labelledby="modalLabel">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="modalLabel">Confirm Action</h4>
            </div>
            <div class="modal-body">
                Are you sure you want to remove this supplier?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="removeSupplierConfirmActionBtn">Confirm</button>
            </div>
        </div>
    </div>
</div>
<script src="../js/datatable/jquery-3.5.1.js"></script>
<script src="../js/datatable/jquery.dataTables.min.js"></script>
<script src="../js/datatable/dataTables.bootstrap.min.js"></script>
<script src="../bootstrap/js/bootstrap.min.js"></script>
<script>
    $(document).ready(function(){

        $("#suppliertable").DataTable();

        $("#suppliertable").on("click", ".deactivate-supplier-btn", function(event) {
            event.preventDefault();
            var deactivateUrl = $(this).attr("href");
            $("#deactivateSupplierConfirmationModal").modal("show");

            $("#deactivateSupplierConfirmActionBtn").off("click").on("click", function() {
                window.location.href = deactivateUrl;
            });
        });

        $("#suppliertable").on("click", ".activate-supplier-button", function(event) {
            event.preventDefault();
            var activateUrl = $(this).attr("href");
            $("#activateSupplierConfirmationModal").modal("show");

            $("#activateSupplierConfirmActionBtn").off("click").on("click", function() {
                window.location.href = activateUrl;
            });
        });

        $("#suppliertable").on("click", ".remove-supplier-btn", function(event) {
            event.preventDefault();
            var removeUrl = $(this).attr("href");
            $("#removeSupplierConfirmationModal").modal("show");

            $("#removeSupplierConfirmActionBtn").off("click").on("click", function() {
                window.location.href = removeUrl;
            });
        });
    });
</script>
</html>