<?php

include_once '../commons/session.php';
include_once '../model/customer_invoice_model.php';


//get user information from session
$userSession=$_SESSION["user"];

$customerInvoiceObj = new CustomerInvoice();
$pendingInvoiceResult = $customerInvoiceObj->getPendingCustomerInvoices();
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
            <ul class="list-group">
                <a href="add-tour.php" class="list-group-item">
                    <span class="glyphicon glyphicon-plus"></span> &nbsp;
                    Add Tour
                </a>
            </ul>
        </div>
        <div class="col-md-9">
            <div class="row">
                <div id="msg" class="col-md-offset-3 col-md-6" style="text-align:center;">
                    <?php if (isset($_GET["msg"])) { ?>

                        <script>
                            var msgElement = document.getElementById("msg");
                            msgElement.classList.add("alert", "alert-danger");
                        </script>

                        <b> <p> <?php echo base64_decode($_GET["msg"]); ?></p></b>
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
                    <select name="invoice_id" id="invoice_id" class="form-control" required="required">
                        <option value="">------------------</option>
                            <?php
                                while($pendingInvoiceRow=$pendingInvoiceResult->fetch_assoc()){
                                    ?>
                            <option value="<?php echo $pendingInvoiceRow['invoice_id'];?>" 
                                    data-start-date="<?php echo htmlspecialchars($pendingInvoiceRow['tour_start_date']); ?>" 
                                    data-end-date="<?php echo htmlspecialchars($pendingInvoiceRow['tour_end_date']); ?>">
                                <?php echo htmlspecialchars($pendingInvoiceRow['invoice_number']);?>
                            </option>
                            <?php
                                }
                                ?>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="control-label">Destination</label>
                </div>
                <div class="col-md-3">
                    <input type="text" class="form-control" name="destination" id="destination"/>
                </div>
            </div>
            <div class="row">
                &nbsp;
            </div>
            <div class="row">
                <div class="col-md-3">
                    <label class="control-label">Start Date</label>
                </div>
                <div class="col-md-3">
                    <input type="date" class="form-control" name="start_date" id="start_date" value=""/>
                </div>
                <div class="col-md-3">
                    <label class="control-label">End Date</label>
                </div>
                <div class="col-md-3">
                    <input type="date" class="form-control" name="end_date" id="end_date" value=""/>
                </div>
            </div>
            <div class="row">
                &nbsp;
            </div>
            <div class="row">
                <div class="col-md-6">
                    <label><b>Requested Bus Types & Quantity</b></label>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Bus Category</th>
                                <th>Quantity</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Standard</td>
                                <td>1</td>
                            </tr>
                            <tr>
                                <td>Mini Bus</td>
                                <td>1</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="row">
                &nbsp;
            </div>
            <div class="row">
                <div class="col-md-12">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Select</th>
                                <th>Vehicle No</th>
                                <th>Make</th>
                                <th>Model</th>
                                <th>Passengers</th>
                                <th>Category</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><input type="checkbox"/></td>
                                <td>62-9102</td>
                                <td>Toyota</td>
                                <td>Coaster</td>
                                <td>29</td>
                                <td>Mini Bus</td>
                            </tr>
                            <tr>
                                <td><input type="checkbox"/></td>
                                <td>CAA-1234</td>
                                <td>Yutong</td>
                                <td>ZK6938HQ</td>
                                <td>40</td>
                                <td>Luxury</td>
                            </tr>
                            <tr>
                                <td><input type="checkbox"/></td>
                                <td>NA-4567</td>
                                <td>Mitsubishi</td>
                                <td>Fuso Rosa</td>
                                <td>25</td>
                                <td>Mini Bus</td>
                            </tr>
                            <tr>
                                <td><input type="checkbox"/></td>
                                <td>NB-5678</td>
                                <td>Lanka Ashok Leyland</td>
                                <td>Viking</td>
                                <td>54</td>
                                <td>Standard</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
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
    </div>
</body>
<script src="../js/jquery-3.7.1.js"></script>
<script>
        $(document).ready(function(){
        
        $('#invoice_id').on('change',function(){
            
            var selectedInvoice = $(this).find('option:selected');
            
            var startDate = selectedInvoice.data('start-date');
            var endDate = selectedInvoice.data('end-date');
            
            
            if($(this).val()===""){
                
                $('#start_date').val('');
                $('#end_date').val('');
            }
            else {
                
                $('#start_date').val(startDate);
                $('#end_date').val(endDate);
            }
            
        });
    });
</script>
</html>