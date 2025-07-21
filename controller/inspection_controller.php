<?php
include_once '../commons/session.php';
include_once '../model/inspection_model.php';
include_once '../model/bus_model.php';



//get user information from session
$userSession=$_SESSION["user"];
$userId = $userSession['user_id'];



if(!isset($_GET["status"])){
    ?>
    <script>
        window.location= "../view/login.php";
    </script>
    <?php
}

$inspectionObj = new Inspection();
$busObj = new Bus();

$status= $_GET["status"];

switch ($status){
    
    case "get_checklist_item_edit_info":
        
        $checklistItemId = $_POST['checklistItemId'];
        
        $inspectionResult = $inspectionObj->getChecklistItem($checklistItemId);
        $inspectionRow = $inspectionResult->fetch_assoc();
        
        ?>
            <div class="row">
                <div class="col-md-2">
                    <label class="control-label">Item Name</label>
                </div>
                <div class="col-md-4">
                    <input type="text" class="form-control" name="checklist_item_name" id="checklist_item_name" value="<?php echo $inspectionRow['checklist_item_name'];?>"/>
                    <input type="hidden" name="checklist_item_id" value="<?php echo $checklistItemId;?>"/>
                </div>
                <div class="col-md-2">
                    <label class="control-label">Item Description</label>
                </div>
                <div class="col-md-4">
                    <textarea id="checklist_item_description" name="checklist_item_description" rows="3" class="form-control"><?php echo $inspectionRow['checklist_item_description'];?></textarea>
                </div>
            </div> 
        <?php
        
    break;

    case "edit_checklist_item":
        
        try{
        
            $checklistItemId = $_POST["checklist_item_id"];
            
            $checklistItemName = $_POST["checklist_item_name"];
            
            if($checklistItemName==""){
                throw new Exception("Checklist Item Name Cannot Be Empty");
            }
            
            $checklistItemDescription = $_POST["checklist_item_description"];
            
            if($checklistItemDescription==""){
                throw new Exception("Checklist Item Description Cannot Be Empty");
            }
            
            $inspectionObj->editChecklistItem($checklistItemId,$checklistItemName,$checklistItemDescription);
            
            $msg = "Checklist Item Edited Successfully";
            $msg = base64_encode($msg);
            ?>

                <script>
                    window.location="../view/manage-checklist-items.php?msg=<?php echo $msg; ?>&success=true";
                </script>

            <?php
        
        }
        catch(Exception $e){
            
            $msg= $e->getMessage();
            $msg= base64_encode($msg);
            ?>
    
            <script>
                window.location="../view/manage-checklist-items.php?msg=<?php echo $msg;?>";
            </script>
            <?php
        }
        
    break;
    
    case "register_checklist_item":
        
        try{
        
            $checklistItemName = $_POST["checklist_item_name"];
            
            if($checklistItemName==""){
                throw new Exception("Checklist Item Name Cannot Be Empty");
            }
            
            $checklistItemDescription = $_POST["checklist_item_description"];
            
            if($checklistItemDescription==""){
                throw new Exception("Checklist Item Description Cannot Be Empty");
            }
        
            $checklistItemId = $inspectionObj->registerChecklistItem($checklistItemName,$checklistItemDescription);
            
            $msg = "Checklist Item $checklistItemName Registered Successfully";
            $msg = base64_encode($msg);
            ?>

                <script>
                    window.location="../view/manage-checklist-items.php?msg=<?php echo $msg; ?>&success=true";
                </script>

            <?php
        }
        catch(Exception $e){
            
            $msg= $e->getMessage();
            $msg= base64_encode($msg);
            ?>
    
            <script>
                window.location="../view/manage-checklist-items.php?msg=<?php echo $msg;?>";
            </script>
            <?php
        }
        
    break;

    case "remove_checklist_item":
        
        $checklistItemId = base64_decode($_GET["checklist_item_id"]);
        
        $inspectionObj->changeChecklistItemStatus($checklistItemId,-1);
        
        $msg = "Checklist Item Activated Successfully";
        $msg = base64_encode($msg);
        ?>

            <script>
                window.location="../view/manage-checklist-items.php?msg=<?php echo $msg; ?>&success=true";
            </script>

        <?php
        
    break;

    case "load_template_and_checklist":
        
        $templateId = $_POST['templateId'];
        
        $templateResult = $inspectionObj->getInspectionChecklistTemplate($templateId);
        $templateRow = $templateResult->fetch_assoc();
        
        $allChecklistItemsResult = $inspectionObj->getAllChecklistItems();
        
        //This is used to get already selected items and tick the check boxes
        $checklistItemsAlreadyInTemplateResult = $inspectionObj->getChecklistItemsInATemplate($templateId);
        
        $checklistItemArray = array();
        
        while($checklistItemsAlreadyInTemplateRow = $checklistItemsAlreadyInTemplateResult->fetch_assoc()){
            
            array_push($checklistItemArray,$checklistItemsAlreadyInTemplateRow['checklist_item_id']);
        }
        
        ?>
            <div class="row">
                <div class="col-md-3">
                    <label class="control-label">Description</label>
                </div>
                <div class="col-md-9">
                    <span><?php echo $templateRow['template_description'];?></span>
                </div>
            </div>
            <div class="row">
                &nbsp;
            </div>
            <div class="row">
                <div class="col-md-9">
                    <label class="control-label">Once Desired Items Are Selected Click On "Update" Button</label>
                </div>
                <div class="col-md-3 text-right">
                    <input type="submit" class="btn btn-success" value="Update"/>
                </div>
            </div>
            <div class="row">
                &nbsp;
            </div>
            <div class="row">
                <div class="col-md-12">
                    <button type="button" id="select_all_btn" class="btn btn-sm btn-info" onclick="selectAllItems()" style="width:100px">Select All</button>
                    <button type="button" id="select_none_btn" class="btn btn-sm btn-warning" onclick="selectNoneItems()" style="width:100px">Select None</button>
                    <button type="button" id="reset_btn" class="btn btn-sm btn-danger" onclick="resetItems()" style="width:100px">Reset</button>
                </div>
            </div>
            <div class="row">
                &nbsp;
            </div>
            <div class="row">
                <table class="table" id="checklist_items">
                    <thead>
                        <tr>
                            <th>Item Name</th>
                            <th>Item Description</th>
                            <th>Select To Include</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($allChecklistItemsRow=$allChecklistItemsResult->fetch_assoc()){ ?>
                        <tr>
                            <td><?php echo $allChecklistItemsRow['checklist_item_name'];?></td>
                            <td><?php echo $allChecklistItemsRow['checklist_item_description'];?></td>
                            <td><input type="checkbox" name="checklist_item[]" value="<?php echo $allChecklistItemsRow['checklist_item_id'];?>"
                                       
                            <?php if(in_array($allChecklistItemsRow["checklist_item_id"],$checklistItemArray)){?>
                                       
                                       checked
                            <?php } ?>
                                       
                            /></td>
                        </tr>
                        <?php }?>
                    </tbody>
                </table>
            </div>
            <div class="row">
                &nbsp;
            </div>
            <div class="row">
                <input type="hidden" name="template_id" value="<?php echo $templateId;?>"/>
            </div>
            
        <?php
        
    break;
    
    case "update_template":
        
        try{
            $templateId = $_POST["template_id"];

            $checklistItems = $_POST["checklist_item"];

            if(empty($checklistItems)){
                throw new Exception("Select At Least 1 Item");
            }
            
            $inspectionObj->removeAllChecklistItemsFromTemplate($templateId);
            
            foreach($checklistItems as $itemId){
                $inspectionObj->addChecklistItemsToTemplate($templateId,$itemId);
            }
            
            $msg = "Template Updated Successfully";
            $msg = base64_encode($msg);
            ?>

                <script>
                    window.location="../view/manage-checklist-template.php?msg=<?php echo $msg; ?>&success=true";
                </script>

            <?php

        }
        catch(Exception $e){
            
            $msg= $e->getMessage();
            $msg= base64_encode($msg);
            ?>
    
            <script>
                window.location="../view/manage-checklist-template.php?msg=<?php echo $msg;?>";
            </script>
            <?php
        }
        
        
    break;
    
    case "perform_inspection":
        
        try{
            //main inspection details
            $inspectionId = $_POST["inspection_id"];
            $templateId = $_POST["template_id"];
            $busId = $_POST["bus_id"];
            $tourId = $_POST["tour_id"];
            $inspectedBy = $userId;
            $overallResult = $_POST['overall_result'];
            $excpectedNumberOfChecklistItems =(int)$inspectionObj->countChecklistItemsInTemplate($templateId);
            
            if($overallResult==""){
                throw new Exception("Overall Result Not Selected");
            }
            
            $finalComments = $_POST['final_comments'];
            
            if($finalComments==""){
                throw new Exception("Final Comments Were Not Entered");
            }
            

            if(!isset($_POST['item_status'])){
                
                throw new Exception("Select Checklist Item Statuses");
                
            }else{
                // Get the arrays of checklist item statuses and comments
                $itemStatuses = $_POST['item_status'];
            }
            
            $receivedChecklistItemCount = (int)count($itemStatuses);
            
            if($receivedChecklistItemCount!=$excpectedNumberOfChecklistItems){
                throw new Exception("Select Statuses For All Checklist Items");
            }
            
            foreach ($itemStatuses as $itemId=>$status){
                
                if($status==0&&empty($_POST['item_comments'][$itemId])){
                    throw new Exception("Provide Comments For Failed Checklist Items");
                }
            }
            

            foreach ($itemStatuses as $itemId=>$status){
                
                $itemComment = $_POST['item_comments'][$itemId];
                
                $inspectionObj->addInspectionChecklistResponses($inspectionId,$itemId,$status,$itemComment);
            }
            
            $inspectionStatus = ($overallResult==1)? 2:3;
            
            $inspectionObj->performInspection($inspectionId,$overallResult,$finalComments,$inspectedBy,$inspectionStatus);
            
            //Change Bus Status To Inspection Failed, So This Will Be reflected To Service
            if($overallResult==0){
                $busObj->changeBusStatus($busId,4);
            }
            
            $msg = "Inspection Completed Successfully";
            $msg = base64_encode($msg);
            ?>

                <script>
                    window.location="../view/pending-inspections.php?msg=<?php echo $msg; ?>&success=true";
                </script>

            <?php
        
        }
        catch(Exception $e){
            
            $inspectionId = base64_encode($inspectionId);
            $msg= $e->getMessage();
            $msg= base64_encode($msg);
            ?>
    
            <script>
                window.location="../view/inspect-bus.php?msg=<?php echo $msg;?>&inspection_id=<?php echo$inspectionId;?>";
            </script>
            <?php
        }
        
    break;
    
    case "edit_inspection":
        
        try{
        
            $inspectionId = $_POST["inspection_id"];
            $busId = $_POST["bus_id"];
            $tourId = $_POST["tour_id"];
            $inspectedBy = $userId;
            $overallResult = $_POST['overall_result'];
            
            $existingChecklistItemCount = (int)$inspectionObj->getExistingInspectionResponseCount($inspectionId);
            
            if($overallResult==""){
                throw new Exception("Overall Result Not Selected");
            }
            
            $finalComments = $_POST['final_comments'];
            
            if($finalComments==""){
                throw new Exception("Final Comments Were Not Entered");
            }
            
            if(!isset($_POST['item_status'])){
                
                throw new Exception("Select Checklist Item Statuses");
                
            }else{
                // Get the arrays of checklist item statuses and comments
                $itemStatuses = $_POST['item_status'];
            }
            
            $receivedChecklistItemCount = (int)count($itemStatuses);
            
            if($receivedChecklistItemCount!=$existingChecklistItemCount){
                throw new Exception("Select Statuses For All Checklist Items");
            }
            
            foreach ($itemStatuses as $itemId=>$status){
                
                if($status==0&&empty($_POST['item_comments'][$itemId])){
                    throw new Exception("Provide Comments For Failed Checklist Items");
                }
            }
            
            foreach ($itemStatuses as $itemId=>$status){
                
                $itemComment = $_POST['item_comments'][$itemId];
                
                $inspectionObj->updateInspectionResponse($inspectionId,$itemId,$status,$itemComment);
            }
            
            $inspectionStatus = ($overallResult==1)? 2:3;
            
            $inspectionObj->updateInspection($inspectionId,$overallResult,$finalComments,$inspectedBy,$inspectionStatus);
            
            //Change Bus Status To Inspection Failed, So This Will Be reflected To Service
            if($overallResult==0){
                $busObj->changeBusStatus($busId,4);
            }elseif($overallResult==1){
                $busObj->changeBusStatus($busId,1);
            }
            
            $msg = "Inspection Edited Successfully";
            $msg = base64_encode($msg);
            ?>

                <script>
                    window.location="../view/past-inspections.php?msg=<?php echo $msg; ?>&success=true";
                </script>

            <?php
        
        }
        catch(Exception $e){
            
            $inspectionId = base64_encode($inspectionId);
            $msg= $e->getMessage();
            $msg= base64_encode($msg);
            ?>
    
            <script>
                window.location="../view/edit-inspection.php?msg=<?php echo $msg;?>&inspection_id=<?php echo$inspectionId;?>";
            </script>
            <?php
        }
        
    break;
    
    case "inspection_failed_list_filtered":
        
        $dateFrom = $_POST["dateFrom"];
        $dateTo = $_POST["dateTo"];
        
        $inspectionFailedResult = $inspectionObj->getFailedInspectionsToAssignNewBusUsingFilters($dateFrom,$dateTo);
        
        while($inspectionFailedRow = $inspectionFailedResult->fetch_assoc()){ ?>
        <tr>
            <td><?php echo $inspectionFailedRow["inspection_id"];?></td>
            <td><?php echo $inspectionFailedRow["destination"];?></td>
            <td style="white-space: nowrap"><?php echo $inspectionFailedRow["start_date"];?></td>
            <td><?php echo $inspectionFailedRow["vehicle_no"];?></td>
            <td><?php echo $inspectionFailedRow["final_comments"];?></td>
            <td>
                <a href="reassign-bus.php?inspection_id=<?php echo base64_encode($inspectionFailedRow['inspection_id'])?>" 
                   class="btn btn-xs btn-success" style="margin:2px;display:<?php echo checkPermissions(88); ?>">
<!--                                        <span class="glyphicon glyphicon-ok"></span>-->
                    Assign A </br>Different Bus
                </a>
            </td>
        </tr>
        <?php }
        
    break;
}