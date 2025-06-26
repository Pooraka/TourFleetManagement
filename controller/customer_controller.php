<?php
include_once '../commons/session.php';
include_once '../model/customer_model.php';

$customerObj = new Customer();

if(!isset($_GET["status"])){
    ?>
    <script>
        window.location= "../view/login.php";
    </script>
    <?php
}

$status= $_GET["status"];

switch ($status)
{
    case "add_customer":
        
        try{
            
            $fname = ucwords(strtolower($_POST["fname"]));
            $lname = ucwords(strtolower($_POST["lname"]));
            $email = strtolower(trim($_POST["email"]));
            $nic = strtoupper(trim($_POST["nic"]));
            $mno = $_POST["mno"];
            $lno = $_POST["lno"];
            
            $patnic = "/^([0-9]{9}[VX]{1}|[0-9]{12})$/";
            $patmno = "/^07[0-9]{8}$/";
            $patlno = "/^0[0-9]{9}$/";
            
            if($fname==""){
                throw new Exception("First Name cannot be Empty!!!!");
            }
            if($lname==""){
                throw new Exception("Last Name cannot be Empty!!!!");
            }
            if($email==""){
                throw new Exception("Email Name cannot be Empty!!!!");
            }
            if($nic==""){
                throw new Exception("NIC cannot be Empty!!!!");
            }
            if(!preg_match($patnic, $nic)){
                 throw new Exception("Invalid NIC");
            }
            if($mno==""){
                throw new Exception("Mobile Number cannot be Empty!!!!");
            }
            if(!preg_match($patmno, $mno)){
                throw new Exception("Invalid Mobile Number");
            }
            if($lno!="" && !preg_match($patlno, $lno)){
                throw new Exception("Invalid Landline");
            }
            
            $customerResult = $customerObj->checkIfCustomerExist($nic);
            if($customerResult->num_rows>0){
                throw new Exception("Customer Already Exists");
            }
            
            $customerId = $customerObj->addCustomer($nic, $fname, $lname, $email);
            
            if($customerId>0){
                
                $customerObj->addCustomerContact($customerId, $mno, 1);
                
                if($lno!=""){
                    $customerObj->addCustomerContact($customerId, $lno, 2);
                }
            }
            
            $msg = "Customer $fname Added Successfully";
            $msg = base64_encode($msg);

            ?>

            <script>
                window.location="../view/view-customers.php?msg=<?php echo $msg;?>&success=true";
            </script>

            <?php
        }
        catch(Exception $e){
            
            $msg= $e->getMessage();
            $msg= base64_encode($msg);
            ?>
    
            <script>
                window.location="../view/add-customer.php?msg=<?php echo $msg;?>";
            </script>
            <?php
        }
        
    break;
    
    case "remove_customer":
        
        $customerId = $_GET['customer_id'];
        $customerId = base64_decode($customerId);
        
        $customerObj->removeCustomer($customerId);
        
        $msg = "Customer Removed Successfully";
        $msg = base64_encode($msg);
        
        ?>
            <script>
                window.location="../view/view-customers.php?msg=<?php echo $msg;?>&success=true";
            </script>
        <?php
        
    break;

    case "update_customer":
        
        try{
            
            $customerId = $_POST['customer_id'];
            $fname = ucwords(strtolower($_POST["fname"]));
            $lname = ucwords(strtolower($_POST["lname"]));
            $email = strtolower(trim($_POST["email"]));
            $nic = strtoupper(trim($_POST["nic"]));
            $mno = $_POST["mno"];
            $lno = $_POST["lno"];
            $existingNIC = $_POST['existing_nic'];
            
            $patnic = "/^([0-9]{9}[VX]{1}|[0-9]{12})$/";
            $patmno = "/^07[0-9]{8}$/";
            $patlno = "/^0[0-9]{9}$/";
            
            if($fname==""){
                throw new Exception("First Name cannot be Empty!!!!");
            }
            if($lname==""){
                throw new Exception("Last Name cannot be Empty!!!!");
            }
            if($email==""){
                throw new Exception("Email Name cannot be Empty!!!!");
            }
            if($nic==""){
                throw new Exception("NIC cannot be Empty!!!!");
            }
            if(!preg_match($patnic, $nic)){
                 throw new Exception("Invalid NIC");
            }
            if($mno==""){
                throw new Exception("Mobile Number cannot be Empty!!!!");
            }
            if(!preg_match($patmno, $mno)){
                throw new Exception("Invalid Mobile Number");
            }
            if($lno!="" && !preg_match($patlno, $lno)){
                throw new Exception("Invalid Landline");
            }
            
            if($existingNIC!=$nic){
                
                $existingNICResult = $customerObj->checkIfCustomerExist($nic);
                if($existingNICResult->num_rows>0){
                    throw new Exception("NIC Already Exists");
                }
            }
            
            $customerObj->updateCustomer($customerId, $nic, $fname, $lname, $email);
            
            $customerObj->removeCustomerContact($customerId);
            
            $customerObj->addCustomerContact($customerId, $mno, 1);
                
            if($lno!=""){
                $customerObj->addCustomerContact($customerId, $lno, 2);
            }
            
            $msg = "Customer $fname Updated Successfully";
            $msg = base64_encode($msg);

            ?>

            <script>
                window.location="../view/view-customers.php?msg=<?php echo $msg;?>&success=true";
            </script>

            <?php
        }
        catch(Exception $e){
            
            $customerId = base64_encode($customerId);
            
            $msg= $e->getMessage();
            $msg= base64_encode($msg);
            ?>
    
            <script>
                window.location="../view/edit-customer.php?msg=<?php echo $msg;?>&customer_id=<?php echo $customerId;?>";
            </script>
            <?php
        }
}