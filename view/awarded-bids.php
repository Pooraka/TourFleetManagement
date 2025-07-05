<?php

include_once '../commons/session.php';
include_once '../model/bid_model.php';
include_once '../model/tender_model.php';



//get user information from session
$userSession=$_SESSION["user"];

$bidObj = new Bid();
$bidResult = $bidObj->getAwardedBids();

$tenderObj = new Tender();
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
        <?php $pageName="Purchasing - Awarded Bids" ?>
        <?php include_once "../includes/header_row_includes.php";?>
        <div class="col-md-3">
            <ul class="list-group">
                <a href="awarded-bids.php" class="list-group-item">
                    <span class="glyphicon glyphicon-plus"></span> &nbsp;
                    Awarded Bids
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
                    <table class="table" id="awarded_bids">
                        <thead>
                            <tr>
                                <th style="white-space:wrap">Bid ID</th>
                                <th style="white-space:wrap">Tender ID</th>
                                <th>Spare Part</th>
                                <th>Unit Price</th>
                                <th>Supplier</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while($bidRow = $bidResult->fetch_assoc()){ 
                                
                                $tenderId = $bidRow['tender_id'];
                                $tenderResult = $tenderObj->getTender($tenderId);
                                $tenderRow = $tenderResult->fetch_assoc();
                                
                                ?>
                            <tr>
                                <td><?php echo $bidRow['bid_id'];?></td>
                                <td><?php echo $tenderId;?></td>
                                <td style="white-space:nowrap"><?php echo $tenderRow['part_number'];?></td>
                                <td><?php echo number_format($bidRow['unit_price'],2);?></td>
                                <td><?php echo $bidRow['supplier_name'];?></td>
                                <td>
                                    <a href="../controller/purchase_order_controller.php?status=generate_po&tender_id=<?php echo base64_encode($tenderId);?>" class="btn btn-xs btn-success" style="margin:2px">                                                 
                                        <span class="glyphicon glyphicon-ok"></span>
                                        Generate PO
                                    </a>
                                    <a href="../controller/bid_controller.php?status=revoke_award&tender_id=<?php echo base64_encode($tenderId);?>&bid_id=<?php echo base64_encode($bidRow['bid_id']) ?>" class="btn btn-xs btn-danger" style="margin:2px">                                                 
                                        <span class="glyphicon glyphicon-remove"></span>
                                        Revoke Award
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

        $("#awarded_bids").DataTable();
    });
</script>
</html>