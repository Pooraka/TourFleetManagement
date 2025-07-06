<?php

include_once '../commons/session.php';
include_once '../model/grn_model.php';
include_once '../model/purchase_order_model.php';
include_once '../model/bid_model.php';

//get user information from session
$userSession=$_SESSION["user"];

$grnObj = new GRN();
$grnResult = $grnObj->getGRNs();

$poObj = new PurchaseOrder();
$bidObj = new Bid();
?>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Spare Parts</title>
    <link rel="stylesheet" type="text/css" href="../css/dataTables.bootstrap.min.css"/>
    <?php include_once "../includes/bootstrap_css_includes.php"?>
</head>
<body>
    <div class="container">
        <?php $pageName="Spare Part Management - View GRNs" ?>
        <?php include_once "../includes/header_row_includes.php";?>
        <div class="col-md-3">
            <ul class="list-group">
                <a href="register-spareparts.php" class="list-group-item">
                    <span class="glyphicon glyphicon-search"></span> &nbsp;
                    Register Spare Parts
                </a>
                <a href="spare-part-types.php" class="list-group-item">
                    <span class="glyphicon glyphicon-search"></span> &nbsp;
                    View Spare Part Types
                </a>
                <a href="add-spare-parts.php" class="list-group-item">
                    <span class="glyphicon glyphicon-search"></span> &nbsp;
                    Add Spare Parts
                </a>
                <a href="view-grns.php" class="list-group-item">
                    <span class="glyphicon glyphicon-search"></span> &nbsp;
                    View GRNs
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
                    <table class="table" id="grn_table" style="font-size:15px">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>GRN Number</th>
                                <th>PO Number</th>
                                <th>Supplier</th>
                                <th>Part Name</th>
                                <th>Ordered</th>
                                <th>Received</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php while($grnRow = $grnResult->fetch_assoc()){
                            
                            $poId = $grnRow['po_id'];
                            $poResult = $poObj->getPO($poId);
                            $poRow = $poResult->fetch_assoc();
                            
                            $bidId = $poRow['bid_id'];
                            $bidResult = $bidObj->getBid($bidId);
                            $bidRow = $bidResult->fetch_assoc();
                            
                            ?>
                            <tr>
                                <td style="white-space:nowrap"><?php echo $grnRow['grn_received_date'];?></td>
                                <td><?php echo $grnRow['grn_number'];?></td>
                                <td><?php echo $poRow['po_number'];?></td>
                                <td><?php echo $bidRow['supplier_name'];?></td>
                                <td><?php echo $poRow['part_name'];?></td>
                                <td><?php echo $poRow['quantity_ordered'];?></td>
                                <td><?php echo $grnRow['grn_quantity_received'];?></td>
                                <td>
                                    <a href="../reports/grn.php?grn_id=<?php echo base64_encode($grnRow['grn_id']); ?>" target="_blank" class="btn btn-info" style="margin:2px">
                                        <span class="glyphicon glyphicon-search"></span>                                                  
                                        View
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

        $("#grn_table").DataTable();
    });
</script>
</html>