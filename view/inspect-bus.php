<?php

include_once '../commons/session.php';
include_once '../model/inspection_model.php';
include_once '../model/bus_model.php';

//get user information from session
$userSession=$_SESSION["user"];

$inspectionId = base64_decode($_GET['inspection_id']);

$inspectionObj = new Inspection();
$inspectionResult = $inspectionObj->getInspection($inspectionId);
$inspectionRow = $inspectionResult->fetch_assoc();

$busObj = new Bus();
$categoryId = $inspectionRow['category_id'];
$categoryResult = $busObj->getBusCategory($categoryId);
$categoryRow = $categoryResult->fetch_assoc();

$templateResult = $inspectionObj->getInspectionChecklistTemplateByBusCategoryId($categoryId);
$templateRow = $templateResult->fetch_assoc();
$templateId = $templateRow['template_id'];

$checklistItemIdsResult = $inspectionObj->getChecklistItemsInATemplate($templateId);
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
        <?php $pageName="Bus Maintenance - Inspect Bus" ?>
        <?php include_once "../includes/header_row_includes.php";?>
        <div class="col-md-3">
            <?php include_once "../includes/bus_maintenance_functions.php"; ?>
        </div>
        <form id="inspectionForm" action="../controller/inspection_controller.php?status=perform_inspection" method="post" enctype="multipart/form-data">
            <div class="col-md-9">
                <div class="row">
                    <div class="col-md-6 col-md-offset-3" id="msg" style="text-align:center;">
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
                    <div class="panel panel-info">
                        <div class="panel-heading"><h4>Bus & Tour Information</h4></div>
                        <div class="panel-body">
                            <div class="col-md-3" style="margin-bottom: 10px">
                                <span class="fa-solid fa-bus"></span>&nbsp;<b>Vehicle Number</b>
                                </br>
                                <span><?php echo $inspectionRow['vehicle_no'];?> </span>
                            </div>
                            <div class="col-md-3" style="margin-bottom: 10px">
                                <span class="fa-solid fa-bus"></span>&nbsp;<b>Bus Category</b>
                                </br>
                                <span><?php echo $categoryRow['category_name'];?> </span>
                            </div>
                            <div class="col-md-3" style="margin-bottom: 10px">
                                <span class="fa-solid fa-bus"></span>&nbsp;<b>Tour Destination</b>
                                </br>
                                <span><?php echo $inspectionRow['destination'];?> </span>
                            </div>
                            <div class="col-md-3" style="margin-bottom: 10px">
                                <span class="fa-solid fa-bus"></span>&nbsp;<b>Tour Start Date</b>
                                </br>
                                <span><?php echo $inspectionRow['start_date'];?> </span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    &nbsp;
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Checklist Item</th>
                                    <th>Description</th>
                                    <th>Status</th>
                                    <th>Comments</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while($checklistItemIdRow = $checklistItemIdsResult->fetch_assoc()){
                                    
                                        $checklistItemId = $checklistItemIdRow["checklist_item_id"];
                                        $checklistItemResult = $inspectionObj->getChecklistItem($checklistItemId);
                                        $checklistItemRow = $checklistItemResult->fetch_assoc();
                                    ?>
                                <tr>
                                    <td><?php echo $checklistItemRow['checklist_item_name']; ?></td>
                                    <td><?php echo $checklistItemRow['checklist_item_description']; ?></td>
                                    <td style="white-space: nowrap">
                                        
                                        <label class="radio-inline">
                                            <input type="radio" 
                                                   name="item_status[<?php echo $checklistItemId; ?>]" 
                                                   value="1" 
                                                   >
                                            Pass
                                        </label>
                                        <label class="radio-inline">
                                            <input type="radio" 
                                                   name="item_status[<?php echo $checklistItemId; ?>]" 
                                                   value="0">
                                            Fail
                                        </label>
                                    </td>
                                    <td>
                                        <input type="text" 
                                               name="item_comments[<?php echo $checklistItemId; ?>]" 
                                               class="form-control" 
                                               placeholder="Brief Comment">
                                    </td>
                                </tr>
                                <?php }?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="row">
                    &nbsp;
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <b>Overall Result</b>
                        </br>
                        </br>
                        <select name="overall_result" id="overall_result" class="form-control">
                            <option value="">-- Select Final Result --</option>
                            <option value="1">Pass</option>
                            <option value="0">Fail</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <b>Final Comments</b>
                        </br>
                        </br>
                        <textarea name="final_comments" id="final_comments" class="form-control" rows="3" placeholder="Enter a summary of the inspection..."></textarea>  
                    </div>
                </div>
                <div class="row">
                    <input type="hidden" value="<?php echo $inspectionId;?>" name="inspection_id"/>
                    <input type="hidden" value="<?php echo $templateId;?>" name="template_id"/>
                    <input type="hidden" value="<?php echo $inspectionRow['bus_id'];?>" name="bus_id"/>
                    <input type="hidden" value="<?php echo $inspectionRow['tour_id'];?>" name="tour_id"/>
                </div>
                <div class="row">
                    &nbsp;
                </div>
                <div class="row">
                    &nbsp;
                </div>
                <div class="row">
                    <div class="col-md-offset-4 col-md-4">
                        <input type="submit" class="btn btn-primary" value="Submit" />
                        <input type="reset" class="btn btn-danger" value="Reset" />
                    </div>
                </div>
                <div class="row" style="height:150px">
                    &nbsp;
                </div>
            </div>
        </form>
    </div>
