<?php

include_once '../commons/session.php';
include_once '../model/sparepart_model.php';

//get user information from session
$userSession=$_SESSION["user"];

$sparePartObj = new SparePart();
$sparePartResult = $sparePartObj->getSpareParts();
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
        <?php $pageName="Spare Part Management - View Spare Parts" ?>
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
                <a href="view-spare-parts.php" class="list-group-item">
                    <span class="glyphicon glyphicon-search"></span> &nbsp;
                    View Spare Parts
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
                    <table class="table" id="spare_parts">
                        <thead>
                            <tr>
                                <th>Part Number</th>
                                <th>Name</th>
                                <th>Quantity On Hand</th>
                                <th>Re-order Level</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while($sparePartRow = $sparePartResult->fetch_assoc()){
                                $partId = $sparePartRow['part_id'];
                                ?>
                            <tr>
                                <td><?php echo $sparePartRow['part_number'];?></td>
                                <td><?php echo $sparePartRow['part_name'];?></td>
                                <td><?php echo $sparePartRow['quantity_on_hand'];?></td>
                                <td><?php echo $sparePartRow['reorder_level'];?></td>
                                <td>
                                    <a href="issue-spare-parts.php?part_id=<?php echo base64_encode($partId); ?>"  class="btn btn-xs btn-success" style="margin:2px">
                                        <span class="glyphicon glyphicon-plus"></span>
                                        Issue To Bus
                                    </a> 
                                    <a href="#" data-toggle="modal" onclick="removeSpareParts(<?php echo $partId;?>)" data-target="#add_remove_info" class="btn btn-xs btn-danger" style="margin:2px">
                                        <span class="glyphicon glyphicon-remove"></span>
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
<div class="modal fade" id="add_remove_info" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="../controller/sparepart_controller.php?status=remove_sparepart" method="post" enctype="multipart/form-data">
                <div class="modal-header"><b><h4>Remove Spare Parts</h4></b></div>
            <div class="modal-body">
                <div id="display_data">
                </div>
            </div>
            <div class="modal-footer">
                <input type="submit" class="btn btn-success" value="Submit"/>
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
            </form>
        </div>
    </div>
</div>
<script src="../js/datatable/jquery-3.5.1.js"></script>
<script src="../js/datatable/jquery.dataTables.min.js"></script>
<script src="../js/datatable/dataTables.bootstrap.min.js"></script>
<script src="../bootstrap/js/bootstrap.min.js"></script>
<script>
    $(document).ready(function(){

        $("#spare_parts").DataTable();

    });
    
    function removeSpareParts(partId){
        var url ="../controller/sparepart_controller.php?status=get_spare_part_remove_interface";

        $.post(url,{partId:partId},function(data){
            $("#display_data").html(data).show();
        });
    }
</script>
</html>