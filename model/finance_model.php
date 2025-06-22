<?php

include_once '../commons/db_connection.php';

$dbCon= new DbConnection();

class Finance{
    
    public function makeSupplierPayment($date,$amount,$reference,$paymentMethod,$categoryId,$paymentDocument,$paidBy){
        
        $con=$GLOBALS["con"];

        $sql = "INSERT INTO payment (date, amount, reference, payment_method, category_id, payment_document, paid_by) "
                . "VALUES ('$date','$amount','$reference','$paymentMethod','$categoryId','$paymentDocument','$paidBy')";
        
        $con->query($sql) or die($con->error);  
        $paymentId=$con->insert_id;
        return $paymentId;
    }
}