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
        <?php $pageName="Tender Management - Add Tender" ?>
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
            </ul>
        </div>
        <div class="col-md-9">
            <div class="row">
                <div class="col-md-3">
                    <label class="control-label">Spare Part</label>
                </div>
                <div class="col-md-3">
                    <select class="form-control">
                        <option>-------</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="control-label">Quantity Required</label>
                </div>
                <div class="col-md-3">
                    <input type="number" class="form-control"/>
                </div>
            </div>
            <div class="row">
                &nbsp;
            </div>
            <div class="row">
                <div class="col-md-3">
                    <label class="control-label">Open Date</label>
                </div>
                <div class="col-md-3">
                    <input type="date" class="form-control"/>
                </div>
                <div class="col-md-3">
                    <label class="control-label">Close Date</label>
                </div>
                <div class="col-md-3">
                    <input type="date" class="form-control"/>
                </div>
            </div>
            <div class="row">
                &nbsp;
            </div>
            <div class="row">
                <div class="col-md-3">
                    <label class="control-label">Advertisement</label>
                </div>
                <div class="col-md-3">
                    <input type="file" class="form-control"/>
                </div>
                <div class="col-md-3">
                    <label class="control-label">Description</label>
                </div>
                <div class="col-md-3">
                    <textarea id="address" name="address" rows="2" class="form-control"></textarea>
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
</html>