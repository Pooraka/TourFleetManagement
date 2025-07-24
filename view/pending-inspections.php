<?php

include_once '../commons/session.php';
include_once '../model/inspection_model.php';

//get user information from session
$userSession=$_SESSION["user"];

$inspectionObj = new Inspection();

$pendingInspectionsResult = $inspectionObj->getPendingInspections();
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
        <?php $pageName="Bus Maintenance - Pending Inspections" ?>
        <?php include_once "../includes/header_row_includes.php";?>
        <div class="col-md-3">
            <?php include_once "../includes/bus_maintenance_functions.php"; ?>
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
                    <table class="table" id="pending_inspections">
                        <thead>
                            <tr>
                                <th>Tour Date</th>
                                <th>Inspection ID</th>
                                <th>Vehicle No</th>
                                <th>Tour Destination</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php while($pendingInspectionsRow=$pendingInspectionsResult->fetch_assoc()) {?>
                            <tr>
                                <td><?php echo $pendingInspectionsRow['start_date'];?></td>
                                <td><?php echo $pendingInspectionsRow['inspection_id'];?></td>
                                <td><?php echo $pendingInspectionsRow['vehicle_no'];?></td>
                                <td><?php echo $pendingInspectionsRow['destination'];?></td>
                                <td>
                                    <a href="inspect-bus.php?inspection_id=<?php echo base64_encode($pendingInspectionsRow['inspection_id'])?>" 
                                       class="btn btn-success" style="margin:2px;display:<?php echo checkPermissions(131); ?>">
<!--                                        <span class="glyphicon glyphicon-ok"></span>-->
                                        Inspect
                                    </a>
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
<script src="../js/datatable/jquery-3.5.1.js"></script>
<script src="../js/datatable/jquery.dataTables.min.js"></script>
<script src="../js/datatable/dataTables.bootstrap.min.js"></script>
<script>
    $(document).ready(function(){

        $("#pending_inspections").DataTable();

    });
</script>
</html>