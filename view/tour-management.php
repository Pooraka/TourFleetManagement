<?php

include_once '../commons/session.php';
include_once '../model/inspection_model.php';
include_once '../model/tour_model.php';


//get user information from session
$userSession=$_SESSION["user"];
$userFunctions=$_SESSION['user_functions'];

$inspectionObj = new Inspection();
$tourObj = new Tour();

$inspectionFailedCountToAssignNewBuses = $inspectionObj->getInspectionFailedCountToAssignNewBuses();
$ongoingTourCount = $tourObj->getOngoingTourCount();
$upComingTourCount = $tourObj->getUpComingToursCount();
$tourCountStartingToday = $tourObj->getTourCountStartingToday();
?>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tour Management</title>
    <?php include_once "../includes/bootstrap_css_includes.php"?>
</head>
<body>
    <div class="container">
        <?php $pageName="Tour Management" ?>
        <?php include_once "../includes/header_row_includes.php";?>
        <div class="col-md-3">
            <ul class="list-group">
                <a href="dashboard.php" class="list-group-item">
                    <span class="fa-solid fa-house"></span> &nbsp;
                    Back To Dashboard
                </a>
                <a href="add-tour.php" class="list-group-item" style="display:<?php echo checkPermissions(82); ?>">
                    <span class="fa-solid fa-plus"></span> &nbsp;
                    Add Tour
                </a>
                <a href="pending-tours.php" class="list-group-item" style="display:<?php echo checkPermissions(83); ?>">
                    <span class="fa-solid fa-clock-rotate-left"></span> &nbsp;
                    Pending Tours
                </a>
                <a href="inspection-failed.php" class="list-group-item" style="display:<?php echo checkPermissions(87); ?>">
                    <span class="fa-solid fa-triangle-exclamation"></span> &nbsp;
                    Pre-Tour Failed Inspections
                </a>
                <a href="past-tour-info.php" class="list-group-item" style="display:<?php echo checkPermissions(165); ?>">
                    <span class="fa-solid fa-scroll"></span> &nbsp;
                    Past Tour Info
                </a>
            </ul>
        </div>
        <div class="col-md-9">
            <?php if($inspectionFailedCountToAssignNewBuses > 0) { ?>
            <div class="row">
                <div class="col-md-12">
                    <div class="alert alert-danger">
                        <span class="fa-solid fa-triangle-exclamation"></span>&nbsp;
                        <strong>Action Required:</strong> There are <strong><?php echo $inspectionFailedCountToAssignNewBuses; ?></strong> buses that have failed pre-tour inspections. 
                        <a id="inspectionFailedLink" href="" class="alert-link">Please assign new buses immediately</a>.
                    </div>
                </div>
            </div>
            <?php } ?>
            <div class="row">
                <div class="col-md-6">
                    <div class="panel panel-info" style="text-align:center; height:150px">
                        <div class="panel-heading">
                            <span class="fa-solid fa-truck"></span>&nbsp;
                            Ongoing Tours
                        </div>
                        <div class="panel-body">
                            <h1 class="h1"><?php echo $ongoingTourCount; ?></h1>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="panel panel-info" style="text-align:center; height:150px">
                        <div class="panel-heading">
                            <span class="fa-solid fa-calendar-day"></span>&nbsp;
                            Tours Starting Today
                        </div>
                        <div class="panel-body">
                            <h1 class="h1"><?php echo $tourCountStartingToday; ?></h1>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-offset-3 col-md-6">
                    <div class="panel panel-info" style="text-align:center; height:150px">
                        <div class="panel-heading">
                            <span class="fa-solid fa-hourglass-half"></span>&nbsp;
                            Upcoming Tours (Next 7 Days)
                        </div>
                        <div class="panel-body">
                            <h1 class="h1"><?php echo $upComingTourCount; ?></h1>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
<script src="../js/jquery-3.7.1.js"></script>
<script>

    var userFunctionsArray = <?php echo json_encode($userFunctions); ?>;

    var inspectionFailedURL = "inspection-failed.php";

    if( userFunctionsArray.includes(87)) {
        $('#inspectionFailedLink').attr('href', inspectionFailedURL);
    }
</script>
</html>