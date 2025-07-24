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
    
    public function getAwardedBidsCount(){
        
        $con = $GLOBALS["con"];
        
        $sql = "SELECT b.*, t.*, s.* FROM bid b, tender t, supplier s WHERE b.tender_id=t.tender_id AND b.supplier_id=s.supplier_id "
                . "AND b.bid_status='2'";
        
        $result = $con->query($sql) or die($con->error);
        $count = $result->num_rows;
        
        return $count;
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

    public function getMostActiveBidders(){

        $con = $GLOBALS["con"];
        
        $sql = "SELECT s.supplier_id, s.supplier_name, COUNT(b.bid_id) AS bid_count "
             . "FROM supplier s JOIN bid b ON s.supplier_id = b.supplier_id "
             . "WHERE b.bid_status != '-1' "
             . "GROUP BY s.supplier_id, s.supplier_name "
             . "ORDER BY bid_count DESC LIMIT 5";

        $result = $con->query($sql) or die($con->error);
        return $result;
    }

    public function getBidPerformanceBySuppliers(){

        $con = $GLOBALS["con"];

        $sql = "SELECT s.supplier_id, s.supplier_name, COUNT(b.bid_id) AS total_bids, 
                SUM(CASE WHEN b.bid_status IN (2,3) THEN 1 ELSE 0 END) AS won_bids 
                FROM supplier s JOIN bid b ON s.supplier_id=b.supplier_id 
                WHERE b.bid_status !=-1 
                GROUP BY s.supplier_id, s.supplier_name 
                HAVING total_bids >0 
                ORDER BY total_bids DESC 
                LIMIT 5";

        $result = $con->query($sql) or die($con->error);
        return $result;
    }

}
