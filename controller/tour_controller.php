<?php
include_once '../commons/session.php';
include_once '../model/tour_model.php';


//get user information from session
$userSession=$_SESSION["user"];
$user_id = $userSession['user_id'];

$tourObj = new Tour();

if(!isset($_GET["status"])){
    ?>
    <script>
        window.location= "../view/login.php";
    </script>
    <?php
}

$status= $_GET["status"];

switch ($status){
    
    

}
