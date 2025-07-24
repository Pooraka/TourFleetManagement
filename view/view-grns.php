<?php

include_once '../commons/session.php';
include_once '../model/grn_model.php';
include_once '../model/sparepart_model.php';
include_once '../model/supplier_model.php';

//get user information from session
$userSession=$_SESSION["user"];

$sparePartObj = new SparePart();
$sparePartResult = $sparePartObj->getAllSparePartsIncludingRemoved();

$supplierObj = new Supplier();
$supplierResult = $supplierObj->getAllSuppliersIncludingRemoved();

$dateFrom ="";
$dateTo ="";
$supplierId ="";
$partId ="";

$grnObj = new GRN();
$grnResult = $grnObj->getAllGRNsFiltered($dateFrom,$dateTo,$supplierId,$partId);

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
        <?php $pageName="Spare Part Management - View GRNs" ?>
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
                <div class="col-md-8">
                    <label class="control-label">Select GRN Date Range To Filter (Keep Blank for All)</label>
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
                    <label class="control-label">Select Supplier</label>
                </div>
                <div class="col-md-3">
                    <select class="form-control" id="supplierId" name="supplierId">
                        <option value="">All</option>
                        <?php while($supplierRow = $supplierResult->fetch_assoc()) { ?>
                            <option value="<?php echo $supplierRow["supplier_id"];?>"><?php echo $supplierRow["supplier_name"];?></option>
                        <?php } ?>
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
                    <table class="table" id="grnTable" style="font-size:15px">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>GRN Number</th>
                                <th>PO Number</th>
                                <th>Supplier</th>
                                <th>Part Name</th>
                                <th>Ordered</th>
                                <th>Received</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody id="grnTableBody">
                        <?php while($grnRow = $grnResult->fetch_assoc()){

                            ?>
                            <tr>
                                <td style="white-space:nowrap"><?php echo $grnRow['grn_received_date'];?></td>
                                <td style="white-space:nowrap"><?php echo $grnRow['grn_number'];?></td>
                                <td style="white-space:nowrap"><?php echo $grnRow['po_number'];?></td>
                                <td><?php echo $grnRow['supplier_name'];?></td>
                                <td><?php echo $grnRow['part_name'];?></td>
                                <td><?php echo $grnRow['quantity_ordered'];?></td>
                                <td><?php echo $grnRow['grn_quantity_received'];?></td>
                                <td>
                                    <a href="../reports/grn.php?grn_id=<?php echo base64_encode($grnRow['grn_id']); ?>" target="_blank" class="btn btn-info" style="margin:2px">
                                        <span class="glyphicon glyphicon-search"></span>                                                  
                                        View
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
<script src="../bootstrap/js/bootstrap.min.js"></script>
<script>
    $(document).ready(function(){

        var dataTableOptions = {
            "pageLength": 5,
            "order": [
                [ 0, "desc" ] //Desc order by GRN date
            ],
             "scrollX": true
        };
        
        var table = $("#grnTable").DataTable(dataTableOptions);
        
        $('#filter_button').on('click', function(){

            $("#msg").html("");
            $("#msg").removeClass("alert alert-danger");
            
            var dateFrom = $("#dateFrom").val();
            var dateTo = $("#dateTo").val();
            var supplierId = $("#supplierId").val();
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

            var url = "../controller/grn_controller.php?status=grn_list_filtered";

            $.post(url, {dateFrom:dateFrom, dateTo:dateTo, supplierId:supplierId, partId:partId}, function (data) {

                // Destroy the old DataTable instance.
                table.destroy();

                // Update the table body with the new filtered data.
                $("#grnTableBody").html(data);

                // Re-initialize the DataTable with the new content.
                table = $("#grnTable").DataTable(dataTableOptions);
            });
        });
    });
</script>
</html>