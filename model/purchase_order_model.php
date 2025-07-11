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
        
        $sql = "UPDATE purchase_order SET po_payment_id=?, po_status='6' WHERE po_id=?";
    
        $stmt = $con->prepare($sql);

        $stmt->bind_param("ii", $paymentId, $poId);

        $stmt->execute();

        $stmt->close();
        
    }
    
    public function getAllPOs(){
        
        $con = $GLOBALS["con"];
        
        $sql = "SELECT * FROM purchase_order";
        
        $result = $con->query($sql) or die ($con->error);
        return $result;
    }
}