<?php

include_once '../commons/session.php';
include_once '../model/inspection_model.php';

//get user information from session
$userSession=$_SESSION["user"];

$inspectionObj = new Inspection();
$templateResult = $inspectionObj->getInspectionChecklistTemplates();
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
        <?php $pageName="Bus Maintenance - Manage Checklist Template" ?>
        <?php include_once "../includes/header_row_includes.php";?>
        <div class="col-md-3">
            <?php include_once "../includes/bus_maintenance_functions.php"; ?>
        </div>
        <form action="../controller/inspection_controller.php?status=update_template" method="post" enctype="multipart/form-data">
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
                <div class="col-md-6">
                    <label class="control-label">Select Checklist Template</label>
                </div>
                <div class="col-md-6">
                    <select name="template_id" id="template_id" class="form-control">
                        <option value="">------------------</option>
                        <?php
                            while($templateRow=$templateResult->fetch_assoc()){
                                    ?>
                            <option value="<?php echo $templateRow['template_id'];?>">
                                <?php echo $templateRow['template_name'];?>
                            </option>
                            <?php
                            }
                        ?>
                    </select>
                </div>
            </div>
            <div class="row">
                &nbsp;
            </div>
            <div id="display_data">
                <div class="row">
                    <div class="col-md-offset-4 col-md-4">
                        <span>Select The Template</span>
                    </div>
                </div>
            </div>
        </div>
        </form>
    </div>
</body>
<script src="../js/datatable/jquery-3.5.1.js"></script>
<script src="../js/datatable/jquery.dataTables.min.js"></script>
<script src="../js/datatable/dataTables.bootstrap.min.js"></script>
<script src="../bootstrap/js/bootstrap.min.js"></script>
<script>
    function selectAllItems() {
        $("#checklist_items input[type='checkbox']").prop('checked', true);
    }

    function selectNoneItems() {
        $("#checklist_items input[type='checkbox']").prop('checked', false);
    }
    
    // This function for the reset button
    function resetItems() {
        // This re-triggers the dropdown's change event
        $("#template_id").trigger("change");
    }
    
    $(document).ready(function(){

        $("#template_id").change(function () {
            
            var templateId = $("#template_id").val();
            
            if(templateId===""){
                $("#display_data").html(
                    `<div class="row">
                        <div class="col-md-offset-4 col-md-4">
                            <span>Select The Template</span>
                        </div>
                    </div>`
                );
            }
            else{
                
                var url = "../controller/inspection_controller.php?status=load_template_and_checklist";

                $.post(url, {templateId: templateId}, function (data) {

                    $("#display_data").html(data);
                    
                    //$("#checklist_items").DataTable();

                }); 
            }

        });

    });
</script>
</html>