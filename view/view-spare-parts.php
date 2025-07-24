<?php

include_once '../commons/session.php';
include_once '../model/sparepart_model.php';

//get user information from session
$userSession=$_SESSION["user"];

$sparePartObj = new SparePart();

$status ="";

$sparePartResult = $sparePartObj->getSparePartsFiltered($status);
?>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Spare Parts</title>
    <?php include_once "../includes/bootstrap_css_includes.php"?>
</head>
<body>
    <div class="container">
        <?php $pageName="Spare Part Management - View Spare Parts" ?>
        <?php include_once "../includes/header_row_includes.php";?>
        <div class="col-md-3">
            <?php include_once "../includes/spareparts_functions.php"; ?>
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
                <div class="col-md-3">
                    <label class="control-label">Select Status</label>
                </div>
                <div class="col-md-3">
                    <select class="form-control" id="status" name="status">
                        <option value="">All</option>
                        <option value="1">Sufficient Stock</option>
                        <option value="2">Re-order Now</option>
                    </select>
                </div>
                <div class="col-md-6 text-right">
                    <button type="button" class="btn btn-success" id="filter_button">Filter</button>
                </div>
            </div>
            <div class="row">
                &nbsp;
            </div>
            <div class="row">
                <div class="col-md-12">
                    <table class="table" id="partsTable">
                        <thead>
                            <tr>
                                <th>Part Number</th>
                                <th>Name</th>
                                <th>Quantity On Hand</th>
                                <th>Re-order Level</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id="partsTableBody">
                            <?php while($sparePartRow = $sparePartResult->fetch_assoc()){
                                $partId = $sparePartRow['part_id'];
                                ?>
                            <tr title='<?php echo htmlspecialchars($sparePartRow['description']);?>' data-toggle="tooltip">
                                <td><?php echo $sparePartRow['part_number'];?></td>
                                <td><?php echo $sparePartRow['part_name'];?></td>
                                <td><?php echo $sparePartRow['quantity_on_hand'];?></td>
                                <td><?php echo $sparePartRow['reorder_level'];?></td>
                                <td>
                                    <?php if($sparePartRow['quantity_on_hand'] >0){ ?>
                                    <a href="issue-spare-parts.php?part_id=<?php echo base64_encode($partId); ?>"  
                                       class="btn btn-xs btn-success" style="margin:2px;display:<?php echo checkPermissions(104); ?>"
                                       title="">
                                        <span class="glyphicon glyphicon-plus"></span>
                                        Issue To Bus
                                    </a>
                                    <a href="#" data-toggle="modal" onclick="removeSpareParts(<?php echo $partId;?>)" data-target="#add_remove_info" 
                                       class="btn btn-xs btn-danger" style="margin:2px;display:<?php echo checkPermissions(105); ?>"
                                       title="">
                                        <span class="glyphicon glyphicon-remove"></span>
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

        var dataTableOptions = {
            "pageLength": 5,
            
            "scrollX": true,
            "drawCallback": function(settings) {
                var api = this.api();
                var rows = api.rows({ page: 'current' }).nodes();

                var tooltips = $(rows).filter('[data-toggle="tooltip"]');

                $(tooltips).tooltip({
                    container: 'body',
                    placement: 'right'
                });
            }
        };
        
        var table = $("#partsTable").DataTable(dataTableOptions);

        $('#filter_button').on('click', function(){

            $("#msg").html("");
            $("#msg").removeClass("alert alert-danger");
            
            var status = $("#status").val();

            var url = "../controller/sparepart_controller.php?status=sparepart_list_filtered";

            $.post(url, {status:status}, function (data) {

                // Destroy the old DataTable instance.
                table.destroy();

                // Update the table body with the new filtered data.
                $("#partsTableBody").html(data);

                // Re-initialize the DataTable with the new content.
                table = $("#partsTable").DataTable(dataTableOptions);
            });
        });

    });
    
    function removeSpareParts(partId){
        var url ="../controller/sparepart_controller.php?status=get_spare_part_remove_interface";

        $.post(url,{partId:partId},function(data){
            $("#display_data").html(data).show();
        });
    }
</script>
</html>