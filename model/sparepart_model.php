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
        
        $stmt->close();
        
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
        
        $stmt->close();
        
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
        
        $stmt->close();
        
        return $partId;
    }
    
    public function getSpareParts(){
        
        $con = $GLOBALS["con"];
        
        $sql = "SELECT * FROM spare_part WHERE 	part_status!=-1";
        
        $result = $con->query($sql) or die($con->error);  
        return $result;
    }
    
    public function getSparePart($partId){
        
        $con = $GLOBALS["con"];

        $sql = "SELECT * FROM spare_part WHERE part_id=?";
    
        $stmt = $con->prepare($sql);

        $stmt->bind_param("i", $partId);

        $stmt->execute();

        $result = $stmt->get_result();

        $stmt->close();

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
        
        $stmt->close();
        
        return $result->num_rows > 0;
    }
    
    public function updateSparePartType($partId,$partNumber,$partName,$reorderLevel,$description){
        
        $con = $GLOBALS["con"];
        
        $sql = "UPDATE spare_part SET part_number=?, part_name=?, reorder_level=?, description=? WHERE part_id=?";
        
        $stmt = $con->prepare($sql);
        
        $stmt->bind_param("ssisi",$partNumber,$partName,$reorderLevel,$description,$partId);
        
        $stmt->execute();
        
        $stmt->close();
    }
    
    /**
     * 
     * This function is being used to add spare parts to warehouse (For Transaction Records)
     * 
     * @param int $partId
     * @param int $transactionType
     * @param int $quantity
     * @param int $grnId
     * @param String $partTransactionNotes
     * @param int $transactedBy
     * @return int
     */
    public function sparePartAddTransaction($partId,$transactionType,$quantity,$grnId,$partTransactionNotes,$transactedBy){
        
        $con = $GLOBALS["con"];
        
        $sql = "INSERT INTO part_transaction (part_id,transaction_type,quantity,grn_id,part_transaction_notes,transacted_by) "
                . "VALUES(?,?,?,?,?,?)";
        
        $stmt = $con->prepare($sql);
        
        $stmt->bind_param("iiiisi",$partId,$transactionType,$quantity,$grnId,$partTransactionNotes,$transactedBy);
        
        $stmt->execute();
        
        $transactionId = $con->insert_id;
        
        $stmt->close();
        
        return $transactionId;
    }
    
    /**
     * 
     * This function can be used to update spare part's quantity on hand
     * when receiving spare parts (For Transaction Recording)
     * 
     * @param int $partId
     * @param int $quantityOnHand
     */
    public function addSpareParts($partId,$quantityOnHand){
        
        $con = $GLOBALS["con"];
        
        $sql = "UPDATE spare_part SET quantity_on_hand=? WHERE part_id=?";
    
        $stmt = $con->prepare($sql);

        $stmt->bind_param("ii", $quantityOnHand, $partId); 

        $stmt->execute();

        $stmt->close();   
    }
    
    /**
     * 
     * This function is being used to issue spare parts to buses (Transaction Recording)
     * 
     * @param int $partId
     * @param int $transactionType
     * @param int $quantity
     * @param int $busId
     * @param String $partTransactionNotes
     * @param int $transactedBy
     * @return int
     */
    public function sparePartIssueTransaction($partId,$transactionType,$quantity,$busId,$partTransactionNotes,$transactedBy){
        
        $con = $GLOBALS["con"];
        
        $sql = "INSERT INTO part_transaction (part_id,transaction_type,quantity,bus_id,part_transaction_notes,transacted_by) "
                . "VALUES(?,?,?,?,?,?)";
        
        $stmt = $con->prepare($sql);
        
        $stmt->bind_param("iiiisi",$partId,$transactionType,$quantity,$busId,$partTransactionNotes,$transactedBy);
        
        $stmt->execute();
        
        $transactionId = $con->insert_id;
        
        $stmt->close();
        
        return $transactionId;
    }
    
    public function issueSpareParts($partId,$quantityOnHand){
        
        $con = $GLOBALS["con"];
        
        $sql = "UPDATE spare_part SET quantity_on_hand=? WHERE part_id=?";
    
        $stmt = $con->prepare($sql);

        $stmt->bind_param("ii", $quantityOnHand, $partId); 

        $stmt->execute();

        $stmt->close();   
    }
    
    /**
     * 
     * This function being used to remove spare parts (Transaction Recording)
     * 
     * @param int $partId
     * @param int $transactionType
     * @param int $quantity
     * @param String $partTransactionNotes
     * @param int $transactedBy
     * @return int
     */
    public function sparePartRemoveTransaction($partId,$transactionType,$quantity,$partTransactionNotes,$transactedBy){
        
        $con = $GLOBALS["con"];
        
        $sql = "INSERT INTO part_transaction (part_id,transaction_type,quantity,part_transaction_notes,transacted_by) "
                . "VALUES(?,?,?,?,?)";
        
        $stmt = $con->prepare($sql);
        
        $stmt->bind_param("iiisi",$partId,$transactionType,$quantity,$partTransactionNotes,$transactedBy);
        
        $stmt->execute();
        
        $transactionId = $con->insert_id;
        
        $stmt->close();
        
        return $transactionId;
    }
    
    public function removeSpareParts($partId,$quantityOnHand){
        
        $con = $GLOBALS["con"];

        $sql = "UPDATE spare_part SET quantity_on_hand=? WHERE part_id=?";
    
        $stmt = $con->prepare($sql);

        $stmt->bind_param("ii", $quantityOnHand, $partId); 

        $stmt->execute();

        $stmt->close();  
    }
    
    public function getAllTransactionsFiltered($dateFrom, $dateTo,$txnType, $sparePart){
        
        $con = $GLOBALS["con"];

        $sql ="SELECT t.*,s.* FROM part_transaction t JOIN spare_part s ON t.part_id = s.part_id WHERE 1 ";

        if($dateFrom!="" && $dateTo!=""){
            $sql.="AND DATE(t.transacted_at) BETWEEN '$dateFrom' AND '$dateTo' ";
        }

        if($txnType!=""){
            $sql.="AND t.transaction_type='$txnType' ";
        }

        if($sparePart!=""){
            $sql.="AND t.part_id='$sparePart' ";
        }
        
        $sql.="ORDER BY t.transacted_at ASC";
        
        $result = $con->query($sql) or die($con->error);
        return $result;
    }
    
    public function getSparePartsBelowReorderLevel(){
        
        $con = $GLOBALS["con"];
        
        $sql = "SELECT * FROM spare_part WHERE quantity_on_hand<=reorder_level AND part_status!=-1";
        
        $result = $con->query($sql) or die($con->error);
        return $result;
    }
    
    public function getAllSparePartsIncludingRemoved(){
        
        $con = $GLOBALS["con"];
        
        $sql = "SELECT * FROM spare_part";
        
        $result = $con->query($sql) or die($con->error);
        return $result;
    }
    
    public function getSparePartsFiltered($status){
        
        $con = $GLOBALS["con"];
        
        $sql = "SELECT * FROM spare_part WHERE 	part_status!=-1 ";
        
        if($status=="1"){
            $sql.="AND quantity_on_hand>reorder_level ";
        }elseif($status=="2"){
            $sql.="AND quantity_on_hand<=reorder_level ";
        }

        $sql.="ORDER BY part_name ASC";

        $result = $con->query($sql) or die($con->error);
        return $result;
    }

    public function getSparePartCountWith0Stock(){
        $con = $GLOBALS["con"];

        $sql = "SELECT COUNT(*) as count FROM spare_part WHERE quantity_on_hand=0 AND part_status!=-1";

        $result = $con->query($sql) or die($con->error);
        $row = $result->fetch_assoc();
        return $row['count'];
    }

    public function getSparePartCountWithLowStock(){
        $con = $GLOBALS["con"];

        $sql = "SELECT COUNT(*) as count FROM spare_part 
        WHERE quantity_on_hand<=reorder_level AND part_status!=-1 AND quantity_on_hand>0";

        $result = $con->query($sql) or die($con->error);
        $row = $result->fetch_assoc();
        return $row['count'];
    }

    /**
     * Get the inventory value distribution of spare parts.
     * 
     * However this was not used as initial loaded spare parts don't have purchase order details.
     * 
     * @return mysqli_result
     */
    public function getInventoryValueDistribution(){

        $con = $GLOBALS["con"];

        $sql = "SELECT sp.part_name, SUM(sp.quantity_on_hand * po.po_unit_price) AS inventory_value
                FROM spare_part sp
                JOIN purchase_order po ON sp.part_id = po.part_id
                WHERE po.order_date = (SELECT MAX(p.order_date) FROM purchase_order p WHERE p.part_id = sp.part_id) 
                AND sp.part_status != -1 ORDER BY inventory_value DESC";

        $result = $con->query($sql) or die($con->error);
        return $result;
    }

    public function getTop5MostStockedParts() {

        $con = $GLOBALS["con"];

        $sql = "SELECT part_name, quantity_on_hand FROM spare_part 
                WHERE part_status != -1 ORDER BY quantity_on_hand DESC LIMIT 5";

        $result = $con->query($sql) or die($con->error);
        return $result;
    }

    public function getSparePartsYetToReceive() {

        $con = $GLOBALS["con"];

        $sql = "SELECT 
                sp.part_name, 
                sp.part_id, 
                SUM(p.quantity_ordered - p.quantity_received) AS pending_count 
            FROM 
                spare_part sp 
            JOIN 
                purchase_order p ON sp.part_id = p.part_id 
            WHERE 
                p.po_status IN ('3', '4')
            GROUP BY 
                sp.part_name, sp.part_id 
            HAVING 
                pending_count > 0 
            ORDER BY 
                pending_count DESC";

        $result = $con->query($sql) or die($con->error);
        return $result;
    }
}