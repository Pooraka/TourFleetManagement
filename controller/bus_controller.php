<?php
include '../commons/session.php';
include '../model/bus_model.php';

$busObj = new Bus();

if(!isset($_GET["status"])){
    ?>
    <script>
        window.location= "../view/login.php";
    </script>
    <?php
}

$status= $_GET["status"];

switch ($status){
    
    case "add_bus":
        
        
}