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
        
        $sql = "SELECT * FROM supplier WHERE supplier_id='$supplierId'";
        
        $result = $con->query($sql) or die($con->error);
        return $result;
    }
    
    public function updateSupplier($supplierId,$supplierName,$supplierContact,$supplierEmail){
        
        $con = $GLOBALS["con"];
        
        $sql = "UPDATE supplier SET supplier_name=?, supplier_contact=?, supplier_email=? WHERE supplier_id=?";
        
        $stmt = $con->prepare($sql);
        
        $stmt->bind_param("sssi",$supplierName,$supplierContact,$supplierEmail,$supplierId);
        
        $stmt->execute();
    }
    
    public function changeSupplierStatus($supplierId,$status){
        
        $con = $GLOBALS["con"];
        
        $sql = "UPDATE supplier SET supplier_status='$status' WHERE supplier_id='$supplierId'";
        
        $con->query($sql) or die($con->error);
        
    }
    
    public function getActiveSuppliers(){
    
        $con = $GLOBALS["con"];
        
        $sql = "SELECT * FROM supplier WHERE supplier_status='1'";
        
        $result = $con->query($sql) or die($con->error);
        return $result;
        
    }
}