<?php
include_once '../commons/session.php';
include_once '../model/inspection_model.php';



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
}