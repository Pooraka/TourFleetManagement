<?php

include_once '../commons/session.php';
include_once '../model/service_detail_model.php';
include_once '../model/bus_model.php';

//get user information from session
$userSession=$_SESSION["user"];

$status="";

$serviceDetailObj = new ServiceDetail();
$serviceDetailResult = $serviceDetailObj->getPastServicesFiltered($status);

$busObj = new Bus();
?>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bus Maintenance</title>
    <link rel="stylesheet" type="text/css" href="../css/dataTables.bootstrap.min.css"/>
    <?php include_once "../includes/bootstrap_css_includes.php"?>
</head>
<body>
    <div class="container">
        <?php $pageName="Bus Maintenance - Service History" ?>
        <?php include_once "../includes/header_row_includes.php";?>
        <div class="col-md-3">
            <?php include_once "../includes/bus_maintenance_functions.php"; ?>
        </div>
        <form action="../controller/service_detail_controller.php?status=initiate_service" method="post" enctype="multipart/form-data">
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
                    <div class="col-md-3">
                        <label class="control-label">Select Status</label>
                    </div>
                    <div class="col-md-3">
                        <select id="status" class="form-control">
                            <option value="">All</option>
                            <option value="2">Completed</option>
                            <option value="3">Completed & Paid</option>
                        </select>
                    </div>
                    <div class="col-md-offset-3 col-md-3 text-right">
                        <button type="button" class="btn btn-success" id="filter_button">Filter</button>
                    </div>
                </div>
                <div class="row">
                    &nbsp;
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <table class="table" id="servicetable" style="width:100%">
                            <thead>
                                <tr>
                                    <th>Vehicle</th>
                                    <th>Serviced Date</th>
                                    <th>Serviced Mileage</th>
                                    <th>Invoice</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody id="servicetableBody">
                                <?php   while($serviceDetailRow = $serviceDetailResult->fetch_assoc()){
                                            
                                            $busId = $serviceDetailRow['bus_id'];
                                            $busResult = $busObj->getBus($busId);
                                            $busRow = $busResult->fetch_assoc();
                                            $serviceStatus = $serviceDetailRow['service_status'];
                                            $statusDisplay = match($serviceStatus){
                                                '-1'=>'Cancelled',
                                                '1'=>'Ongoing',
                                                '2'=>'Completed',
                                                '3'=>'Completed & Paid'
                                            };
                                            
                                            $statusDisplayClass = match($serviceStatus){
                                                '-1'=>'label label-danger',
                                                '1'=>'label label-warning',
                                                '2'=>'label label-success',
                                                '3'=>'label label-primary'
                                            };
                                            
                                            $serviceId = $serviceDetailRow['service_id'];
                                            $serviceId = base64_encode($serviceId);
                                ?>
                                <tr>
                                    <td><?php echo $busRow['vehicle_no'];?></td>
                                    <td><?php echo $servicedDate = ($serviceDetailRow['completed_date']=="")?"Not Applicable":$serviceDetailRow['completed_date'];?> </td>
                                    <td><?php echo number_format($serviceDetailRow['mileage_at_service'],0);?>&nbsp; Km </td>
                                    <td><?php echo $serviceDetailRow['invoice_number'];?></td>
                                    <td><span class="<?php echo $statusDisplayClass;?>"><?php echo $statusDisplay;?></span> </td>
                                    <td>
                                        <a href="view-service-record.php?service_id=<?php echo $serviceId; ?>" 
                                           class="btn btn-sm btn-info" style="margin:2px;display:<?php echo checkPermissions(123); ?>">
                                            <span class="fa-solid fa-circle-info"></span>                                                  
                                            View
                                        </a>
                                        <?php if($serviceStatus==2){ ?>
                                        <a href="edit-service-record.php?service_id=<?php echo $serviceId; ?>" 
                                           class="btn btn-sm btn-warning" style="margin:2px;display:<?php echo checkPermissions(124); ?>">
                                            <span class="glyphicon glyphicon-pencil"></span>
                                            Edit
                                        </a>
                                        <?php } ?>
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
        </form>
    </div>
</body>
<script src="../js/datatable/jquery-3.5.1.js"></script>
<script src="../js/datatable/jquery.dataTables.min.js"></script>
<script src="../js/datatable/dataTables.bootstrap.min.js"></script>
<script src="../bootstrap/js/bootstrap.min.js"></script>
<script>
    $(document).ready(function() {

        var dataTableOptions = {
            "pageLength": 15,
            "order": [
                [ 1, "desc" ] 
            ],
             "scrollX": true
        };

        var table = $("#servicetable").DataTable(dataTableOptions);

        $('#filter_button').on('click', function(){

            $("#msg").html("");
            $("#msg").removeClass("alert alert-danger");
            
            var status = $('#status').val();

            
            var url = "../controller/service_detail_controller.php?status=all_past_services_filtered";

            $.post(url, {status:status}, function (data) {

                // Destroy the old DataTable instance.
                table.destroy();

                // Update the table body with the new filtered data.
                $("#servicetableBody").html(data);

                // Re-initialize the DataTable with the new content.
                table = $("#servicetable").DataTable(dataTableOptions);
            });
        });
    });
</script>
</html>