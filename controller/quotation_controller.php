<?php
include '../commons/session.php';
include '../model/quotation_model.php';


//get user information from session
$userSession=$_SESSION["user"];
$user_id = $userSession['user_id'];

if(!isset($_GET["status"])){
    ?>
    <script>
        window.location= "../view/login.php";
    </script>
    <?php
}

$status= $_GET["status"];

switch ($status){
    
    case "generate_quotation":
        
        
    break;
}