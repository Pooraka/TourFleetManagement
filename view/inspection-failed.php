<?php

include_once '../commons/session.php';
include_once '../model/inspection_model.php';


//get user information from session
$userSession=$_SESSION["user"];

$inspectionObj = new Inspection();
$inspectionFailedResult = $inspectionObj->getFailedInspectionsToAssignNewBus();
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
        <?php $pageName="Tour Management - Failed Inspections" ?>
        <?php include_once "../includes/header_row_includes.php";?>
        <div class="col-md-3">
            <ul class="list-group">
                <a href="add-tour.php" class="list-group-item">
                    <span class="glyphicon glyphicon-plus"></span> &nbsp;
                    Add Tour
                </a>
                <a href="pending-tours.php" class="list-group-item">
                    <span class="glyphicon glyphicon-search"></span> &nbsp;
                    Pending Tours
                </a>
                <a href="inspection-failed.php" class="list-group-item">
                    <span class="glyphicon glyphicon-search"></span> &nbsp;
                    Pre-Tour Failed Inspections
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
                    <table class="table" id="inspection_failed_table">
                        <thead>
                            <tr>
                                <th>Inspection Id</th>
                                <th>Tour Destination</th>
                                <th>Tour Date</th>
                                <th>Vehicle No</th>
                                <th>Inspection Comment</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while($inspectionFailedRow = $inspectionFailedResult->fetch_assoc()){ ?>
                            <tr>
                                <td><?php echo $inspectionFailedRow["inspection_id"];?></td>
                                <td><?php echo $inspectionFailedRow["destination"];?></td>
                                <td style="white-space: nowrap"><?php echo $inspectionFailedRow["start_date"];?></td>
                                <td><?php echo $inspectionFailedRow["vehicle_no"];?></td>
                                <td><?php echo $inspectionFailedRow["final_comments"];?></td>
                                <td>
                                    <a href="reassign-bus.php?inspection_id=<?php echo base64_encode($inspectionFailedRow['inspection_id'])?>" class="btn btn-xs btn-success" style="margin:2px">
<!--                                        <span class="glyphicon glyphicon-ok"></span>-->
                                        Assign A </br>Different Bus
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

        $("#inspection_failed_table").DataTable();

    });
</script>
</html>