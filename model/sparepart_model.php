<?php

include_once '../commons/db_connection.php';

$dbCon= new DbConnection();

class SparePart{
    
    public function checkIfPartNumberExist($partNumber){
        
        $con = $GLOBALS["con"];
        
        $sql = "SELECT * FROM spare_part WHERE part_number=?";
        
        $stmt = $con->prepare($sql);
        
        $stmt->bind_param("s",$partNumber);
        
        $stmt->execute();
        
        $result =$stmt->get_result();
        
        return $result->num_rows > 0;
    }
    
    public function initialStockLoadTransaction($partId,$quantity,$transactedBy){
        
        $con = $GLOBALS["con"];
        
        $transaction_type = 1;
        $partTransactionNotes = "Initial Spare Part Registration";
        
        $sql = "INSERT INTO part_transaction (part_id,transaction_type,quantity,part_transaction_notes,transacted_by) "
                . "VALUES(?,?,?,?,?);";
        
        $stmt = $con->prepare($sql);
        
        $stmt->bind_param("iiisi",$partId,$transaction_type,$quantity,$partTransactionNotes,$transactedBy);
        
        $stmt->execute();
        
        $transactionId = $con->insert_id;
        return $transactionId;
        
    }
    
    /**
     * 
     * This can register spare part types, however a stock is needed to register
     * 
     * @param String $partNumber
     * @param String $partName
     * @param String $description
     * @param int $quantityOnHand
     * @param int $reorderLevel
     * @return int returns partId
     */
    public function registerSparePart($partNumber,$partName,$description,$quantityOnHand,$reorderLevel){
        
        $con = $GLOBALS["con"];
        
        $sql = "INSERT INTO spare_part (part_number,part_name,description,quantity_on_hand,reorder_level) "
                . "VALUES(?,?,?,?,?)";
        
        $stmt = $con->prepare($sql);
        
        $stmt->bind_param("sssii",$partNumber,$partName,$description,$quantityOnHand,$reorderLevel);
        
        $stmt->execute();
        
        $partId = $con->insert_id;
        return $partId;
    }
    
    public function getSpareParts(){
        
        $con = $GLOBALS["con"];
        
        $sql = "SELECT * FROM spare_part";
        
        $result = $con->query($sql) or die($con->error);  
        return $result;
    }
    
    public function getSparePart($partId){
        
        $con = $GLOBALS["con"];
        
        $sql = "SELECT * FROM spare_part WHERE part_id='$partId'";
        
        $result = $con->query($sql) or die($con->error);  
        return $result;
    }
    
    /**
     * New function for updates.
     * Checks if a part number exists on a DIFFERENT record.
     * @param string $partNumber The part number to check.
     * @param int $partId The ID of the part being edited.
     * @return boolean True if a duplicate exists on another record, false otherwise.
     */
    public function checkDuplicatePartNumberOnUpdate($partNumber, $partId) {
        
        $con = $GLOBALS["con"];

        $sql = "SELECT part_id FROM spare_part WHERE part_number = ? AND part_id != ?";
        
        $stmt = $con->prepare($sql);

        $stmt->bind_param("si",$partNumber,$partId);
        
        $stmt->execute();
        
        $result = $stmt->get_result();
        
        return $result->num_rows > 0;
    }
    
    public function updateSparePartType($partId,$partNumber,$partName,$reorderLevel,$description){
        
        $con = $GLOBALS["con"];
        
        $sql = "UPDATE spare_part SET part_number=?, part_name=?, reorder_level=?, description=? WHERE part_id=?";
        
        $stmt = $con->prepare($sql);
        
        $stmt->bind_param("ssisi",$partNumber,$partName,$reorderLevel,$description,$partId);
        
        $stmt->execute();
    }
    
    public function sparePartAddTransaction($partId,$transactionType,$quantity,$grnId,$partTransactionNotes,$transactedBy){
        
        $con = $GLOBALS["con"];
        
        $sql = "INSERT INTO part_transaction (part_id,transaction_type,quantity,grn_id,part_transaction_notes,transacted_by) "
                . "VALUES(?,?,?,?,?,?)";
        
        $stmt = $con->prepare($sql);
        
        $stmt->bind_param("iiiisi",$partId,$transactionType,$quantity,$grnId,$partTransactionNotes,$transactedBy);
        
        $stmt->execute();
        
        $transactionId = $con->insert_id;
        return $transactionId;
    }
    
    /**
     * 
     * This function can be used to update spare part's quantity on hand
     * when receiving spare parts
     * 
     * @param int $partId
     * @param int $quantityOnHand
     */
    public function addSpareParts($partId,$quantityOnHand){
        
        $con = $GLOBALS["con"];
        
        $sql = "UPDATE spare_part SET quantity_on_hand='$quantityOnHand' WHERE part_id='$partId'";
        
        $con->query($sql) or die($con->error);    
    }
}