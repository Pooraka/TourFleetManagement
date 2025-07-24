<?php

include_once '../commons/db_connection.php';

$dbCon= new DbConnection();

class PurchaseOrder{
    
    public function generatePurchaseOrder($poNumber,$bidId,$partId,$quantityOrdered,$poUnitPrice,$totalAmount,$createdBy){
        
        $con = $GLOBALS["con"];
        
        $sql ="INSERT INTO purchase_order (po_number,bid_id,part_id,quantity_ordered,po_unit_price,total_amount,created_by) "
                . "VALUES (?,?,?,?,?,?,?)";
        
        $stmt = $con->prepare($sql);
        
        $stmt->bind_param("siiiddi",$poNumber,$bidId,$partId,$quantityOrdered,$poUnitPrice,$totalAmount,$createdBy);
        
        $stmt->execute();
        
        $poId = $con->insert_id;
        
        $stmt->close();
        return $poId;
    }
    
    public function getPendingPO(){
        
        $con = $GLOBALS["con"];
        
        $sql = "SELECT po.*, b.*, sp.* FROM purchase_order po, bid b, spare_part sp WHERE po.bid_id=b.bid_id AND po.part_id=sp.part_id "
                . "AND po.po_status IN (1,2)";
        
        $result = $con->query($sql) or die($con->error);
        return $result;
    }
    
    public function changePOStatus($poId,$status){
        
        $con = $GLOBALS["con"];
        
        $sql = "UPDATE purchase_order SET po_status=? WHERE po_id=?";
    
        $stmt = $con->prepare($sql);

        $stmt->bind_param("ii", $status, $poId);

        $stmt->execute();

        $stmt->close();
    }
    
    public function approvePO($poId,$approvedBy){
        
        $con = $GLOBALS["con"];

        $sql = "UPDATE purchase_order SET po_status='2', approved_by=? WHERE po_id=?";
    
        $stmt = $con->prepare($sql);

        $stmt->bind_param("ii", $approvedBy, $poId); 

        $stmt->execute();

        $stmt->close();
    }
    
    public function getPO($poId){
        
        $con = $GLOBALS["con"];
        
        $sql = "SELECT po.*, b.*, sp.* FROM purchase_order po, bid b, spare_part sp WHERE po.bid_id=b.bid_id AND po.part_id=sp.part_id "
            . "AND po.po_id=?";
    
        $stmt = $con->prepare($sql);

        $stmt->bind_param("i", $poId); 

        $stmt->execute();

        $result = $stmt->get_result();

        $stmt->close();

        return $result;
    }
    
    public function rejectPO($poId,$rejectedBy){
        
        $con = $GLOBALS["con"];

        $sql = "UPDATE purchase_order SET po_status='-1', rejected_by=? WHERE po_id=?";
    
        $stmt = $con->prepare($sql);

        $stmt->bind_param("ii", $rejectedBy, $poId); 

        $stmt->execute();

        $stmt->close();
    }
    
    public function addSupplierInvoice($poId,$supplierInvoice,$supplierInvoiceNumber){
        
        $con = $GLOBALS["con"];
        
        $sql = "UPDATE purchase_order SET supplier_invoice=?,supplier_invoice_number=? WHERE po_id=?";
        
        $stmt = $con->prepare($sql);
        
        $stmt->bind_param("ssi",$supplierInvoice,$supplierInvoiceNumber,$poId);
        
        $stmt->execute();
        
        $stmt->close();
    }
    
