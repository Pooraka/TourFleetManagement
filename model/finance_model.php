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
    
    public function getTourIncomeTrend($dateFrom, $dateTo){
        
        $con=$GLOBALS["con"];
        
        $sql = "SELECT ti.payment_date, SUM(ti.paid_amount) AS amount "
                . "FROM tour_income ti JOIN customer_invoice ci ON ci.invoice_id=ti.invoice_id "
                . "JOIN customer c ON c.customer_id=ci.customer_id WHERE ti.payment_status ='1' ";
        
        if($dateFrom!=="" && $dateTo!=""){
            
            $sql.="AND ti.payment_date BETWEEN '$dateFrom' AND '$dateTo' ";
        }
        
        $sql.="GROUP BY ti.payment_date ORDER BY ti.payment_date ASC";
        
        $result = $con->query($sql) or die($con->error);
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

    public function getPastPayments($dateFrom, $dateTo, $txnCategory,$paymentMethod){

        $con=$GLOBALS["con"];

        $sql = "SELECT p.*,tc.category FROM payment p JOIN transaction_category tc ON p.category_id=tc.category_id WHERE 1 ";

        if($dateFrom!="" && $dateTo!=""){
            $sql .= "AND p.date BETWEEN '$dateFrom' AND '$dateTo' ";
        }

        if($txnCategory!=""){
            $sql .= "AND p.category_id='$txnCategory' ";
        }

        if($paymentMethod!=""){
            $sql .= "AND p.payment_method='$paymentMethod' ";
        }

        $sql .= "ORDER BY p.date DESC";

        $result = $con->query($sql) or die($con->error);
        return $result;
    }

    public function getPendingServicePaymentCount(){
        
        $con=$GLOBALS["con"];
        
        $sql = "SELECT COUNT(*) AS count FROM service_Detail WHERE service_status = 2";

        $result = $con->query($sql) or die($con->error);
        $row = $result->fetch_assoc();
        return $row['count'];
    }

    public function getPendingSupplierPaymentCount(){
        
        $con=$GLOBALS["con"];
        
        $sql = "SELECT COUNT(*) AS count FROM purchase_order WHERE po_status = 5";

        $result = $con->query($sql) or die($con->error);
        $row = $result->fetch_assoc();
        return $row['count'];
    }

    public function getExpenseBreakdown(){

        $dateFrom ="";
        $dateTo = "";
        $month = "";

        $con=$GLOBALS["con"];

        $sql = "SELECT SUM(ABS(txn_amount)) AS expenses, txn_description FROM cash_book 
                WHERE txn_type IN(1,2) AND debit_credit_flag=1 ";

        if($dateFrom!="" && $dateTo!=""){
            $sql .= "AND cash_book_txn_date BETWEEN '$dateFrom' AND '$dateTo' ";
        }
        if($month!=""){
            $sql .= "AND DATE_FORMAT(cash_book_txn_date, '%Y-%m') = '$month' ";
        }

        $sql .= "GROUP BY txn_description";

        $result = $con->query($sql) or die($con->error);
        return $result;
    }

    public function getRevenueByBusCategory(){

        $con=$GLOBALS["con"];

        $sql = "SELECT
                    bc.category_name,
                    SUM(ci.paid_amount) AS total_revenue
                FROM customer_invoice ci
                JOIN customer_invoice_item cii ON ci.invoice_id = cii.invoice_id
                JOIN bus_category bc ON cii.category_id = bc.category_id 
                GROUP BY bc.category_name";

        $result = $con->query($sql) or die($con->error);
        return $result; 
    }
}