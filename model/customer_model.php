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
    
    public function getActiveCustomerCount(){
        
        $con=$GLOBALS["con"];
        
        $sql = "SELECT * FROM customer WHERE customer_status =1";
        
        $result = $con->query($sql) or die($con->error);
        
        $count = $result->num_rows;
        
        return $count;
    }
    
    public function getCustomerCountWithToursWithinLast7Days(){
        
        $con=$GLOBALS["con"];
        
        $today = new DateTime();
        $todayStr = $today->format("Y-m-d");
        
        $dateBeforeSevenDays = (new DateTime())->sub(new DateInterval("P7D"));
        $dateBeforeSevenDaysStr = $dateBeforeSevenDays->format("Y-m-d");
        
        $sql = "SELECT DISTINCT c.customer_id FROM customer c "
                . "JOIN customer_invoice i ON i.customer_id = c.customer_id "
                . "JOIN tour t ON t.invoice_id = i.invoice_id "
                . "WHERE t.start_date BETWEEN '$dateBeforeSevenDaysStr' AND '$todayStr' ";
        
        $result = $con->query($sql) or die($con->error);
        
        $count =  $result->num_rows;
        
        return $count;
    }

    public function getNewCustomerGrowth(){

        $con=$GLOBALS["con"];

        $sql =  "SELECT
                    first_invoice_date AS acquisition_date,
                    COUNT(customer_id) AS new_customer_count
                FROM (
                    SELECT
                        customer_id,
                        MIN(invoice_date) AS first_invoice_date
                    FROM customer_invoice
                    WHERE invoice_status != -1
                    GROUP BY customer_id
                ) AS first_invoices
                GROUP BY acquisition_date
                ORDER BY acquisition_date ASC;";

        $result = $con->query($sql) or die($con->error);
        return $result;
    }

    public function getCustomersWithTours(){

        $con=$GLOBALS["con"];

        $sql = "SELECT DISTINCT c.* FROM customer c
                JOIN customer_invoice i ON c.customer_id = i.customer_id
                JOIN tour t ON i.invoice_id = t.invoice_id
                WHERE c.customer_status != -1 ";

        $result = $con->query($sql) or die($con->error);
        return $result;
    }  
}