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
    <?php include_once "../includes/bootstrap_css_includes.php"?>
</head>
<body>
    <div class="container">
        <?php $pageName="Tender Management" ?>
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
            </ul>
        </div>
        <div class="col-md-9">
            <div class="row">
                <div class="col-md-6 col-md-offset-3">
                    <?php

                        if(isset($_GET["msg"])){

                            $msg = base64_decode($_GET["msg"]);
                    ?>
                            <div class="row">
                                <div class="alert alert-success" style="text-align:center">
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
                    <table class="table" id="suppliertable">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Contact</th>
                                <th>Email</th>                               
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>United Motors Lanka</td>
                                <td>0112448112</td>
                                <td>info@unitedmotors.lk</td>
                                <td>Active</td>
                                <td>
                                    <button class="btn btn-warning btn-xs"><i class="fa fa-edit"></i> Edit</button>
                                    <button class="btn btn-danger btn-xs"><i class="fa fa-trash"></i> Delete</button>
                                </td>
                            </tr>
                            <tr>
                                <td>Lanka Ashok Leyland PLC - Spare Parts</td>
                                <td>0112867435</td>
                                <td>parts@lal.lk</td>
                                <td>Active</td>
                                <td>
                                    <button class="btn btn-warning btn-xs"><i class="fa fa-edit"></i> Edit</button>
                                    <button class="btn btn-danger btn-xs"><i class="fa fa-trash"></i> Delete</button>
                                </td>
                            </tr>
                            <tr>
                                <td>Japan Auto Parts Colombo</td>
                                <td>0777321654</td>
                                <td>sales@japanautoparts.lk</td>
                                <td>Active</td>
                                <td>
                                    <button class="btn btn-warning btn-xs"><i class="fa fa-edit"></i> Edit</button>
                                    <button class="btn btn-danger btn-xs"><i class="fa fa-trash"></i> Delete</button>
                                </td>
                            </tr>
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

        $("#suppliertable").DataTable();
    });
</script>
</html>