<?php

include_once '../commons/session.php';
include_once '../model/sparepart_model.php';
include_once '../model/tender_model.php';


//get user information from session
$userSession=$_SESSION["user"];

$sparePartObj = new SparePart();
$sparePartResult = $sparePartObj->getAllSparePartsIncludingRemoved();

$tenderObj = new Tender();

$tenderStatus = "";
$partId = "";

$tenderResult = $tenderObj->getOpenTendersFiltered($tenderStatus,$partId);

?>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tender</title>
    <link rel="stylesheet" type="text/css" href="../css/dataTables.bootstrap.min.css"/>
    <?php include_once "../includes/bootstrap_css_includes.php"?>
</head>
<body>
    <div class="container">
        <?php $pageName="Tender Management - Pending Tenders" ?>
        <?php include_once "../includes/header_row_includes.php";?>
        <div class="col-md-3">
            <?php include_once "../includes/tender_functions.php"; ?>
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
                <div class="col-md-2">
                    <label class="control-label">Select Tender Status</label>
                </div>
                <div class="col-md-3">
                    <select class="form-control" id="tenderStatus" name="tenderStatus">
                        <option value="">All</option>
                        <option value="1">Open</option>
                        <option value="2">Closed</option>                     
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="control-label">Select Spare Part</label>
                </div>
                <div class="col-md-3">
                    <select class="form-control" id="sparePart" name="sparePart">
                        <option value="">All</option>
                        <?php while($sparePartRow = $sparePartResult->fetch_assoc()) { ?>
                            <option value="<?php echo $sparePartRow["part_id"];?>"><?php echo $sparePartRow["part_name"];?></option>
                        <?php } ?>
                    </select>
                </div>
                <div class="col-md-2 text-right">
                    <button type="button" class="btn btn-success" id="filter_button">Filter</button>
                </div>
            </div>
            <div class="row">
                &nbsp;
            </div>
            <div class="row">
                <div class="col-md-12">
                    <table class="table" id="open_tenders">
                        <thead>
                            <tr>
                                <th style="white-space: wrap">Tender ID</th>
                                <th>Spare Part</th>
                                <th>Quantity</th>
                                <th>Description</th>
                                <th>Open Date</th>
                                <th>Close Date</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id="open_tenders_body">
                            <?php while($tenderRow = $tenderResult->fetch_assoc()){ 
                                
                                $statusDisplay = match((int)$tenderRow["tender_status"]){
                                    
                                    1=>"Open",
                                    2=>"Closed"
                                };
                                
                                $statusClass = match((int)$tenderRow["tender_status"]){
                                    
                                    1=>"label label-success",
                                    2=>"label label-warning"
                                };
                                
                                ?>
                            <tr>
                                <td style="white-space: wrap"><?php echo $tenderRow["tender_id"];?></td>
                                <td><?php echo $tenderRow["part_name"];?></td>
                                <td><?php echo $tenderRow["quantity_required"];?></td>
                                <td><?php echo $tenderRow["tender_description"];?></td>
                                <td style="white-space: nowrap"><?php echo $tenderRow["open_date"];?></td>
                                <td style="white-space: nowrap"><?php echo $tenderRow["close_date"];?></td>
                                <td><span class="<?php echo $statusClass?>"><?php echo $statusDisplay;?></span></td>
                                <td>
                                    <a href="../documents/tenderadvertisements/<?php echo $tenderRow["advertisement_file_name"];?>" 
                                       class="btn btn-xs btn-info" style="margin:2px;display:<?php echo checkPermissions(69); ?>" target="_blank">                                                 
                                        Advertisement
                                    </a>
                                    <?php if($tenderRow["tender_status"]==1){ ?>
                                    <a href="add-bids.php?tender_id=<?php echo base64_encode($tenderRow["tender_id"]);?>" 
                                       class="btn btn-xs btn-success" style="margin:2px;display:<?php echo checkPermissions(70); ?>">
                                        Add Bids
                                    </a>
                                    <?php } ?>
                                    <a href="view-bids.php?tender_id=<?php echo base64_encode($tenderRow["tender_id"]);?>" 
                                       class="btn btn-xs btn-primary" style="margin:2px;display:<?php echo checkPermissions(71); ?>">
                                        View Bids
                                    </a>
                                    <a href="../controller/tender_controller.php?status=cancel_tender&tender_id=<?php echo base64_encode($tenderRow["tender_id"]);?>" 
                                       class="btn btn-xs btn-danger cancel-tender-btn" style="margin:2px;display:<?php echo checkPermissions(151); ?>">                                                 
                                        Cancel
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
    $(document).ready(function() {

        //Cancel Tender Confirmation Modal
        $("#open_tenders").on("click", ".cancel-tender-btn", function(event) {
            event.preventDefault(); // Prevent the default link behavior
            var cancelURL = $(this).attr("href");
            $("#confirmationModal").modal("show");
            $("#confirmActionBtn").off("click").on("click", function () {
                window.location.href = cancelURL;
            });
        });
        
        var dataTableOptions = {
            "pageLength": 5,
            "order": [
                [ 0, "desc" ] //Desc order by tender created date
            ],
             "scrollX": true
        };
        
        var table = $("#open_tenders").DataTable(dataTableOptions); 
        
        
        $('#filter_button').on('click', function(){

            $("#msg").html("");
            $("#msg").removeClass("alert alert-danger");
            
            var tenderStatus = $("#tenderStatus").val();
            var partId = $("#sparePart").val();
            
            var url = "../controller/tender_controller.php?status=open_tenders_filtered";

            $.post(url, {tenderStatus:tenderStatus, partId:partId}, function (data) {

                // Destroy the old DataTable instance.
                table.destroy();

                // Update the table body with the new filtered data.
                $("#open_tenders_body").html(data);

                // Re-initialize the DataTable with the new content.
                table = $("#open_tenders").DataTable(dataTableOptions);
            });
        });
    });
</script>
</html>