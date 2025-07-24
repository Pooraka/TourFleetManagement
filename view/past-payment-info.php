<?php

include_once '../commons/session.php';
include_once '../model/finance_model.php';
include_once '../model/user_model.php';

//get user information from session
$userSession=$_SESSION["user"];

$financeObj = new Finance();
$userObj = new User();


$dateFrom = "";
$dateTo = "";
$txnCategory = "";
$paymentMethod = "";

$paymentResult = $financeObj->getPastPayments($dateFrom, $dateTo, $txnCategory,$paymentMethod);
?>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Finance Management</title>
    <?php include_once "../includes/bootstrap_css_includes.php"?>
</head>
<body>
    <div class="container">
        <?php $pageName="Finance Management - Past Payment Info" ?>
        <?php include_once "../includes/header_row_includes.php";?>
        <div class="col-md-3">
            <?php include_once "../includes/finance_functions.php"; ?>
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
                    <label class="control-label">Select Payment Date Range To Filter (Keep Blank for All)</label>
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
                    <label class="control-label">Select Payment Type</label>
                </div>
                <div class="col-md-3">
                    <select class="form-control" id="txnCategory" name="txnCategory">
                        <option value="">All</option>
                        <option value="1">Service Payment</option>
                        <option value="2">Supplier Payment</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="control-label">Select Payment Method</label>
                </div>
                <div class="col-md-3">
                    <select class="form-control" id="paymentMethod" name="paymentMethod">
                        <option value="">All</option>
                        <option value="Cheque">Cheque</option>
                        <option value="Transfer">Transfer</option>
                    </select>
                </div>
            </div>
            <div class="row">
                &nbsp;
            </div>
            <div class="row">
                <div class="col-md-12">
                    <table class="table" id="paymentTable">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Amount (LKR)</th>
                                <th>Reference</th>
                                <th>Payment Method</th>
                                <th>Category</th>
                                <th>Paid By</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id="paymentTableBody">
                            <?php while($paymentRow = $paymentResult->fetch_assoc()){
                                
                                $userId = $paymentRow["paid_by"];
                                $userResult = $userObj->getUser($userId);
                                $userRow = $userResult->fetch_assoc();
                                $userName = substr($userRow["user_fname"],0,1).". ".$userRow["user_lname"];
                                
                                ?>
                            <tr>
                                <td style="white-space: nowrap"><?php echo $paymentRow["date"];?></td>
                                <td style="white-space: nowrap"><?php echo number_format((float)$paymentRow["amount"],2);?></td>
                                <td style=""><?php echo $paymentRow["reference"];?></td>
                                <td style=""><?php echo $paymentRow["payment_method"];?></td>
                                <td style=""><?php echo $paymentRow["category"];?></td>
                                <td style="white-space: nowrap"><?php echo $userName;?></td>
                                <td>
                                    <a href="../documents/paymentsmade/<?php echo $paymentRow["payment_document"];?>" 
                                       class="btn btn-xs btn-info" style="margin:2px;" target="_blank">
                                    Payment Document
                                    </a>
                                    <?php if($paymentRow["category_id"]==1){ ?>
                                    <button type="button" class="btn btn-xs btn-primary"
                                            style="margin:2px;"
                                            data-toggle="modal" data-target="#serviceListModal"
                                            onclick="serviceListOfAPayment(<?php echo $paymentRow["payment_id"];?>)"
                                            >
                                        View Service List
                                    </button>   
                                    <?php } ?>
                                    <?php if($paymentRow["category_id"]==2){ ?>
                                    <button type="button" class="btn btn-xs btn-primary"
                                            style="margin:2px;"
                                            data-toggle="modal" data-target="#poListModal"
                                            onclick="poListOfAPayment(<?php echo $paymentRow["payment_id"];?>)"
                                            >
                                        View PO List
                                    </button>   
                                    <?php } ?>
                                </td>
                            </tr>
                            <?php }?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <th colspan="2">Page Total:</th>
                                <th></th>
                                <th></th>
                                <th colspan="2">Total Amount:</th>
                                <th></th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>        
        </div>
    </div>
</body>
<div class="modal fade" id="serviceListModal" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header"><b><h4>View Service List</h4></b></div>
            <div class="modal-body">
                <div id="sd_display_data">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="poListModal" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header"><b><h4>View Purchase Order List</h4></b></div>
            <div class="modal-body">
                <div id="po_display_data">
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
    function serviceListOfAPayment(paymentId){
        
        var url ="../controller/service_detail_controller.php?status=service_list_of_a_payment_modal";

        $.post(url,{paymentId:paymentId},function(data){
            $("#sd_display_data").html(data).show();
            
        });
    }
    
    function poListOfAPayment(paymentId){
        
        var url ="../controller/purchase_order_controller.php?status=po_list_of_a_payment_modal";

        $.post(url,{paymentId:paymentId},function(data){
            $("#po_display_data").html(data).show();
            
        });
    }
    
    $(document).ready(function(){
        
        var dataTableOptions = {
            "pageLength": 5,
            "order": [
                [ 0, "desc" ] //Desc order by payment date
            ],
             "scrollX": true,
            "footerCallback": function ( row, data, start, end, display ) {
                var api = this.api();
                
                // Calculate Total for the Current Page
                var pageTotal = 0;
                var pageData = api.column(1, { page: 'current' }).data(); // Get data for current page only
                
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
                var overallData = api.column(1, { search: 'applied' }).data(); // Get data for all filtered pages
                
                for (var i = 0; i < overallData.length; i++) {
                    
                    var amount = overallData[i];
                    var numericValue = parseFloat(String(amount).replace(/LKR /g, '').replace(/,/g, ''));
                    
                    if (!isNaN(numericValue)) {
                       overallTotal += numericValue;
                    }
                }
                
                // Format and display the overall total in the 4th footer cell
                var formattedOverallTotal = 'LKR ' + overallTotal.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ",");
                $(api.column(4).footer()).html(formattedOverallTotal);
            }
        };
        
        var table = $("#paymentTable").DataTable(dataTableOptions);
        
        $('#filter_button').on('click', function(){

            $("#msg").html("");
            $("#msg").removeClass("alert alert-danger");
            
            var dateFrom = $("#dateFrom").val();
            var dateTo = $("#dateTo").val();
            var txnCategory = $("#txnCategory").val();
            var paymentMethod = $("#paymentMethod").val();

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
            
            var url = "../controller/finance_controller.php?status=past_payments_filtered";

            $.post(url, {dateFrom:dateFrom, dateTo:dateTo, txnCategory:txnCategory, paymentMethod:paymentMethod}, function (data) {

                // Destroy the old DataTable instance.
                table.destroy();

                // Update the table body with the new filtered data.
                $("#paymentTableBody").html(data);

                // Re-initialize the DataTable with the new content.
                table = $("#paymentTable").DataTable(dataTableOptions);
            });
        });
        
        
    });
</script>
</html>