<?php

include_once '../commons/session.php';
include_once '../model/sparepart_model.php';
include_once '../model/purchase_order_model.php';
include_once '../model/supplier_model.php';


//get user information from session
$userSession=$_SESSION["user"];

$sparePartObj = new SparePart();
$sparePartResult = $sparePartObj->getAllSparePartsIncludingRemoved();

$dateFrom = "";
$dateTo = "";
$partId = "";
$poStatus = "";

$poObj = new PurchaseOrder();
$poResult = $poObj->getPastPOsFiltered($dateFrom,$dateTo,$partId,$poStatus);

$supplierObj = new Supplier();
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
        <?php $pageName="Purchasing - Past Purchase Orders" ?>
        <?php include_once "../includes/header_row_includes.php";?>
        <div class="col-md-3">
            <ul class="list-group">
                <a href="awarded-bids.php" class="list-group-item" style="display:<?php echo checkPermissions(89); ?>">
                    <span class="fa-solid fa-gavel"></span> &nbsp;
                    View Awarded Bids
                </a>
                <a href="pending-purchase-orders.php" class="list-group-item" style="display:<?php echo checkPermissions(92); ?>">
                    <span class="fa-solid fa-file-import"></span> &nbsp;
                    View Pending PO
                </a>
                <a href="past-purchase-orders.php" class="list-group-item" style="display:<?php echo checkPermissions(162); ?>">
                    <span class="fa-solid fa-scroll"></span> &nbsp;
                    Past Purchase Orders
                </a>
                <a href="po-status-report.php" class="list-group-item" style="display:<?php echo checkPermissions(97); ?>">
                    <span class="fa-solid fa-chart-gantt"></span> &nbsp;
                    PO Status Report
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
                    <label class="control-label">Select Purchase Order Date Range To Filter (Keep Blank for All)</label>
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
                    <label class="control-label">Select PO Status</label>
                </div>
                <div class="col-md-3">
                    <select class="form-control" id="poStatus" name="poStatus">
                        <option value="">All</option>
                        <option value="3">Approved</option>
                        <option value="4">Partially Received</option>
                        <option value="5">All Parts Received</option>
                        <option value="6">Paid</option>
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
                    <table class="table" id="poTable">
                        <thead>
                            <tr>
                                <th>PO Date</th>
                                <th>PO No</th>
                                <th>Supplier</th>
                                <th>Supp Inv No.</th>
                                <th>Part Name</th>
                                <th>Qty Ord.</th>
                                <th>Qty rec.</th>
                                <th>Amount (LKR)</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id="poTableBody">
                            <?php while($poRow = $poResult->fetch_assoc()){

                                $supplierId = $poRow["supplier_id"];

                                $supplierResult = $supplierObj->getSupplier($supplierId);
                                $supplierRow = $supplierResult->fetch_assoc();
                                $supplierName = $supplierRow["supplier_name"];
                                
                                $sparePartId = $poRow["part_id"];
                                $sparePartResult = $sparePartObj->getSparePart($sparePartId);
                                $sparePartRow = $sparePartResult->fetch_assoc();
                                $sparePartName = $sparePartRow["part_name"];
                                
                                $status = match((int)$poRow["po_status"]){
                                    
                                    3=>"Approved",
                                    4=>"Partially Received",
                                    5=>"All Parts Received",
                                    6=>"Paid",
                                };
                                
                                ?>
                            <tr>
                                <td style="white-space:nowrap"><?php echo $poRow["order_date"];?></td>
                                <td style="white-space:nowrap"><?php echo $poRow["po_number"];?></td>
                                <td><?php echo $supplierName;?></td>
                                <td><?php echo $poRow["supplier_invoice_number"];?></td>
                                <td><?php echo $sparePartName;?></td>
                                <td><?php echo number_format($poRow["quantity_ordered"]);?></td>
                                <td><?php echo number_format($poRow["quantity_received"]);?></td>
                                <td style="text-align:right"><?php echo number_format($poRow["total_amount"],2);?></td>
                                <td><?php echo $status;?></td>
                                <td>
                                    <a href="../reports/purchaseorder.php?po_id=<?php echo base64_encode($poRow['po_id']);?>" 
                                       class="btn btn-xs btn-info" style="margin:2px;display:<?php echo checkPermissions(95); ?>" target="_blank">
                                    View PO
                                    </a>
                                    <a href="../documents/supplierinvoices/<?php echo $poRow['supplier_invoice'];?>" 
                                       class="btn btn-xs btn-primary" style="margin:2px;display:<?php echo checkPermissions(163); ?>" target="_blank">
                                    View Supp Inv
                                    </a>
                                </td>
                            </tr>
                            <?php } ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <th style="text-align:right;" colspan="2">Page Total:</th>
                                <th style="text-align:right;white-space: nowrap"></th>
                                <th style="text-align:right;" colspan="4">Overall Total:</th>
                                <th style="text-align:right;white-space: nowrap"></th> 
                                <th></th>
                                <th></th>
                            </tr>
                        </tfoot>
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
        
        
        var dataTableOptions = {
            "pageLength": 5,
            "order": [
                [ 0, "desc" ] //Desc order by po date
            ],
             "scrollX": true,
            "footerCallback": function ( row, data, start, end, display ) {
                var api = this.api();
                
                // Calculate Total for the Current Page
                var pageTotal = 0;
                var pageData = api.column(7, { page: 'current' }).data(); // Get data for current page only
                
                for (var i = 0; i < pageData.length; i++) {
                    var amount = pageData[i];
                    var numericValue = parseFloat(String(amount).replace(/LKR /g, '').replace(/,/g, ''));
                    if (!isNaN(numericValue)) {
                       pageTotal += numericValue;
                    }
                }
                
                // Format and display the page total in the 2nd footer cell
                var formattedPageTotal = 'LKR ' + pageTotal.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ",");
                $(api.column(1).footer()).html(formattedPageTotal);
                
                
                //Calculate Overall Total for All Filtered Pages
                
                var overallTotal = 0;
                var overallData = api.column(7, { search: 'applied' }).data(); // Get data for all filtered pages
                
                for (var i = 0; i < overallData.length; i++) {
                    
                    var amount = overallData[i];
                    var numericValue = parseFloat(String(amount).replace(/LKR /g, '').replace(/,/g, ''));
                    
                    if (!isNaN(numericValue)) {
                       overallTotal += numericValue;
                    }
                }
                
                // Format and display the overall total in the 4th footer cell
                var formattedOverallTotal = 'LKR ' + overallTotal.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ",");
                $(api.column(3).footer()).html(formattedOverallTotal);
            }
        };
        
        var table = $("#poTable").DataTable(dataTableOptions);
        
        $('#filter_button').on('click', function(){

            $("#msg").html("");
            $("#msg").removeClass("alert alert-danger");
            
            var dateFrom = $("#dateFrom").val();
            var dateTo = $("#dateTo").val();
            var poStatus = $("#poStatus").val();
            var sparePart = $("#sparePart").val();

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
            
            var url = "../controller/purchase_order_controller.php?status=past_po_filtered";

            $.post(url, {dateFrom:dateFrom, dateTo:dateTo, poStatus:poStatus, sparePart:sparePart}, function (data) {

                // Destroy the old DataTable instance.
                table.destroy();

                // Update the table body with the new filtered data.
                $("#poTableBody").html(data);

                // Re-initialize the DataTable with the new content.
                table = $("#poTable").DataTable(dataTableOptions);
            });
        });
        
    });
</script>
</html>