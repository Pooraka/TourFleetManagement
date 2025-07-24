<?php

include_once '../commons/session.php';
include_once '../model/inspection_model.php';


//get user information from session
$userSession=$_SESSION["user"];

$dateFrom = "";
$dateTo = "";

$inspectionObj = new Inspection();
$inspectionFailedResult = $inspectionObj->getFailedInspectionsToAssignNewBusUsingFilters($dateFrom,$dateTo);
?>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tour Management</title>
    <link rel="stylesheet" type="text/css" href="../css/dataTables.bootstrap.min.css"/>
    <?php include_once "../includes/bootstrap_css_includes.php"?>
</head>
<body>
    <div class="container">
        <?php $pageName="Tour Management - Failed Inspections" ?>
        <?php include_once "../includes/header_row_includes.php";?>
        <div class="col-md-3">
            <?php include_once "../includes/tour_management_functions.php"; ?>
        </div>
        <div class="col-md-9">
            <div class="row">
                <div class="col-md-6 col-md-offset-3" id="msg">
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
                <div class="col-md-8">
                    <label class="control-label">Select Date Range To Filter By Tour Start Date (Keep Blank for All)</label>
                </div>
                <div class="col-md-4 text-right">
                    <button type="button" class="btn btn-success" id="filter_button">Filter</button>
                </div>
            </div>
            <div class="row">
                &nbsp;
            </div>
            <div class="row">
                <div class="col-md-3">
                    <label class="control-label">From Date</label>
                </div>
                <div class="col-md-3">
                    <input type="date" class="form-control" id="dateFrom" name="dateFrom" max="<?php echo date("Y-m-d"); ?>"/>
                </div>
                <div class="col-md-3">
                    <label class="control-label">To Date</label>
                </div>
                <div class="col-md-3">
                    <input type="date" class="form-control" id="dateTo" name="dateTo" max="<?php echo date("Y-m-d"); ?>"/>
                </div>
            </div>
            <div class="row">
                &nbsp;
            </div>
            <div class="row">
                <div class="col-md-12">
                    <table class="table" id="inspection_failed_table" style="width:100%">
                        <thead>
                            <tr>
                                <th>Inspection Id</th>
                                <th>Tour Destination</th>
                                <th>Tour Date</th>
                                <th>Vehicle No</th>
                                <th>Inspection Comment</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id="inspection_failed_table_body">
                            <?php while($inspectionFailedRow = $inspectionFailedResult->fetch_assoc()){ ?>
                            <tr>
                                <td><?php echo $inspectionFailedRow["inspection_id"];?></td>
                                <td><?php echo $inspectionFailedRow["destination"];?></td>
                                <td style="white-space: nowrap"><?php echo $inspectionFailedRow["start_date"];?></td>
                                <td><?php echo $inspectionFailedRow["vehicle_no"];?></td>
                                <td><?php echo $inspectionFailedRow["final_comments"];?></td>
                                <td>
                                    <a href="reassign-bus.php?inspection_id=<?php echo base64_encode($inspectionFailedRow['inspection_id'])?>" 
                                       class="btn btn-xs btn-success" style="margin:2px;display:<?php echo checkPermissions(88); ?>">
<!--                                        <span class="glyphicon glyphicon-ok"></span>-->
                                        Assign A </br>Different Bus
                                    </a>
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
<script src="../js/datatable/jquery-3.5.1.js"></script>
<script src="../js/datatable/jquery.dataTables.min.js"></script>
<script src="../js/datatable/dataTables.bootstrap.min.js"></script>
<script src="../bootstrap/js/bootstrap.min.js"></script>
<script>
    $(document).ready(function(){

        var dataTableOptions = {
            
            "pageLength": 2,
            "order": [
                [ 1, "asc" ] 
            ],
             "scrollX": true
        };
        
        var table = $("#inspection_failed_table").DataTable(dataTableOptions);
        
        $('#filter_button').on('click', function(){

            $("#msg").html("");
            $("#msg").removeClass("alert alert-danger");
            
            var dateFrom = $("#dateFrom").val();
            var dateTo = $("#dateTo").val();

            if(dateFrom!="" || dateTo!=""){

                if(dateFrom ==""){
                    $("#msg").html("Both Dates Must Be Selected To Get The Report For A Period");
                    $("#msg").addClass("alert alert-danger");
                    return false;
                }
                if(dateTo ==""){
                    $("#msg").html("Both Dates Must Be Selected To Get The Report For A Period");
                    $("#msg").addClass("alert alert-danger");
                    return false;
                }
                
                if(dateFrom>dateTo){
                    $("#msg").html("'From' Date Cannot Be Greater Than 'To' Date");
                    $("#msg").addClass("alert alert-danger");
                    return false;
                }

            }
            
            var url = "../controller/inspection_controller.php?status=inspection_failed_list_filtered";

            $.post(url, {dateFrom:dateFrom, dateTo:dateTo}, function (data) {

                // Destroy the old DataTable instance.
                table.destroy();

                // Update the table body with the new filtered data.
                $("#inspection_failed_table_body").html(data);

                // Re-initialize the DataTable with the new content.
                table = $("#inspection_failed_table").DataTable(dataTableOptions);
            });
        });

    });
</script>
</html>