<?php
include_once '../commons/session.php';
include_once '../model/quotation_model.php';
include_once '../model/customer_model.php';


//get user information from session
$userSession=$_SESSION["user"];
$user_id = $userSession['user_id'];

$customerObj = new Customer();
$quotationObj = new Quotation();

if(!isset($_GET["status"])){
    ?>
    <script>
        window.location= "../view/login.php";
    </script>
    <?php
}

$status= $_GET["status"];

switch ($status){
    
    case "get_customer":
        
        $nic=strtoupper($_POST["nic"]);
        
        if($nic==""){
            echo '<b style="color:red">Enter NIC</b>';
        }
        else{
            
            $customerResult = $customerObj->checkIfCustomerExist($nic);
            
            if($customerResult->num_rows==0){
                echo '<b style="color:red">Customer Does Not Exist</b>';
            }
            else{
                $customerRow = $customerResult->fetch_assoc();
                $customerName = $customerRow['customer_fname']." ".$customerRow['customer_lname'];
                echo "<b>".$customerName."</b>";
            }
        }
        
    break;
    
    
    case "generate_quotation":
              
    break;
}