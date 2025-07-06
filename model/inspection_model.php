<?php

include_once '../commons/db_connection.php';

$dbCon= new DbConnection();

class Inspection{
    
    public function getAllChecklistItems(){
        
        $con = $GLOBALS["con"];
        
        $sql = "SELECT * FROM checklist_item WHERE checklist_item_status!='-1'";
        
        $result = $con->query($sql) or die($con->error);
        return $result;
    }
    
    public function getChecklistItem($checklistItemId){
        
        $con = $GLOBALS["con"];
        
        $sql = "SELECT * FROM checklist_item WHERE checklist_item_id='$checklistItemId'";
        
        $result = $con->query($sql) or die($con->error);
        return $result;
    }
    
    public function editChecklistItem($checklistItemId,$checklistItemName,$checklistItemDescription){
        
        $con = $GLOBALS["con"];
        
        $sql = "UPDATE checklist_item SET checklist_item_name=?, checklist_item_description=? WHERE checklist_item_id=?";
        
        $stmt = $con->prepare($sql);
        
        $stmt->bind_param("ssi",$checklistItemName,$checklistItemDescription,$checklistItemId);
        
        $stmt->execute();
    }
    
    public function registerChecklistItem($checklistItemName,$checklistItemDescription){
        
        $con = $GLOBALS["con"];
        
        $sql = "INSERT INTO checklist_item (checklist_item_name,checklist_item_description) "
                . "VALUES(?,?)";
        
        $stmt = $con->prepare($sql);
        
        $stmt->bind_param("ss",$checklistItemName,$checklistItemDescription);
        
        $stmt->execute();
        
        $checklistItemId = $con->insert_id;
        return $checklistItemId;
    }
}
