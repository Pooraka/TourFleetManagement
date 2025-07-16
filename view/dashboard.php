<?php

include_once '../commons/session.php';
include_once '../model/module_model.php';

//get user information from session
$userSession=$_SESSION["user"];

$moduleObj = new Module();

$moduleResult = $moduleObj->getAllModules($userSession['user_role']);
?>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <?php include_once "../includes/bootstrap_css_includes.php"?>
</head>
<body>
    <div class="container">
        <?php $pageName="Dashboard" ?>
        <?php include_once "../includes/header_row_includes.php";?>
        <?php while($moduleRow=$moduleResult->fetch_assoc()){
            ?>
            <div class="col-xl-3 col-lg-3 col-md-3 col-sm-6 col-12">
                <a href="<?php echo $moduleRow["module_url"]?>" style="text-decoration:none;color:black">
                    <div class="panel module" style="height:170px;background-color:#ffffff;border:1px solid lightsteelblue">
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
<script>
    $(document).ready(function(){
        $('.module').on(
            {mouseenter:function(){$(this).css("background-color", "#e2eafc");},
            mouseleave: function(){$(this).css("background-color", "#ffffff");}
        });
    });
</script>
</html>