</body>
<div class="modal fade" id="confirmationModal" tabindex="-1" role="dialog" aria-labelledby="modalLabel">
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
                <button type="button" class="btn btn-primary" id="confirmActionBtn">Confirm</button>
            </div>
        </div>
    </div>
</div>
<script src="../js/datatable/jquery-3.5.1.js"></script>
<script src="../js/datatable/jquery.dataTables.min.js"></script>
<script src="../js/datatable/dataTables.bootstrap.min.js"></script>
<script src="../bootstrap/js/bootstrap.min.js"></script>
<script>
    $(document).ready(function () {

        $("#inspectionForm").on("submit", function(event) {
            // Prevent the default form submission
            event.preventDefault();

            // Validate That All Items Have a Status Selected
            let allItemsChecked = true;

            $("input[name^='item_status']").each(function() {
                if (!$("input[name='" + $(this).attr("name") + "']:checked").length) {
                    allItemsChecked = false;
                    return false; // Break out of the loop
                }
            });

            if (!allItemsChecked) {
                $("#msg").addClass("alert alert-danger");
                $("#msg").html("<b>Please select a status for all checklist items.</b>");
                return false; // Stop form submission
            }

            //If Fail is selected for any item, ensure comments are provided
            let failWithoutComment = false;
           
            $("input[name^='item_status'][value='0']:checked").each(function() {
                // Check the comment in the same row
                if (!$(this).closest("tr").find("input[name^='item_comments']").val().trim()) {
                    failWithoutComment = true;
                    return false; // Break out of the loop
                }
            });

            if (failWithoutComment) {
                $("#msg").addClass("alert alert-danger");
                $("#msg").html("<b>Please provide comments for all failed checklist items.</b>");
                return false; // Stop form submission
            }

            var overallResult = $("#overall_result").val();
            var finalComments = $("#final_comments").val().trim();

            if (overallResult == "") {
                $("#msg").addClass("alert alert-danger");
                $("#msg").html("<b>Please select an overall result.</b>");
                return false; // Stop form submission
            }   

            if (finalComments == "") {
                $("#msg").addClass("alert alert-danger");
                $("#msg").html("<b>Please provide final comments.</b>");
                return false; // Stop form submission
            }

            // If all validation passes, show the modal.
            $("#confirmationModal").modal('show');
            
            //set up the confirmation button to perform the actual submission.
            $("#confirmActionBtn").off("click").on("click", function() {
                // To avoid this validation logic from running again in a loop,
                // Remove the handler and then trigger the native form submission.
                $("#inspectionForm").off("submit").submit();
            });
        });

        $("#inspectionForm").on("reset", function() {
            // Clear the message area when the form is reset
            $("#msg").removeClass("alert alert-danger alert-success");
            $("#msg").html("");
        });
    });

</script>
</html>