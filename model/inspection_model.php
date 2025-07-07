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
        
        $sql = "SELECT * FROM checklist_item WHERE checklist_item_id=?";

        $stmt = $con->prepare($sql);
        
        $stmt->bind_param("i", $checklistItemId);
        
        $stmt->execute();

        $result = $stmt->get_result();
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
    
    public function changeChecklistItemStatus($checklistItemId,$status){
        
        $con = $GLOBALS["con"];
        
        $sql = "UPDATE checklist_item SET checklist_item_status='$status' WHERE checklist_item_id='$checklistItemId'";
        
        $con->query($sql) or die($con->error);
    }
    
    public function getInspectionChecklistTemplates(){
        
        $con = $GLOBALS["con"];
        
        $sql = "SELECT * FROM inspection_checklist_template WHERE template_status='1'";
        
        $result = $con->query($sql) or die($con->error);
        return $result;
    }
    
    public function getInspectionChecklistTemplate($templateId){
        
        $con = $GLOBALS["con"];
        
        $sql = "SELECT * FROM inspection_checklist_template WHERE template_id='$templateId'";
        
        $result = $con->query($sql) or die($con->error);
        return $result;
    }
    
    public function getChecklistItemsInATemplate($templateId){
        
        $con = $GLOBALS["con"];
        
        $sql = "SELECT * FROM template_item_link WHERE template_id='$templateId'";
        
        $result = $con->query($sql) or die($con->error);
        return $result;
        
    }
    
    public function removeAllChecklistItemsFromTemplate($templateId){
        
        $con = $GLOBALS["con"];
        
        $sql = "DELETE FROM template_item_link WHERE template_id = ?";

        $stmt = $con->prepare($sql);

        $stmt->bind_param("i",$templateId);

        $stmt->execute();
    }
    
    public function addChecklistItemsToTemplate($templateId,$checklistItemId){
        
        $con = $GLOBALS["con"];
        
        $sql = "INSERT INTO template_item_link(template_id,checklist_item_id) VALUES(?,?)";
        
        $stmt = $con->prepare($sql);

        $stmt->bind_param("ii",$templateId,$checklistItemId);

        $stmt->execute();
    }
    
    public function scheduleInspection($busId,$tourId){
        
        $con = $GLOBALS["con"];
        
        $sql = "INSERT INTO inspection (bus_id,tour_id) VALUES (?,?)";
        
        $stmt = $con->prepare($sql);

        $stmt->bind_param("ii",$busId,$tourId);

        $stmt->execute();
    }
    
    /**
     * 
     * This function is used to check if inspections exists before scheduling inspections pre-tour
     * 
     * @param int $tourId
     * @return Boolean 
     */
    public function checkIfInspectionExistsForTour($tourId) {
        
        $con = $GLOBALS["con"];
        
        $sql = "SELECT * FROM inspection WHERE tour_id=? AND inspection_status IN (1,2)";

        $stmt = $con->prepare($sql);
        
        $stmt->bind_param("i", $tourId);
        
        $stmt->execute();

        $result = $stmt->get_result();

        return ($result->num_rows > 0);
    }
}
