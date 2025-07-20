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
    
    public function acceptCustomerPayment($invoiceId,$receiptNo,$paymentDate,$paidAmount,$paymentMethod,$paymentProof,$tour_income_type,$receivedBy){
        
        $con=$GLOBALS["con"];
        
        $sql="INSERT INTO tour_income (invoice_id,receipt_number,payment_date,paid_amount,payment_method,payment_proof,tour_income_type,received_by) "
                . "VALUES (?,?,?,?,?,?,?,?)";
        
        $stmt = $con->prepare($sql);
        
        $stmt->bind_param("issdssii",$invoiceId,$receiptNo,$paymentDate,$paidAmount,$paymentMethod,$paymentProof,$tour_income_type,$receivedBy);
        
        $stmt->execute();
        
        $tourIncomeId = $con->insert_id;
        
        $stmt->close();
        return $tourIncomeId;
    }
    
    public function getTourIncomeRecordByInvoiceIdAndTourIncomeType($invoiceId,$tourIncomeType){
        
        $con=$GLOBALS["con"];

        $sql = "SELECT * FROM tour_income WHERE invoice_id=? AND tour_income_type=? AND payment_status!='-1'";
        
        $stmt = $con->prepare($sql);
            
        $stmt->bind_param("ii", $invoiceId,$tourIncomeType);
    
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
    
    public function getTourIncomeForAPeriod($startDate,$endDate){
        
        $con=$GLOBALS["con"];
        
        $sql = "SELECT DATE_FORMAT(payment_date,'%Y-%m-%d') AS date, SUM(paid_amount) AS total_income FROM tour_income WHERE payment_date BETWEEN ? AND ? "
                . "GROUP BY date ORDER BY date";
        
        $stmt = $con->prepare($sql);
        
        $stmt->bind_param("ss",$startDate,$endDate);
        
        $stmt->execute();
        
        $result = $stmt->get_result();
        
        $stmt->close();
        return $result;
    }
    
    public function makeRefundTransaction($receiptNumber,$invoiceId,$refundAmount,$refundMethod,$tourIncomeType,$issuedBy,$refundReason){
        
        $con=$GLOBALS["con"];
        
        $paymentDate = date("Y-m-d");
        
        $sql = "INSERT INTO tour_income (receipt_number,invoice_id,payment_date,paid_amount,payment_method,tour_income_type,received_by,tour_income_remarks) "
                . "VALUES(?,?,?,?,?,?,?,?)";
        
        $stmt = $con->prepare($sql);
        
        $stmt->bind_param("sisdiiis",$receiptNumber,$invoiceId,$paymentDate,$refundAmount,$refundMethod,$tourIncomeType,$issuedBy,$refundReason);
        
        $stmt->execute();
        
        $transactionId = $con->insert_id;
        return $transactionId;
    }
    
    public function getRefundRecordOfACancelledInvoice($invoiceId){
        
        $con=$GLOBALS["con"];
        
        $sql = "SELECT ci.*,ti.* FROM tour_income ti JOIN customer_invoice ci ON ci.invoice_id=ti.invoice_id "
                . "WHERE ci.invoice_status='-1' AND ti.tour_income_type='3' AND ci.invoice_id=?";
        
        $stmt = $con->prepare($sql);
        
        $stmt->bind_param("i",$invoiceId);
        
        $stmt->execute();
        
        $result = $stmt->get_result();
        
        $stmt->close();
        
        return $result;
    }
    
    public function logInCashBook($txnType,$paymentOrTourIncomeId,$txnDescription,$txnAmount,$txnPerformedBy,$debitCreditFlag){
        
        $con=$GLOBALS["con"];
        
        $sql = "INSERT INTO cash_book (txn_type,payment_id_or_tour_income_id,txn_description,txn_amount,txn_performed_by,debit_credit_flag) "
                . "VALUES(?,?,?,?,?,?)";
        
        $stmt = $con->prepare($sql);
        
        $stmt->bind_param("iisdii",$txnType,$paymentOrTourIncomeId,$txnDescription,$txnAmount,$txnPerformedBy,$debitCreditFlag);
        
        $stmt->execute();
        
        $cashBookId = $con->insert_id;
        
        $stmt->close();
        
        return $cashBookId;
    }
    
    public function getCashFlowFiltered($dateFrom,$dateTo,$txnType){
        
        $con=$GLOBALS["con"];
        
        $sql = "SELECT * FROM cash_book WHERE 1 ";
        
        if($dateFrom!="" && $dateTo!=""){
            $sql.="AND cash_book_txn_date BETWEEN '$dateFrom' AND '$dateTo' ";
        }
        
        if($txnType!=""){
            $sql.= "AND txn_type='$txnType' ";
        }
        
        $sql.= "ORDER BY txn_timestamp ASC";
        
        $result = $con->query($sql) or die($con->error);
        
        return $result;
    }
}