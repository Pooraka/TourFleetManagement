<?php

include_once '../commons/db_connection.php';

$dbCon= new DbConnection();

class Customer{
    
    public function checkIfCustomerExist($nic){
        
        $con=$GLOBALS["con"];

        $sql="SELECT * FROM customer WHERE customer_nic='$nic'";
        
        $result = $con->query($sql) or die($con->error);  
        return $result;
    }
    
    public function addCustomer($nic,$fname,$lname,$email) {
        
        $con = $GLOBALS["con"];

        $sql = "INSERT INTO customer (customer_nic, customer_fname, customer_lname, customer_email) "
                . "VALUES ('$nic','$fname','$lname','$email')";
        
        $con->query($sql) or die ($con->error);
        $customerId=$con->insert_id;
        return $customerId;  
    }
    
    public function addCustomerContact($customerId,$number,$type){
        
        $con = $GLOBALS["con"];
        
        $sql="INSERT INTO customer_contact (contact_type,contact_number,customer_id) VALUES ('$type','$number','$customerId')";
        
        $con->query($sql) or die ($con->error);
    }
    
    
    public function getCustomers(){
        
        $con=$GLOBALS["con"];

        $sql="SELECT * FROM customer WHERE customer_status !='-1'";
        
        $result = $con->query($sql) or die($con->error);  
        return $result;
    }
    
    public function getCustomerContact($customerId,$contactType){
        
        $con=$GLOBALS["con"];

        $sql="SELECT * FROM customer_contact WHERE customer_id =? AND contact_type=?";
        
        $stmt = $con->prepare($sql);
        
        $stmt->bind_param("ii",$customerId,$contactType);
        
        $stmt->execute();
        
        $result = $stmt->get_result(); 
        return $result;
    }
    
    public function removeCustomer($customerId){
        
        $con = $GLOBALS["con"];
        
        $sql="UPDATE customer SET customer_status='-1' WHERE customer_id='$customerId'";
        
        $con->query($sql) or die ($con->error);
    }
    
    public function getCustomer($customerId){
        
        $con=$GLOBALS["con"];

        $sql="SELECT * FROM customer WHERE customer_id='$customerId'";
        
        $result = $con->query($sql) or die($con->error);  
        return $result;
    }
    
    public function updateCustomer($customerId,$nic,$fname,$lname,$email) {
        
        $con = $GLOBALS["con"];
        
        $sql="UPDATE customer SET customer_fname='$fname', customer_lname='$lname', customer_nic='$nic', customer_email='$email' WHERE customer_id='$customerId'";
        
        $con->query($sql) or die ($con->error);
    }
    
    public function removeCustomerContact($customerId){
        
        $con=$GLOBALS["con"];
        
        $sql="DELETE FROM customer_contact WHERE customer_id='$customerId'";
        
        $con->query($sql) or die($con->error);
    }
}