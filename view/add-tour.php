<?php

include_once '../commons/session.php';
include_once '../model/customer_invoice_model.php';


//get user information from session
$userSession=$_SESSION["user"];

$customerInvoiceObj = new CustomerInvoice();
$pendingInvoiceResult = $customerInvoiceObj->getInvoicesToAssignTours();
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
        <?php $pageName="Tour Management - Add Tour" ?>
        <?php include_once "../includes/header_row_includes.php";?>
        <div class="col-md-3">
            <?php include_once "../includes/tour_management_functions.php"; ?>
        </div>
        <form action="../controller/tour_controller.php?status=add_tour" method="post" enctype="multipart/form-data">
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
                <div class="col-md-3">
                    <label class="control-label">Invoice No</label>
                </div>
                <div class="col-md-3">
                    <select name="invoice_id" id="invoice_id" class="form-control" required="required" onchange="tourData()">
                        <option value="">------------------</option>
                            <?php
                                while($pendingInvoiceRow=$pendingInvoiceResult->fetch_assoc()){
                                    ?>
                            <option value="<?php echo $pendingInvoiceRow['invoice_id'];?>" >
                                <?php echo htmlspecialchars($pendingInvoiceRow['invoice_number']);?>
                            </option>
                            <?php
                                }
                                ?>
                    </select>
                </div>
            </div>
            <div id="dynamic_tour_data">

            </div>
            <div class="row">
                &nbsp;
            </div>
            <div class="row">
                <div class="col-md-6 col-md-offset-3">
                    <input type="submit" class="btn btn-primary" value="Submit"/>
                    <input type="reset" class="btn btn-danger" value="Reset"/>
                </div>
            </div>
        </div>
        </form>
    </div>
</body>
<script src="../js/jquery-3.7.1.js"></script>
<script>
    function tourData(){
        var invoiceId = $('#invoice_id').val();
        
        var url = "../controller/tour_controller.php?status=get_data_to_add_tour";
        
        $.post(url, {invoiceId: invoiceId}, function (data) {

            $("#dynamic_tour_data").html(data);

        });
    }
</script>
</html>