    public function getPOToAddSpareParts(){
        
        $con = $GLOBALS["con"];
        
        $sql = "SELECT po.*, b.*, sp.* FROM purchase_order po, bid b, spare_part sp WHERE po.bid_id=b.bid_id AND po.part_id=sp.part_id "
                . "AND po.po_status IN (3,4)";
        
        $result = $con->query($sql) or die($con->error);
        return $result;
    }
    
    
    /**
     * 
     * This function is used to update the PO when a GRN is created
     * 
     * @param int $poId
     * @param int $quantityReceived
     * @param int $poStatus
     */
    public function updatePOWhenGRNCreated($poId,$quantityReceived,$poStatus){
        
        $con = $GLOBALS["con"];
        
        $sql = "UPDATE purchase_order SET quantity_received=?, po_status=? WHERE po_id=?";
    
        $stmt = $con->prepare($sql);

        $stmt->bind_param("iii", $quantityReceived, $poStatus, $poId); 

        $stmt->execute();

        $stmt->close();
    }
    
    public function getPaymentPendingInvoices($supplierId){
        
        $con = $GLOBALS["con"];
        
        $sql = "SELECT p.* FROM purchase_order p, supplier s, bid b "
            . "WHERE p.bid_id=b.bid_id AND b.supplier_id=s.supplier_id AND s.supplier_id=? AND p.po_status='5'";
    
        $stmt = $con->prepare($sql);

        $stmt->bind_param("i", $supplierId);

        $stmt->execute();

        $result = $stmt->get_result();

        $stmt->close();

        return $result;
    }
    
    public function updatePaidPOs($poId,$paymentId){
        
        $con = $GLOBALS["con"];
        
        $poPaidDate = date("Y-m-d");
        
        $sql = "UPDATE purchase_order SET po_payment_id=?, po_status='6', po_paid_date='$poPaidDate' WHERE po_id=?";
    
        $stmt = $con->prepare($sql);

        $stmt->bind_param("ii", $paymentId, $poId);

        $stmt->execute();

        $stmt->close();
        
    }
    
    public function getAllPOsFiltered($dateFrom,$dateTo,$partId,$poStatus){
        
        $con = $GLOBALS["con"];
        
        $sql = "SELECT p.*,b.* FROM purchase_order p JOIN bid b ON p.bid_id = b.bid_id WHERE 1 ";
        
        if($dateFrom!="" && $dateTo!=""){
            
            $sql.="AND p.order_date BETWEEN '$dateFrom' AND '$dateTo' ";
        }
        
        if($partId!=""){
            
            $sql.="AND p.part_id='$partId' ";
        }
        
        if($poStatus=="2"){
            
            $sql.="AND p.po_status IN (2,3) ";
        }elseif($poStatus!=""){
            $sql.="AND p.po_status='$poStatus' ";   
        }
        
        $sql.="ORDER BY p.order_date ASC";
        
        $result = $con->query($sql) or die ($con->error);
        return $result;
    }

    public function getSupplierCostTrend($dateFrom, $dateTo, $supplierId){

        $con = $GLOBALS["con"];
        
        $sql="SELECT p.po_paid_date, SUM(p.total_amount) AS total_amount
              FROM purchase_order p
              JOIN bid b ON p.bid_id = b.bid_id
              WHERE p.po_status = '6' ";

        if($dateFrom!="" && $dateTo!=""){
            $sql.="AND p.po_paid_date BETWEEN '$dateFrom' AND '$dateTo' ";
        }

        if($supplierId!=""){
            $sql.="AND b.supplier_id = '$supplierId' ";
        }

        $sql.="GROUP BY p.po_paid_date ORDER BY p.po_paid_date ASC";

        $result = $con->query($sql) or die($con->error);
        return $result;
    }
    
        
    public function getMonthlySupplierPayments($startMonth,$endMonth,$supplierId){
        
        
        $con=$GLOBALS["con"];
        
        $sql = "SELECT DATE_FORMAT(p.po_paid_date, '%Y-%m') AS month, SUM(p.total_amount) AS total_amount  
                FROM purchase_order p JOIN bid b ON p.bid_id = b.bid_id WHERE p.po_status = '6' ";

        if($startMonth!="" && $endMonth!=""){
            $sql .= "AND DATE_FORMAT(p.po_paid_date, '%Y-%m') BETWEEN '$startMonth' AND '$endMonth' ";
        }

        if($supplierId!=""){
            $sql .= "AND b.supplier_id = '$supplierId' ";
        }

        $sql .= "GROUP BY month ORDER BY month ASC";

        $result = $con->query($sql) or die($con->error);
        return $result;
    }
    
