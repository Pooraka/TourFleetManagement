<?php

include_once '../commons/db_connection.php';

$dbCon= new DbConnection();

class Bid{
    
    public function getBidsOfATender($tenderId){
        
        $con = $GLOBALS["con"];
        
        $sql = "SELECT b.*, t.*, s.* FROM bid b, tender t, supplier s WHERE b.tender_id=t.tender_id AND b.supplier_id=s.supplier_id "
                . "AND b.bid_status!='-1' AND b.tender_id='$tenderId'";
        
        $result = $con->query($sql) or die($con->error);
        return $result;
    }
    
    public function addBid($tenderId,$supplierId,$unitPrice){
        
        $con = $GLOBALS["con"];
        
        $sql = "INSERT INTO bid (tender_id,supplier_id,unit_price) VALUES (?,?,?)";
        
        $stmt = $con->prepare($sql);
        
        $stmt->bind_param("iid",$tenderId,$supplierId,$unitPrice);
        
        $stmt->execute();
        
        $bidId = $con->insert_id;
        return $bidId;
    }
    
    public function changeBidStatus($bidId,$status){
        
        $con = $GLOBALS["con"];
        
        $sql = "UPDATE bid SET bid_status='$status' WHERE bid_id='$bidId'";
        
        $con->query($sql) or die($con->error);
    }

}
