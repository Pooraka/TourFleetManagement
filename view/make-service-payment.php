<?php

include_once '../commons/session.php';
include_once '../model/service_detail_model.php';
include_once '../model/service_station_model.php';

//get user information from session
$userSession=$_SESSION["user"];

if(!isset($_GET['service_station_id'])||$_GET['service_station_id']==""){?>
    <script>
        window.location="pending-service-payments.php";
    </script>
<?php
}

$serviceStationId = base64_decode($_GET['service_station_id']);

$serviceDetailObj = new ServiceDetail();
$serviceDetailResult = $serviceDetailObj->getPaymentPendingServices($serviceStationId);

$serviceStationObj = new ServiceStation();
$serviceStationResult = $serviceStationObj->getServiceStation($serviceStationId);
$serviceStationRow = $serviceStationResult->fetch_assoc();
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
        <?php $pageName="Finance Management - Make Service Payments" ?>
        <?php include_once "../includes/header_row_includes.php";?>
        <div class="col-md-3">
            <ul class="list-group">
                <a href="pending-service-payments.php" class="list-group-item">
                    <span class="glyphicon glyphicon-search"></span> &nbsp;
                    Pending Service Payments
                </a>
            </ul>
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
                <form action="../controller/finance_controller.php?status=make_service_payment" method="post" enctype="multipart/form-data"> 
                <div class="panel panel-info">
                    <div class="panel-heading"><?php echo "<b>".$serviceStationRow['service_station_name']."</b>"." Payments";?> </div>
                    <div class="panel-body">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Select</th>
                                    <th>Vehicle</th>
                                    <th>Serviced Date</th>
                                    <th>Amount</th>
                                    <th>Invoice Number</th>                                    
                                    <th>View Invoice</th>                                    
                                </tr>
                            </thead>
                            <tbody>
                                <?php while($serviceDetailRow = $serviceDetailResult->fetch_assoc()){ ?>
                                <tr>
                                    <td style="text-align: center"><input name="service[]" type="checkbox" class="amount-check" data-amount="<?php echo $serviceDetailRow['cost'];?>" value="<?php echo $serviceDetailRow['service_id'];?>"></td>
                                    <td><?php echo $serviceDetailRow['vehicle_no'];?></td>
                                    <td><?php echo $serviceDetailRow['completed_date'];?></td>
                                    <td><?php echo "LKR ".number_format($serviceDetailRow['cost'],2);?></td>
                                    <td><?php echo $serviceDetailRow['invoice_number'];?></td>
                                    <td>
                                        <a href="../documents/busserviceinvoices/<?php echo $serviceDetailRow['invoice']; ?>" target="_blank" class="btn btn-primary">
                                            <span class="glyphicon glyphicon-file"></span> View Invoice
                                        </a>
                                    </td>
                                </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="panel-heading">Process Payment</div>
                    <div class="panel-footer">
                        <div class="row">
                            <div class="col-md-12">
                                <h4><b>Total Amount (LKR):<span id="total"></span></b></h4>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3">
                                <h4>Payment type</h4>
                            </div>
                            <div class="col-md-6">
                                <h4>
                                    <input type="radio" name="payment_method" value="cheque"/>
                                    <label>Cheque</label>
                                    &nbsp;&nbsp;
                                    <input type="radio" name="payment_method" value="transfer"/>
                                    <label>Funds Transfer</label>
                                </h4>
                            </div>
                            <div class="col-md-3">&nbsp;</div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <h4><span>Cheque Number or Funds Transfer reference</span></h4>
                            </div>
                            <div class="col-md-6">
                                 <input type="text" class="form-control" name="reference" placeholder="FT125485 or 254785"/>
                            </div>
                        </div>
                        <div class="row">
                            &nbsp;
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <h4><span>Attach payment document</span></h4>
                            </div>
                            <div class="col-md-6">
                                <input type="file" class="form-control" name="payment_document"/>
                            </div>
                        </div>
                        <div class="row">
                            <input type="hidden" name="service_station_id" value="<?php echo $serviceStationId; ?>"/>
                            <input type="hidden" id="totalpaymenttosubmit" name="totalpayment" value=""/>
                            &nbsp;
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <input type="submit" class="btn btn-success" value="Payment Made"/>
                                <input type="reset" class="btn btn-danger" value="Reset" style="width:130px"/>
                            </div>
                        </div>
                    </div>
                </div>
                </form>
            </div>
        </div>
    </div>
</body>
<script src="../js/jquery-3.7.1.js"></script>
<script>
    $(document).ready(function(){
        
        function calculateTotal(){
            
            let total = 0;
            
            $('.amount-check:checked').each(function () {
                
                
                total += parseFloat(String($(this).data('amount')));
                
                $('input[name="totalpayment"]').val(total);
            });
            
            total = total.toLocaleString('en-US', {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
            });
            $('#total').text(total);
            
        }
        
        $('.amount-check').on('change',function(){
            calculateTotal();
        });
        
        calculateTotal();
        
    });
</script>
</html>