<?php

include_once '../commons/session.php';
include_once '../model/inspection_model.php';

//get user information from session
$userSession=$_SESSION["user"];

$inspectionObj = new Inspection();
$inspectionResult = $inspectionObj->getAllChecklistItems();
?>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bus Maintenance</title>
    <link rel="stylesheet" type="text/css" href="../css/dataTables.bootstrap.min.css"/>
    <?php include_once "../includes/bootstrap_css_includes.php"?>
</head>
<body>
    <div class="container">
        <?php $pageName="Bus Maintenance - Manage Checklist Items" ?>
        <?php include_once "../includes/header_row_includes.php";?>
        <div class="col-md-3">
            <?php include_once "../includes/bus_maintenance_functions.php"; ?>
        </div>
        <div class="col-md-9">
            <div class="row">
                <div class="col-md-6 col-md-offset-3" id="msg" style="text-align:center">
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
                <form action="../controller/inspection_controller.php?status=register_checklist_item" method="post" enctype="multipart/form-data">
                    <div class="panel panel-success">
                        <div class="panel-heading"><h4 style="margin:0px">Register Checklist Items</h4></div>
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-md-2">
                                    <label class="control-label">Item Name</label>
                                </div>
                                <div class="col-md-4">
                                    <input type="text" class="form-control" name="checklist_item_name" id="checklist_item_name"/>
                                </div>
                                <div class="col-md-2">
                                    <label class="control-label">Item Description</label>
                                </div>
                                <div class="col-md-4">
                                    <textarea id="checklist_item_description" name="checklist_item_description" rows="2" class="form-control"></textarea>
                                </div>
                            </div>
                            <div class="row">
                                &nbsp;
                            </div>
                            <div class="row">
                                <div class="col-md-offset-3 col-md-6">
                                    <input type="submit" class="btn btn-primary" value="Register" style="width:120px;display:<?php echo checkPermissions(126); ?>"/>
                                    <input type="reset" class="btn btn-danger" value="Reset" style="width:120px"/>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="row">
                &nbsp;
            </div>
            <div class="row">
                <div class="col-md-12">
                    <label class="control-label"><h4>Edit & Remove Checklist Items</h4></label>
                </div>
            </div>
            <div class="row">
                &nbsp;
            </div>
            <div class="row">
                <table class="table" id="checklist_items">
                    <thead>
                        <tr>
                            <th>Item Name</th>
                            <th>Item Description</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($inspectionRow = $inspectionResult->fetch_assoc()){
                            
                            $status = match((int)$inspectionRow['checklist_item_status']){
                                
                                -1=>"Removed",
                                1=>"Active",
                                
                            };
                            
                            ?>
                        <tr>
                            <td><?php echo $inspectionRow['checklist_item_name'];?></td>
                            <td><?php echo $inspectionRow['checklist_item_description'];?></td>
                            <td><?php echo $status;?></td>
                            <td>
                                <a href="#" data-toggle="modal" data-target="#edit_checklist_item" onclick="getChecklistItemEditInfo(<?php echo $inspectionRow['checklist_item_id'];?>)" 
                                   class="btn btn-xs btn-warning" style="margin:2px;display:<?php echo checkPermissions(127); ?>">
                                    <span class="glyphicon glyphicon-pencil"></span>
                                    Edit
                                </a>
                                <a href="../controller/inspection_controller.php?status=remove_checklist_item&checklist_item_id=<?php echo base64_encode($inspectionRow['checklist_item_id'])?>" 
                                   class="btn btn-xs btn-danger remove-checklist-btn" style="margin:2px;display:<?php echo checkPermissions(128); ?>">
                                    <span class="glyphicon glyphicon-trash"></span>
                                    Remove
                                </a>
                            </td>
                        </tr>
                        <?php }?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
<div class="modal fade" id="edit_checklist_item" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="../controller/inspection_controller.php?status=edit_checklist_item" method="post" enctype="multipart/form-data">
                <div class="modal-header"><b><h4>Edit Checklist Item</h4></b></div>
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
<div class="modal fade" id="confirmationModal" tabindex="-1" role="dialog" aria-labelledby="modalLabel">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="modalLabel">Confirm Action</h4>
            </div>
            <div class="modal-body">
                Are you sure you want to remove the checklist item?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">No</button>
                <button type="button" class="btn btn-primary" id="confirmActionBtn">Yes, Confirm</button>
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

        $("#checklist_items").DataTable();

        $("form").submit(function(){

            var checklistItemName = $("#checklist_item_name").val();
            var checklistItemDescription = $("#checklist_item_description").val();

            if(checklistItemName.trim() == ""){
                $("#msg").html("<b>Checklist Item Name Cannot Be Empty!</b>");
                $("#msg").addClass("alert alert-danger");
                return false;
            }

            if(checklistItemDescription.trim() == ""){
                $("#msg").html("<b>Checklist Item Description Cannot Be Empty!</b>");
                $("#msg").addClass("alert alert-danger");
                return false;
            }
        });

        $("#checklist_items").on("click", ".remove-checklist-btn", function(event) {
            event.preventDefault(); // Prevent the default link behavior
            var removeURL = $(this).attr("href");
            $("#confirmationModal").modal("show");
            $("#confirmActionBtn").off("click").on("click", function () {
                window.location.href = removeURL;
            });
        });

    });
    
    function getChecklistItemEditInfo(checklistItemId){
        var url ="../controller/inspection_controller.php?status=get_checklist_item_edit_info";

        $.post(url,{checklistItemId:checklistItemId},function(data){
            $("#display_data").html(data).show();
        });
    }
</script>
</html>