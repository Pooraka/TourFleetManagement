<?php

include_once '../commons/session.php';
include_once '../model/service_detail_model.php';

//get user information from session
$userSession=$_SESSION["user"];

$serviceDetailObj = new ServiceDetail();
$serviceDetailResult = $serviceDetailObj->getPaymentPendingServiceStations();
?>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Finance Management</title>
    <link rel="stylesheet" type="text/css" href="../css/dataTables.bootstrap.min.css"/>
    <?php include_once "../includes/bootstrap_css_includes.php"?>
</head>
<body>
    <div class="container">
        <?php $pageName="Finance Management - Pending Service Payments" ?>
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
                <div class="col-md-12">
                    <table class="table" id="pendingservicetable">
                        <thead>
                            <tr>
                                <th>Service Station</th>
                                <th>Number of Services</th>
                                <th>Total Due</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while($serviceDetailRow = $serviceDetailResult->fetch_assoc()){ ?>
                            <tr>
                                <td><?php echo $serviceDetailRow['service_station_name'];?></td>
                                <td><?php echo $serviceDetailRow['service_count'];?></td>
                                <td><?php echo number_format($serviceDetailRow['total_due'],2);?></td>
                                <td>
                                    <a href="make-service-payment.php?service_station_id=<?php echo base64_encode($serviceDetailRow['service_station_id']) ?>" 
                                       class="btn btn-success" style="margin:2px;display:<?php echo checkPermissions(135); ?>">
                                        <span class="fas fa-dollar-sign"></span>
                                        Pay
                                    </a>
                                </td>
                            </tr>
                            <?php 
                            } 
                            ?>
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
<script>
    $(document).ready(function(){

        $("#pendingservicetable").DataTable();

    });
</script>
</html>