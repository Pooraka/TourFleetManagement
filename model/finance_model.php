<?php

include_once '../commons/db_connection.php';

$dbCon= new DbConnection();

class Finance{
    
    public function makeServiceStationPayment($date,$amount,$reference,$paymentMethod,$categoryId,$paymentDocument,$paidBy){
        
        $con=$GLOBALS["con"];

        $sql = "INSERT INTO payment (date, amount, reference, payment_method, category_id, payment_document, paid_by) "
                . "VALUES (?,?,?,?,?,?,?)";
        
        $stmt = $con->prepare($sql);
        
        $stmt->bind_param("sdssisi",$date,$amount,$reference,$paymentMethod,$categoryId,$paymentDocument,$paidBy);
        
        $stmt->execute();
         
        $paymentId=$con->insert_id;
        
        $stmt->close();
        return $paymentId;
    }
    
    public function acceptCustomerPayment($invoiceId,$receiptNo,$paymentDate,$paidAmount,$paymentMethod,$paymentProof,$receivedBy){
        
        $con=$GLOBALS["con"];
        
        $sql="INSERT INTO tour_income (invoice_id,receipt_number,payment_date,paid_amount,payment_method,payment_proof,received_by) "
                . "VALUES (?,?,?,?,?,?,?)";
        
        $stmt = $con->prepare($sql);
        
        $stmt->bind_param("issdssi",$invoiceId,$receiptNo,$paymentDate,$paidAmount,$paymentMethod,$paymentProof,$receivedBy);
        
        $stmt->execute();
    }
    
    public function getTourIncomeRecordByInvoiceId($invoiceId){
        
        $con=$GLOBALS["con"];

        $sql = "SELECT * FROM tour_income WHERE invoice_id=? AND payment_status!='-1'";
        
        $stmt = $con->prepare($sql);
            
        $stmt->bind_param("i", $invoiceId);
    
        $stmt->execute();

        $result = $stmt->get_result();

        $stmt->close();

        return $result;
    }
    
    public function getTourIncomeRecords(){
        
        $con=$GLOBALS["con"];

        $sql = "SELECT * FROM tour_income WHERE payment_status!='-1'";
            
        $result = $con->query($sql) or die($con->error); 
        return $result;
    }
    
    public function makeSupplierPayment($date,$amount,$reference,$paymentMethod,$categoryId,$paymentDocument,$paidBy){
        
        $con=$GLOBALS["con"];

        $sql = "INSERT INTO payment (date,amount,reference,payment_method,category_id,payment_document,paid_by) "
                . "VALUES (?,?,?,?,?,?,?)";
        
        $stmt = $con->prepare($sql);
        
        $stmt->bind_param("sdssisi",$date,$amount,$reference,$paymentMethod,$categoryId,$paymentDocument,$paidBy);
        
        $stmt->execute();
         
        $paymentId=$con->insert_id;
        
        $stmt->close();
        return $paymentId;
    }
    
    public function getTourIncomeRecord($tourIncomeId){
        
        $con=$GLOBALS["con"];

        $sql = "SELECT * FROM tour_income WHERE tour_income_id=?";

        $stmt = $con->prepare($sql);

        $stmt->bind_param("i", $tourIncomeId);

        $stmt->execute();

        $result = $stmt->get_result();

        $stmt->close();

        return $result;
    }
    
    public function changeTourIncomeStatus($tourIncomeId,$status){
        
        $con=$GLOBALS["con"];
    
        $sql ="UPDATE tour_income SET payment_status=? WHERE tour_income_id=?";

        $stmt = $con->prepare($sql);

        $stmt->bind_param("ii", $status, $tourIncomeId);

        $stmt->execute();

        $stmt->close();
    }
    
    public function verifyAcceptedCustomerPayment($tourIncomeId,$verifiedBy){
        
        $con=$GLOBALS["con"];
        
        $sql ="UPDATE tour_income SET payment_status='2', verified_by=? WHERE tour_income_id=?";
        
        $stmt = $con->prepare($sql);

        $stmt->bind_param("ii", $verifiedBy, $tourIncomeId);

        $stmt->execute();

        $stmt->close(); 
    }
    
    public function getMonthlySupplierPayments($startMonth,$endMonth){
        
        $con=$GLOBALS["con"];
        
        $sql = "SELECT DATE_FORMAT(date,'%Y-%m') AS month, SUM(amount) AS total_amount "
                . "FROM payment WHERE category_id='2' AND DATE_FORMAT(date, '%Y-%m') BETWEEN ? AND ? "
                . "GROUP BY month ORDER BY month ASC";
        
        $stmt = $con->prepare($sql);
        
        $stmt->bind_param("ss",$startMonth,$endMonth);
        
        $stmt->execute();
        
        $result = $stmt->get_result();
        
        $stmt->close();
        return $result;
    }
}