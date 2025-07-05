<?php

include_once '../commons/db_connection.php';

$dbCon= new DbConnection();

class Tender{
    
    public function addTender($partId,$quantityRequired,$tenderDescription,$advertisementFileName,$openDate,$closeDate,$createdBy){
        
        $con = $GLOBALS["con"];
        
        $sql = "INSERT INTO tender (part_id,quantity_required,tender_description,advertisement_file_name,open_date,close_date,created_by) "
                . "VALUES(?,?,?,?,?,?,?)";
        
        $stmt = $con->prepare($sql);
        
        $stmt->bind_param("iissssi",$partId,$quantityRequired,$tenderDescription,$advertisementFileName,$openDate,$closeDate,$createdBy);
        
        $stmt->execute();
        
        $tenderId = $con->insert_id;
        return $tenderId;
    }
    
    public function getOpenTenders(){
        
        $con = $GLOBALS["con"];
        
        $sql = "SELECT t.*,s.* FROM tender t, spare_part s WHERE t.part_id=s.part_id AND t.tender_status='1'";
        
        $result = $con->query($sql) or die($con->error);
        return $result;
        
    }
    
    public function changeTenderStatus($tenderId,$status){
        
        $con = $GLOBALS["con"];
        
        $sql = "UPDATE tender SET tender_status='$status' WHERE tender_id='$tenderId'";
        
        $con->query($sql) or die($con->error);
    }
    
    public function getTender($tenderId){
        
        $con = $GLOBALS["con"];
        
        $sql = "SELECT t.*,s.* FROM tender t, spare_part s WHERE t.part_id=s.part_id AND t.tender_id='$tenderId'";
        
        $result = $con->query($sql) or die($con->error);
        return $result;
        
    }
    
    public function addAwardedBidToTender($tenderId,$bidId){
        
        $con = $GLOBALS["con"];
        
        $sql = "UPDATE tender SET awarded_bid='$bidId' WHERE tender_id='$tenderId'";
        
        $con->query($sql) or die($con->error);
        
    }
}
