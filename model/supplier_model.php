<?php

include_once '../commons/db_connection.php';

$dbCon= new DbConnection();

class Supplier{
    
    public function addSupplier($supplierName,$supplierContact,$supplierEmail){
        
        $con = $GLOBALS["con"];
        
        $sql = "INSERT INTO supplier (supplier_name,supplier_contact,supplier_email) "
                . "VALUES (?,?,?)";
        
        $stmt = $con->prepare($sql);
        
        $stmt->bind_param("sss",$supplierName,$supplierContact,$supplierEmail);
        
        $stmt->execute();
        
        $supplierId = $con->insert_id;
        
        $stmt->close();
        
        return $supplierId;
    }
    
    public function getSuppliers(){
        
        $con = $GLOBALS["con"];
        
        $sql = "SELECT * FROM supplier WHERE supplier_status!='-1'";
        
        $result = $con->query($sql) or die($con->error);
        return $result;
    }
    
    public function getSupplier($supplierId){
        
        $con = $GLOBALS["con"];
        
        $sql = "SELECT * FROM supplier WHERE supplier_id=?";
    
        $stmt = $con->prepare($sql);

        $stmt->bind_param("i", $supplierId);

        $stmt->execute();

        $result = $stmt->get_result();

        $stmt->close();

        return $result;
    }
    
    public function updateSupplier($supplierId,$supplierName,$supplierContact,$supplierEmail){
        
        $con = $GLOBALS["con"];
        
        $sql = "UPDATE supplier SET supplier_name=?, supplier_contact=?, supplier_email=? WHERE supplier_id=?";
        
        $stmt = $con->prepare($sql);
        
        $stmt->bind_param("sssi",$supplierName,$supplierContact,$supplierEmail,$supplierId);
        
        $stmt->execute();
        
        $stmt->close();
    }
    
    public function changeSupplierStatus($supplierId,$status){
        
        $con = $GLOBALS["con"];
        
        $sql = "UPDATE supplier SET supplier_status=? WHERE supplier_id=?";
    
        $stmt = $con->prepare($sql);

        $stmt->bind_param("ii", $status, $supplierId); 

        $stmt->execute();

        $stmt->close();
        
    }
    
    public function getActiveSuppliers(){
    
        $con = $GLOBALS["con"];
        
        $sql = "SELECT * FROM supplier WHERE supplier_status='1'";
        
        $result = $con->query($sql) or die($con->error);
        return $result;
        
    }
    
    public function getPaymentPendingSuppliers(){
        
        $con = $GLOBALS["con"];
        
        $sql = "SELECT s.supplier_id, s.supplier_name, COUNT(p.po_id) AS po_count, SUM(p.total_amount) AS total_due FROM "
                . "supplier s, purchase_order p, bid b WHERE p.bid_id=b.bid_id AND b.supplier_id=s.supplier_id "
                . "AND p.po_status='5' GROUP BY s.supplier_id";
        
        $result = $con->query($sql) or die($con->error);
        return $result;
    }
    
    public function getAllSuppliersIncludingRemoved(){
        
        $con = $GLOBALS["con"];
        
        $sql = "SELECT * FROM supplier ORDER BY supplier_name ASC";
        
        $result = $con->query($sql) or die($con->error);
        return $result;
    }

    public function checkIfASupplierHasBidsInAnActiveTender($supplierId) {
        
        $con = $GLOBALS["con"];
        
        $sql = "SELECT COUNT(*) AS bid_count 
        FROM bid b JOIN tender t ON b.tender_id = t.tender_id 
        WHERE b.supplier_id=? AND t.tender_status IN ('1', '2')";

        $stmt = $con->prepare($sql);
        $stmt->bind_param("i", $supplierId);
        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();

        $row = $result->fetch_assoc();
        return $row['bid_count'] > 0;
    }
}