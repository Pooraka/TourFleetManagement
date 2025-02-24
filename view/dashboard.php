<?php

include_once '../commons/session.php';
include_once '../model/module_model.php';

//get user information from session
$userSession=$_SESSION["user"];

$moduleObj = new Module();

$moduleResult = $moduleObj->getAllModules();
?>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Management</title>
    <?php include_once "../includes/bootstrap_css_includes.php"?>
</head>
<body>
    <div class="container">
        <?php $pageName="Dashboard" ?>
        <?php include_once "../includes/header_row_includes.php";?>
        <?php while($moduleRow=$moduleResult->fetch_assoc()){
            ?>
            <div class="col-md-3 col-sm-4">
                <a href="<?php echo $moduleRow["module_url"]?>" style="text-decoration:none; color:#fff">
                    <div class="panel" style="height:150px; background-color:#2A7F97">
                        <h1 align="center">
                            <img src="../images/moduleimages/<?php echo $moduleRow["module_icon"]?>" style="height:80px">
                        </h1>
                        <h4 align="center"> 
                        <?php echo $moduleRow["module_name"];?>
                        </h4>
                    </div>
                </a>
            </div>
            <?php
        }
        ?>
    </div>
</body>
<script src="../js/jquery-3.7.1.js"></script>
</html>