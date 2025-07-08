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
        
        $sql = "SELECT t.template_id, c.checklist_item_id, c.checklist_item_name, c.checklist_item_description "
                . "FROM template_item_link t, checklist_item c WHERE t.checklist_item_id=c.checklist_item_id "
                . "AND c.checklist_item_status='1' AND template_id='$templateId'";
        
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
        
        $sql = "SELECT * FROM inspection WHERE tour_id=? AND inspection_status IN (1,2,3,4)";

        $stmt = $con->prepare($sql);
        
        $stmt->bind_param("i", $tourId);
        
        $stmt->execute();

        $result = $stmt->get_result();

        return ($result->num_rows > 0);
    }
    
    public function getPendingInspections(){
        
        $con = $GLOBALS["con"];
        
        $sql = "SELECT i.inspection_id,b.*,t.* FROM inspection i, bus b, tour t WHERE i.bus_id=b.bus_id AND i.tour_id=t.tour_id AND i.inspection_status='1'";
        
        $result = $con->query($sql) or die($con->error);
        return $result;
    }
    
    
    public function getInspection($inspectionId){
        
        $con = $GLOBALS["con"];
        
        $sql = "SELECT i.*,b.*,t.* FROM inspection i JOIN tour t ON t.tour_id = i.tour_id JOIN bus b ON b.bus_id = i.bus_id WHERE "
                . "inspection_id=?";
        
        $stmt = $con->prepare($sql);
        
        $stmt->bind_param("i",$inspectionId);
        
        $stmt->execute();
        
        $result = $stmt->get_result();
        return $result;
    }
    
    public function getInspectionChecklistTemplateByBusCategoryId($categoryId){
        
        $con = $GLOBALS["con"];
        
        $sql = "SELECT * FROM inspection_checklist_template WHERE category_id=? AND template_status='1'";
        
        $stmt = $con->prepare($sql);
        
        $stmt->bind_param("i",$categoryId);
        
        $stmt->execute();
        
        $result= $stmt->get_result();
        return $result;
    }
    
    /**
    * Counts the number of checklist items in a given template. 
    * This was used to check if a user submitted items matches with the template count in an inspection
    * @param int $templateId The ID of the template.
    * @return int The total number of items.
    */
    public function countChecklistItemsInTemplate($templateId) {
       
       $con = $GLOBALS["con"];
       
       $sql = "SELECT COUNT(*) AS item_count FROM template_item_link ti, checklist_item c WHERE ti.checklist_item_id = c.checklist_item_id AND "
               . "c.checklist_item_status='1' AND ti.template_id=?";

       $stmt = $con->prepare($sql);
       
       $stmt->bind_param("i", $templateId);
       
       $stmt->execute();

       $result = $stmt->get_result()->fetch_assoc();
       
       return (int)$result['item_count'];
    }
   
    /**
     * 
     * This function used after an inspection is performed to add checklist items responses
     * 
     * @param int $inspectionId
     * @param int $checklistItemId
     * @param int $responseValue
     * @param String $itemComment
     */
    public function addInspectionChecklistResponses($inspectionId,$checklistItemId,$responseValue,$itemComment){
       
       $con = $GLOBALS["con"];
       
       $sql = "INSERT INTO inspection_checklist_response(inspection_id,checklist_item_id,response_value,item_comment) "
               . "VALUES(?,?,?,?)";
       
       $stmt = $con->prepare($sql);
       
       $stmt->bind_param("iiis",$inspectionId,$checklistItemId,$responseValue,$itemComment);
       
       $stmt->execute();
       
       $responseId = $con->insert_id;
       return $responseId;
    }
   
    /**
     * 
     * This function updates final results after an inspection
     * 
     * @param int $inspectionId
     * @param int $busId
     * @param int $tourId
     * @param int $inspectionResult
     * @param String $finalComments
     * @param int $inspectedBy
     * @param int $inspectionStatus
     */
    public function performInspection($inspectionId,$inspectionResult,$finalComments,$inspectedBy,$inspectionStatus){
       
       $con = $GLOBALS["con"];
       
       $date = date('Y-m-d');
       
       $sql = "UPDATE inspection SET inspection_date=?, inspection_result=?, "
               . "final_comments=?, inspected_by=?, inspection_status=? WHERE inspection_id=?";
       
       $stmt = $con->prepare($sql);
       
       $parameterTypes = "sisiii";
       
       $stmt->bind_param($parameterTypes,$date,$inspectionResult,$finalComments,$inspectedBy,$inspectionStatus,$inspectionId);
       
       $stmt->execute();
    }
   
    public function getInspectionResultOfABusAssignedToATour($tourId,$busId){
    
       $con = $GLOBALS["con"];
       
       $sql = "SELECT * FROM inspection WHERE tour_id=? AND bus_id=?";
       
       $stmt = $con->prepare($sql);
       
       $stmt->bind_param("ii",$tourId,$busId);
       
       $stmt->execute();
       
       $result = $stmt->get_result();
       return $result;
   }
   
    public function getFailedInspectionsToAssignNewBus(){
       
        $con = $GLOBALS["con"];
        
        $sql = "SELECT i.*,t.*,b.* FROM inspection i JOIN tour t ON t.tour_id = i.tour_id JOIN bus b ON b.bus_id = i.bus_id "
                . "WHERE i.inspection_status='3'";
        
        $result = $con->query($sql) or die($con->error);
        return $result;
    }
    
    public function changeInspectionStatus($inspectionId,$status){
        
        $con = $GLOBALS["con"];
        
        $sql = "UPDATE inspection SET inspection_status=? WHERE inspection_id=?";
        
        $stmt = $con->prepare($sql);
        
        $stmt->bind_param("ii",$status,$inspectionId);
        
        $stmt->execute();
    }
}
