<?php

include_once '../commons/session.php';
include_once '../model/purchase_order_model.php';
include_once '../model/sparepart_model.php';
include_once '../model/bid_model.php';
include_once '../model/supplier_model.php';



//get user information from session
$userSession=$_SESSION["user"];

$poObj = new PurchaseOrder();
$poResult = $poObj->getPendingPO();

$sparePartObj = new SparePart();
$bidObj = new Bid();
$supplierObj = new Supplier();

?>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Purchasing</title>
    <link rel="stylesheet" type="text/css" href="../css/dataTables.bootstrap.min.css"/>
    <?php include_once "../includes/bootstrap_css_includes.php"?>
</head>
<body>
    <div class="container">
        <?php $pageName="Purchasing - Pending Purchase Orders" ?>
        <?php include_once "../includes/header_row_includes.php";?>
        <div class="col-md-3">
            <?php include_once "../includes/purchasing_functions.php"; ?>
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
                    <table class="table" id="pending_po">
                        <thead>
                            <tr>
                                <th>PO Number</th>
                                <th>Spare Part</th>
                                <th>Quantity</th>
                                <th>Unit Price</th>
                                <th>Total</th>
                                <th>Supplier</th>
                                <th>PO Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while($poRow = $poResult->fetch_assoc()) {
                                
                                $partId = $poRow['part_id'];
                                $sparePartResult = $sparePartObj->getSparePart($partId);
                                $sparePartRow = $sparePartResult->fetch_assoc();
                                
                                $bidId = $poRow['bid_id'];
                                $bidResult = $bidObj->getBid($bidId);
                                $bidRow = $bidResult->fetch_assoc();
                                
                                $supplierId = $bidRow['supplier_id'];
                                $supplierResult = $supplierObj->getSupplier($supplierId);
                                $supplierRow = $supplierResult->fetch_assoc();
                                
                                $status = match((int)$poRow['po_status']){
                                    -1=>"Rejected",
                                    1=>"Approval Pending",
                                    2=>"Approved",
                                };
                                
                                ?>
                            
                            <tr>
                                <td style="white-space: nowrap;font-size:13px;font-weight: bold"><?php echo $poRow['po_number'];?></td>
                                <td style="white-space: nowrap;font-size:13px;font-weight: bold"><?php echo $sparePartRow['part_number'];?></td>
                                <td><?php echo $poRow['quantity_ordered'];?></td>
                                <td><?php echo "LKR ".number_format($poRow['po_unit_price'],2);?></td>
                                <td><?php echo "LKR ".number_format($poRow['total_amount'],2);?></td>
                                <td><?php echo $supplierRow['supplier_name'];?></td>
                                <td><?php echo $status;?></td>
                                <td>
                                    <?php if($poRow['po_status']==1){ ?>
                                    <a href="../controller/purchase_order_controller.php?status=approve_po&po_id=<?php echo base64_encode($poRow['po_id']);?>" 
                                       class="btn btn-xs btn-success approve-po-btn" style="margin:2px;display:<?php echo checkPermissions(93); ?>">
                                    Approve
                                    </a>
                                    <a href="../controller/purchase_order_controller.php?status=reject_po&po_id=<?php echo base64_encode($poRow['po_id']);?>" 
                                       class="btn btn-xs btn-danger reject-po-btn" style="margin:2px;display:<?php echo checkPermissions(94); ?>">
                                    Reject
                                    </a>
                                    <?php } elseif($poRow['po_status']==2){?>
                                    <a href="../reports/purchaseorder.php?po_id=<?php echo base64_encode($poRow['po_id']);?>" 
                                       class="btn btn-xs btn-info" style="margin:2px;display:<?php echo checkPermissions(95); ?>" target="_blank">
                                    View
                                    </a>
                                    <a href="#" data-toggle="modal" onclick="getSupplierInvoice(<?php echo $poRow['po_id'];?>)" data-target="#add_supplier_invoice" 
                                       class="btn btn-xs btn-primary" style="margin:2px;display:<?php echo checkPermissions(96); ?>">
                                    Add Supplier Invoice
                                    </a>
                                    <?php } ?>
                                </td>
                            </tr>
                            <?php }?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</body>
<div class="modal fade" id="add_supplier_invoice" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form id="addSupplierInvoiceForm" action="../controller/purchase_order_controller.php?status=add_supplier_invoice" method="post" enctype="multipart/form-data">
                <div class="modal-header"><b><h4>Add Supplier Invoice</h4></b></div>
            <div class="modal-body">
                <div id="display_data">
                </div>
            </div>
            <div class="modal-footer">
                <input type="submit" id="addSupplierInvoiceBtn" class="btn btn-success" value="Submit"/>
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
            </form>
        </div>
    </div>
</div>
<div class="modal fade" id="addSupplierInvoiceConfirmationModal" tabindex="-1" role="dialog" aria-labelledby="modalLabel">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="modalLabel">Confirm Action</h4>
            </div>
            <div class="modal-body">
                Are you sure you want to proceed?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="addSupplierInvoiceConfirmActionBtn">Confirm</button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="approvePOConfirmationModal" tabindex="-1" role="dialog" aria-labelledby="modalLabel">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="modalLabel">Confirm Action</h4>
            </div>
            <div class="modal-body">
                Are you sure you want to approve this purchase order?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="approvePOConfirmActionBtn">Confirm</button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="rejectPOConfirmationModal" tabindex="-1" role="dialog" aria-labelledby="modalLabel">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="modalLabel">Confirm Action</h4>
            </div>
            <div class="modal-body">
                Are you sure you want to reject this purchase order?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="rejectPOConfirmActionBtn">Confirm</button>
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

        $("#pending_po").DataTable();


        //Add SUpplier Invoice Confirmation Modal
        $("#addSupplierInvoiceBtn").click(function(e){

            e.preventDefault();
            $("#add_supplier_invoice").modal("hide");
            $("#addSupplierInvoiceConfirmationModal").modal("show");
        });

        $("#addSupplierInvoiceConfirmActionBtn").click(function(){

            $("#addSupplierInvoiceForm").submit();
        });

        //Approve PO Confirmation Modal
        $("#pending_po").on("click", ".approve-po-btn", function(event) {

            event.preventDefault();

            var approveUrl = $(this).attr("href");

            $("#approvePOConfirmationModal").modal("show");

            $("#approvePOConfirmActionBtn").off("click").on("click", function() {
                window.location.href = approveUrl;
            });
        });

        //Reject PO Confirmation Modal
        $("#pending_po").on("click", ".reject-po-btn", function(event) {

            event.preventDefault();

            var rejectUrl = $(this).attr("href");

            $("#rejectPOConfirmationModal").modal("show");

            $("#rejectPOConfirmActionBtn").off("click").on("click", function() {
                window.location.href = rejectUrl;
            });
        });


    });
    
    function getSupplierInvoice(poId){
        var url ="../controller/purchase_order_controller.php?status=get_supplier_invoice";

        $.post(url,{poId:poId},function(data){
            $("#display_data").html(data).show();
        });
    }
</script>
</html>