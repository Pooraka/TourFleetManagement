<?php

include_once '../commons/db_connection.php';

$dbCon= new DbConnection();

class Customer{
    
    public function checkIfCustomerExist($nic){
        
        $con=$GLOBALS["con"];

        $sql="SELECT * FROM customer WHERE customer_nic=?";
        
        $stmt = $con->prepare($sql);
        
        $stmt->bind_param("s",$nic);
        
        $stmt->execute();
        
        $result = $stmt->get_result();
        
        $stmt->close();
        return $result;
    }
    
    public function addCustomer($nic,$fname,$lname,$email) {
        
        $con = $GLOBALS["con"];
        
        $sql = "INSERT INTO customer (customer_nic, customer_fname, customer_lname, customer_email) VALUES (?,?,?,?)";
          
        $stmt = $con->prepare($sql);
    
        $stmt->bind_param("ssss", $nic, $fname, $lname, $email);

        $stmt->execute();

        $customerId=$con->insert_id;

        $stmt->close();

        return $customerId;  
    }
    
    public function addCustomerContact($customerId,$number,$type){
        
        $con = $GLOBALS["con"];

        $sql="INSERT INTO customer_contact (contact_type,contact_number,customer_id) VALUES (?,?,?)";
        
        $stmt = $con->prepare($sql);
    
        $stmt->bind_param("isi", $type, $number, $customerId);
    
        $stmt->execute();

        $stmt->close();
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
        
        $stmt->close();
        return $result;
    }
    
    public function removeCustomer($customerId){
        
        $con = $GLOBALS["con"];
        
        $sql="UPDATE customer SET customer_status='-1' WHERE customer_id=?";
        
        $stmt = $con->prepare($sql);
        
        $stmt->bind_param("i",$customerId);
        
        $stmt->execute();
        
        $stmt->close();
    }
    
    public function getCustomer($customerId){
        
        $con=$GLOBALS["con"];
        
        $sql="SELECT * FROM customer WHERE customer_id=?";
    
        $stmt = $con->prepare($sql);

        $stmt->bind_param("i", $customerId);

        $stmt->execute();

        $result = $stmt->get_result();

        $stmt->close();

        return $result;
    }
    
    public function updateCustomer($customerId,$nic,$fname,$lname,$email) {
        
        $con = $GLOBALS["con"];

        $sql="UPDATE customer SET customer_fname=?, customer_lname=?, customer_nic=?, customer_email=? WHERE customer_id=?";
        
        $stmt = $con->prepare($sql);
        
        $stmt->bind_param("ssssi", $fname, $lname, $nic, $email, $customerId); 
        
        $stmt->execute();
    
        $stmt->close();
    }
    
    public function removeCustomerContact($customerId){
        
        $con=$GLOBALS["con"];
    
        $sql="DELETE FROM customer_contact WHERE customer_id=?";

        $stmt = $con->prepare($sql);

        $stmt->bind_param("i", $customerId); 

        $stmt->execute();

        $stmt->close();
    }
}