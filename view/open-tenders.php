<?php

include_once '../commons/session.php';


//get user information from session
$userSession=$_SESSION["user"];
?>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tender</title>
    <link rel="stylesheet" type="text/css" href="../css/dataTables.bootstrap.min.css"/>
    <?php include_once "../includes/bootstrap_css_includes.php"?>
</head>
<body>
    <div class="container">
        <?php $pageName="Tender Management - View Tenders" ?>
        <?php include_once "../includes/header_row_includes.php";?>
        <div class="col-md-3">
            <ul class="list-group">
                <a href="add-supplier.php" class="list-group-item">
                    <span class="glyphicon glyphicon-plus"></span> &nbsp;
                    Add Supplier
                </a>
                <a href="view-suppliers.php" class="list-group-item">
                    <span class="glyphicon glyphicon-search"></span> &nbsp;
                    View Suppliers
                </a>
                <a href="add-tender.php" class="list-group-item">
                    <span class="glyphicon glyphicon-plus"></span> &nbsp;
                    Add Tender
                </a>
                <a href="open-tenders.php" class="list-group-item">
                    <span class="glyphicon glyphicon-search"></span> &nbsp;
                    Open Tenders
                </a>
            </ul>
        </div>
        <div class="col-md-9">
            <table class="table" id="test">
                <thead>
                    <tr>
                        <th>Tender ID</th>
                        <th>Spare Part</th>
                        <th>Quantity</th>
                        <th>Open Date</th>
                        <th>Close Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>215</td>
                        <td>Yutong ZK6938HQ Oil Filter</td>
                        <td>25</td>
                        <td>2025-06-20</td>
                        <td>2025-06-30</td>
                        <td>
                            <a href="../controller/user_controller.php?status=activate&user_id=<?php echo $user_id; ?>" class="btn btn-success" style="margin:2px">
                                <span class="glyphicon glyphicon-plus"></span>
                                Add Bids
                            </a>
                            <a  class="btn btn-primary" style="margin:2px">
                                <span class="glyphicon glyphicon-search"></span>
                                View Bids
                            </a>
                            <a href="../controller/user_controller.php?status=deactivate&user_id=<?php echo $user_id; ?>" class="btn btn-danger" style="margin:2px">
                                <span class="glyphicon glyphicon-remove"></span>
                                Remove
                            </a>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</body>
<script src="../js/datatable/jquery-3.5.1.js"></script>
<script src="../js/datatable/jquery.dataTables.min.js"></script>
<script src="../js/datatable/dataTables.bootstrap.min.js"></script>
<script>
    $(document).ready(function(){

        $("#test").DataTable();
    });
</script>
</html>