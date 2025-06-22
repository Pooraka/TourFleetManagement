<?php
session_name("TourFleetManagement");
session_start();
function checkPermissions($functionId){
    
    if(!in_array($functionId,$_SESSION['user_functions'])){
        return 'none';
    }
}


?>