    public function getPastPOsFiltered($dateFrom,$dateTo,$partId,$poStatus){
        
        $con = $GLOBALS["con"];
        
        $sql = "SELECT p.*,b.* FROM purchase_order p JOIN bid b ON p.bid_id = b.bid_id WHERE 1 ";
        
        if($dateFrom!="" && $dateTo!=""){
            
            $sql.="AND p.order_date BETWEEN '$dateFrom' AND '$dateTo' ";
        }
        
        if($partId!=""){
            
            $sql.="AND p.part_id='$partId' ";
        }
        
        if($poStatus==""){
            
            $sql.="AND p.po_status IN (3,4,5,6) ";
        }else{
            
            $sql.="AND p.po_status='$poStatus' ";
        }
        
        $sql.="ORDER BY p.order_date ASC";
        
        $result = $con->query($sql) or die ($con->error);
        return $result;
    }
    
    public function getPOListOfPayment($paymentId){
        
        $con = $GLOBALS["con"];

        $sql = "SELECT p.*, b.*, sp.* FROM purchase_order p JOIN bid b ON p.bid_id = b.bid_id ".
                "JOIN spare_part sp ON p.part_id = sp.part_id WHERE p.po_payment_id = ?";

        $stmt = $con->prepare($sql);

        $stmt->bind_param("i", $paymentId);

        $stmt->execute();
        
        $result = $stmt->get_result();
        return $result;
    }

    public function getPurchaseOrderPipelineWithinLast14Days() {
        
        $con = $GLOBALS["con"];

        $fourteenDaysAgo = date('Y-m-d', strtotime('-14 days'));

        $sql = "SELECT
                    po_status,
                    CASE
                        WHEN po_status = 1 THEN 'Initiated'
                        WHEN po_status = 2 THEN 'Approved'
                        WHEN po_status = 3 THEN 'Invoice Attached'
                        WHEN po_status = 4 THEN 'Partially Received'
                        WHEN po_status = 5 THEN 'Completed'
                        WHEN po_status = 6 THEN 'Paid'
                        ELSE 'Unknown'
                    END AS status_name,
                    COUNT(po_id) AS po_count
                FROM purchase_order
                WHERE 
                    po_status != -1 
                    AND order_date >= '$fourteenDaysAgo' 
                GROUP BY po_status, status_name ORDER BY po_count DESC";

        $result = $con->query($sql) or die($con->error);
        return $result;
    }

    public function getPOCountPendingApproval() {
        $con = $GLOBALS["con"];

        $sql = "SELECT COUNT(po_id) AS count FROM purchase_order WHERE po_status = 1";

        $result = $con->query($sql) or die($con->error);
        
        $row = $result->fetch_assoc();
        return $row['count'];
    }

    public function getPOCountPendingSupplierInvoice() {

        $con = $GLOBALS["con"];

        $sql = "SELECT COUNT(po_id) AS count FROM purchase_order WHERE po_status = 2 AND supplier_invoice IS NULL";

        $result = $con->query($sql) or die($con->error);

        $row = $result->fetch_assoc();
        return $row['count'];
    }

    public function getMostSpendingBySupplier(){

        $con = $GLOBALS["con"];

        $sql = "SELECT 
                    s.supplier_name,
                    SUM(po.total_amount) AS total_spent 
                FROM purchase_order po 
                JOIN bid b ON po.bid_id = b.bid_id 
                JOIN supplier s ON b.supplier_id = s.supplier_id 
                WHERE po.po_status = 6 
                GROUP BY s.supplier_name 
                ORDER BY total_spent DESC 
                LIMIT 4;";

        $result = $con->query($sql) or die($con->error);
        return $result;
    }

}