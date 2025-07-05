<?php

include_once '../commons/db_connection.php';

$dbCon= new DbConnection();

class PurchaseOrder{
    
    public function generatePurchaseOrder($poNumber,$bidId,$partId,$quantityOrdered,$poUnitPrice,$totalAmount,$createdBy){
        
        $con = $GLOBALS["con"];
        
        $sql ="INSERT INTO purchase_order (po_number,bid_id,part_id,quantity_ordered,unit_price,total_amount,created_by) "
                . "VALUES (?,?,?,?,?,?,?)";
        
        $stmt = $con->prepare($sql);
        
        $stmt->bind_param("siiiddi",$poNumber,$bidId,$partId,$quantityOrdered,$poUnitPrice,$totalAmount,$createdBy);
        
        $stmt->execute();
        
        $poId = $con->insert_id;
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
        
        $sql = "UPDATE purchase_order SET po_status='$status' WHERE po_id='$poId'";
        
        $con->query($sql) or die($con->error);
    }
    
    public function approvePO($poId,$approvedBy){
        
        $con = $GLOBALS["con"];
        
        $sql = "UPDATE purchase_order SET po_status='2', approved_by='$approvedBy' WHERE po_id='$poId'";
        
        $con->query($sql) or die($con->error);
    }
    
    public function getPO($poId){
        
        $con = $GLOBALS["con"];
        
        $sql = "SELECT po.*, b.*, sp.* FROM purchase_order po, bid b, spare_part sp WHERE po.bid_id=b.bid_id AND po.part_id=sp.part_id "
                . "AND po.po_id='$poId'";
        
        $result = $con->query($sql) or die($con->error);
        return $result;
    }
    
    public function rejectPO($poId,$rejectedBy){
        
        $con = $GLOBALS["con"];
        
        $sql = "UPDATE purchase_order SET po_status='-1', rejected_by='$rejectedBy' WHERE po_id='$poId'";
        
        $con->query($sql) or die($con->error);
    }
}