<?php

include_once '../commons/session.php';
include_once '../model/sparepart_model.php';
include_once '../model/tender_model.php';


//get user information from session
$userSession=$_SESSION["user"];

$sparePartObj = new SparePart();
$sparePartResult = $sparePartObj->getAllSparePartsIncludingRemoved();

$dateFrom = "";
$dateTo = "";
$tenderStatus = "";
$partId = "";

$tenderObj = new Tender();
$tenderResult = $tenderObj->getPastTendersFiltered($dateFrom, $dateTo, $partId, $tenderStatus);
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
        <?php $pageName="Tender Management - View Past Tenders" ?>
        <?php include_once "../includes/header_row_includes.php";?>
        <div class="col-md-3">
            <ul class="list-group">
                <a href="add-supplier.php" class="list-group-item" style="display:<?php echo checkPermissions(61); ?>">
                    <span class="fa-solid fa-plus"></span> &nbsp;
                    Add Supplier
                </a>
                <a href="view-suppliers.php" class="list-group-item" style="display:<?php echo checkPermissions(62); ?>">
                    <span class="fa-solid fa-truck-field"></span> &nbsp;
                    View Suppliers
                </a>
                <a href="add-tender.php" class="list-group-item" style="display:<?php echo checkPermissions(67); ?>">
                    <span class="fa-solid fa-plus"></span> &nbsp;
                    Add Tender
                </a>
                <a href="open-tenders.php" class="list-group-item" style="display:<?php echo checkPermissions(68); ?>">
                    <span class="fa-solid fa-folder-open"></span> &nbsp;
                    View Open Tenders
                </a>
                <a href="past-tenders.php" class="list-group-item" style="display:<?php echo checkPermissions(164); ?>">
                    <span class="fa-solid fa-scroll"></span> &nbsp;
                    Past Tenders
                </a>
                <a href="tender-status-report.php" class="list-group-item" style="display:<?php echo checkPermissions(150); ?>">
                    <span class="fa-solid fa-file-contract"></span> &nbsp;
                    Tender Status Report
                </a>
            </ul>
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
                    <label class="control-label">Select Tender Placed Date Range To Filter (Keep Blank for All)</label>
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
                <div class="col-md-3">
                    <label class="control-label">Select Tender Status</label>
                </div>
                <div class="col-md-3">
                    <select class="form-control" id="tenderStatus" name="tenderStatus">
                        <option value="">All</option>
                        <option value="3">Bid Awarded</option>
                        <option value="2">Closed By System</option>
                        <option value="-1">Cancelled</option>                     
                    </select>
                </div>
                <div class="col-md-3">
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
            </div>
            <div class="row">
                &nbsp;
            </div>
            <div class="row">
                <div class="col-md-12">
                    <table class="table" id="tender_table">
                        <thead>
                            <tr>
                                <th>Created Date</th>
                                <th>Tender ID</th>
                                <th>Part Name</th>
                                <th>Quantity</th>
                                <th>Open Date</th>
                                <th>Close Date</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id="tender_table_body">
                            <?php while($tenderRow = $tenderResult->fetch_assoc()){
                                
                                $status = match((int)$tenderRow["tender_status"]){
                                    
                                    -1=>"Cancelled",
                                    2=>"Closed By System",
                                    3=>"Bid Awarded",
                                };
                                
                                ?>
                            <tr>
                                <td style="white-space: nowrap"><?php echo date("Y-m-d", strtotime($tenderRow["created_at"])); ?></td>
                                <td><?php echo $tenderRow["tender_id"];?></td>
                                <td><?php echo $tenderRow["part_name"];?></td>
                                <td><?php echo $tenderRow["quantity_required"];?></td>
                                <td style="white-space: nowrap"><?php echo $tenderRow["open_date"];?></td>
                                <td style="white-space: nowrap"><?php echo $tenderRow["close_date"];?></td>
                                <td><?php echo $status;?></td>
                                <td>
                                    <a href="../documents/tenderadvertisements/<?php echo $tenderRow["advertisement_file_name"];?>" 
                                       class="btn btn-xs btn-info" style="margin:2px;display:<?php echo checkPermissions(69); ?>" target="_blank">                                                 
                                        Advertisement
                                    </a>
                                    <?php if($tenderRow["tender_status"]==3){ ?>
                                    <button type="button" id="viewBidsBtn" class="btn btn-xs btn-primary" 
                                            style="margin:2px;display:<?php echo checkPermissions(71); ?>"
                                            onclick="getBidsToView(<?php echo $tenderRow["tender_id"];?>)"
                                            data-toggle="modal" data-target="#viewBidsModal"
                                            >
                                        View Bids
                                    </button>
                                    <?php }?>
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
<div class="modal fade" id="viewBidsModal" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header"><b><h4>View Bids</h4></b></div>
            <div class="modal-body">
                <div id="display_data">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
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
        
        var dataTableOptions = {
            "pageLength": 5,
            "order": [
                [ 0, "desc" ] //Desc order by tender created date
            ],
             "scrollX": true
        };
        
        var table = $("#tender_table").DataTable(dataTableOptions); 
        
        
        $('#filter_button').on('click', function(){

            $("#msg").html("");
            $("#msg").removeClass("alert alert-danger");
            
            var dateFrom = $("#dateFrom").val();
            var dateTo = $("#dateTo").val();
            var tenderStatus = $("#tenderStatus").val();
            var partId = $("#sparePart").val();

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
            
            var url = "../controller/tender_controller.php?status=past_tenders_filtered";

            $.post(url, {dateFrom:dateFrom, dateTo:dateTo, tenderStatus:tenderStatus, partId:partId}, function (data) {

                // Destroy the old DataTable instance.
                table.destroy();

                // Update the table body with the new filtered data.
                $("#tender_table_body").html(data);

                // Re-initialize the DataTable with the new content.
                table = $("#tender_table").DataTable(dataTableOptions);
            });
        });
    
    
        
    });
    
    function getBidsToView(tenderId){

            var url = "../controller/tender_controller.php?status=get_bids_of_past_tender";
            
            $.post(url,{tenderId:tenderId},function(data){
            $("#display_data").html(data).show();
            
            });
            
        }
</script>
</html>