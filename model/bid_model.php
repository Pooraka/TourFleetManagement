<?php

include_once '../commons/db_connection.php';

$dbCon= new DbConnection();

class Bid{
    
    public function getBidsOfATender($tenderId){
        
        $con = $GLOBALS["con"];        
        
        $sql = "SELECT b.*, t.*, s.* FROM bid b, tender t, supplier s WHERE b.tender_id=t.tender_id AND b.supplier_id=s.supplier_id "
            . "AND b.bid_status!='-1' AND b.tender_id=?";
        
        $stmt = $con->prepare($sql);
    
        $stmt->bind_param("i", $tenderId);

        $stmt->execute();

        $result = $stmt->get_result();

        $stmt->close();

        return $result;
    }
    
    public function getBidsOfATenderIncludingRemoved($tenderId){
        
        $con = $GLOBALS["con"];        
        
        $sql = "SELECT b.*, t.*, s.* FROM bid b, tender t, supplier s WHERE b.tender_id=t.tender_id AND b.supplier_id=s.supplier_id "
            . "AND b.tender_id=?";
        
        $stmt = $con->prepare($sql);
    
        $stmt->bind_param("i", $tenderId);

        $stmt->execute();

        $result = $stmt->get_result();

        $stmt->close();

        return $result;
    }
    
    public function addBid($tenderId,$supplierId,$unitPrice){
        
        $con = $GLOBALS["con"];
        
        $sql = "INSERT INTO bid (tender_id,supplier_id,unit_price) VALUES (?,?,?)";
        
        $stmt = $con->prepare($sql);
        
        $stmt->bind_param("iid",$tenderId,$supplierId,$unitPrice);
        
        $stmt->execute();
        
        $bidId = $con->insert_id;
        
        $stmt->close();
        
        return $bidId;
    }
    
    public function changeBidStatus($bidId,$status){
        
        $con = $GLOBALS["con"];
    
        $sql = "UPDATE bid SET bid_status=? WHERE bid_id=?";

        $stmt = $con->prepare($sql);

        $stmt->bind_param("ii", $status, $bidId);

        $stmt->execute();

        $stmt->close();
    }
    
    public function getAwardedBids(){
        
        $con = $GLOBALS["con"];
        
        $sql = "SELECT b.*, t.*, s.* FROM bid b, tender t, supplier s WHERE b.tender_id=t.tender_id AND b.supplier_id=s.supplier_id "
                . "AND b.bid_status='2'";
        
        $result = $con->query($sql) or die($con->error);
        return $result;
    }
    
    public function getBid($bidId){
        
        $con = $GLOBALS["con"];
    
        $sql = "SELECT b.*, t.*, s.* FROM bid b, tender t, supplier s WHERE b.tender_id=t.tender_id AND b.supplier_id=s.supplier_id "
                . "AND b.bid_id=?";

        $stmt = $con->prepare($sql);

        $stmt->bind_param("i", $bidId);

        $stmt->execute();

        $result = $stmt->get_result();

        $stmt->close();

        return $result;
    }

}
