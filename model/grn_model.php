<?php

include_once '../commons/db_connection.php';

$dbCon= new DbConnection();

class GRN{
    
    public function createGRN($grnNumber,$poId,$quantityReceived,$inspectedBy,$grnNotes){
        
        $con = $GLOBALS["con"];
        
        $sql = "INSERT INTO grn (grn_number,po_id,grn_quantity_received,inspected_by,grn_notes) "
                . "VALUES(?,?,?,?,?)";
        
        $stmt = $con->prepare($sql);
        
        $stmt->bind_param("siiis",$grnNumber,$poId,$quantityReceived,$inspectedBy,$grnNotes);
        
        $stmt->execute();
        
        $grnId = $con->insert_id;
        return $grnId;
    }
    
    
}