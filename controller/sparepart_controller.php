<?php
include_once '../commons/session.php';
include_once '../model/sparepart_model.php';
include_once '../model/purchase_order_model.php';

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

$sparePartObj = new SparePart();
$poObj = new PurchaseOrder();

$status= $_GET["status"];

switch ($status){
    
    case "register_sparepart":
        
        try{
            
            $partNumber = $_POST['part_number'];
            
            if($partNumber==""){
                throw new Exception("Spare Part Number Cannot Be Empty");
            }
            
            $spartPartNumberExist = $sparePartObj->checkIfPartNumberExist($partNumber);
            
            if($spartPartNumberExist){
                throw new Exception("Spare Part No Already Exists");
            }
            
            $partName = $_POST["part_name"];
            
            if($partName==""){
                throw new Exception("Spare Part Name Cannot Be Empty");
            }
            
            $quantityOnHand = $_POST["quantity"];
            
            if($quantityOnHand<0){
                throw new Exception("Hands On Quantity Cannot Be Empty. Minimum Value is 0");
            }
            
            $reorderLevel = $_POST["reorder_level"];
            
            if($reorderLevel<=0){
                throw new Exception("Re-order Level Must Be Above 0");
            }
            
            $description = $_POST["description"];
            
            if($description==""){
                throw new Exception("Description Cannot Be Empty");
            }
            
            $partId = $sparePartObj->registerSparePart($partNumber, $partName, $description, $quantityOnHand, $reorderLevel);
            
            $sparePartObj->initialStockLoadTransaction($partId, $quantityOnHand, $userId);
            
            $msg = "Spare Part ".$partName."Registered Successfully";
            $msg = base64_encode($msg);

            ?>

            <script>
                window.location="../view/spare-part-types.php?msg=<?php echo $msg;?>&success=true";
            </script>

            <?php
        }
        catch(Exception $e){
            
            $msg= $e->getMessage();
            $msg= base64_encode($msg);
            ?>
    
            <script>
                window.location="../view/register-spareparts.php?msg=<?php echo $msg;?>";
            </script>
            <?php
        }
    break;
    
    case "edit_sparepart_type":
        
        try{
        
            $partId = $_POST["part_id"];
            
            $partNumber = $_POST['part_number'];
            
            if($partNumber==""){
                throw new Exception("Spare Part Number Cannot Be Empty");
            }
            
            $spartPartNumberExist = $sparePartObj->checkDuplicatePartNumberOnUpdate($partNumber, $partId);
            
            if($spartPartNumberExist){
                throw new Exception("Spare Part No Already Exists");
            }
            
            $partName = $_POST["part_name"];
            
            if($partName==""){
                throw new Exception("Spare Part Name Cannot Be Empty");
            }
            
            $reorderLevel = $_POST["reorder_level"];
            
            if($reorderLevel<=0){
                throw new Exception("Re-order Level Must Be Above 0");
            }
            
            $description = $_POST["description"];
            
            if($description==""){
                throw new Exception("Description Cannot Be Empty");
            }
            
            $sparePartObj->updateSparePartType($partId, $partNumber, $partName, $reorderLevel, $description);
            
            $msg = "Spare Part $partNumber Edited Successfully";
            $msg = base64_encode($msg);

            ?>

            <script>
                window.location="../view/spare-part-types.php?msg=<?php echo $msg;?>&success=true";
            </script>

            <?php
        
        }
        catch(Exception $e){
            
            $msg= $e->getMessage();
            $msg= base64_encode($msg);
            ?>
    
            <script>
                window.location="../view/edit-spare-part-type.php?msg=<?php echo $msg;?>&part_id=<?php echo base64_encode($partId);?>";
            </script>
            <?php
        }
        
    break;
    
    case "issue_spare_parts":
        
        try{
            
            $partId = $_POST["part_id"];
            
            $sparePartResult = $sparePartObj->getSparePart($partId);
            $sparePartRow = $sparePartResult->fetch_assoc();
            $quantityOnHand =(int)$sparePartRow ["quantity_on_hand"];
            
            $busId = $_POST["bus_id"];
            
            if($busId==""){
                throw new Exception("Bus Number Should Be Selected");
            }
            
            $quantityToIssue =(int)$_POST["quantity_to_issue"];
            
            if($quantityToIssue<=0){
                throw new Exception("Quantity To Issue Should Be Above 0");
            }
            
            if($quantityToIssue>$quantityOnHand){
                throw new Exception("Quantity To Issue Should Not Exceed The Quantity On Hand");
            }
            
            $issueNotes = $_POST["issue_notes"];
            
            if($issueNotes==""){
                throw new Exception("Notes Cannot Be Empty");
            }
            
            $newQuantityOnHand = $quantityOnHand-$quantityToIssue;
            
            $sparePartObj->issueSpareParts($partId,$newQuantityOnHand);
            
            $transactionId = $sparePartObj->sparePartIssueTransaction($partId,3,$quantityToIssue,$busId,$issueNotes,$userId);
            
            $msg = "Spare Parts Issued Successfully";
            $msg = base64_encode($msg);

            ?>

            <script>
                window.location="../view/view-spare-parts.php?msg=<?php echo $msg;?>&success=true";
            </script>

            <?php
        }
        catch(Exception $e){
            
            $msg= $e->getMessage();
            $msg= base64_encode($msg);
            ?>
    
            <script>
                window.location="../view/issue-spare-parts.php?msg=<?php echo $msg;?>&part_id=<?php echo base64_encode($partId);?>";
            </script>
            <?php
        }
        
    break;
    
    case "get_spare_part_remove_interface":
        
        $partId = $_POST["partId"];
        
        ?>
            <div class="row">
                <div class="col-md-2">
                    <label>Remove Quantity</label>
                </div>
                <div class="col-md-2">
                    <label>
                        <input type="number" name="remove_quantity" min="1" class="form-control"/>
                        <input type="hidden" name="part_id" value="<?php echo $partId;?>"/>
                    </label>
                </div>
                <div class="col-md-2">
                    <label>Enter Reason</label>
                </div>
                <div class="col-md-6">
                    <label>
                        <textarea id="remove_reason" name="remove_reason" rows="2" class="form-control"></textarea>
                    </label>
                </div>
            </div> 
        <?php
        
    break;

    case "remove_sparepart":
        
        try{
            
            $partId = $_POST["part_id"];
            
            $sparePartResult = $sparePartObj->getSparePart($partId);
            $sparePartRow = $sparePartResult->fetch_assoc();
            
            $quantityOnHand = (int)$sparePartRow['quantity_on_hand'];
            
            $removeQuantity = (int)$_POST["remove_quantity"];
            
            if($removeQuantity<=0){
                throw new Exception("Remove Quantity Should Be Above 0");
            }
            
            if($quantityOnHand<$removeQuantity){
                throw new Exception("Remove Quantity Cannot Exceed The Quantity On Hand");
            }
            
            $removeReason = $_POST["remove_reason"];
            
            if($removeReason==""){
                throw new Exception("Remove Reason Should Be Entered");
            }
            
            $newQuantityOnHand = $quantityOnHand-$removeQuantity;
            
            $sparePartObj->issueSpareParts($partId,$newQuantityOnHand);
            
            $transactionId = $sparePartObj->sparePartRemoveTransaction($partId,4,$removeQuantity,$removeReason,$userId);
            
            $msg = "Spare Parts Removed Successfully";
            $msg = base64_encode($msg);

            ?>

            <script>
                window.location="../view/view-spare-parts.php?msg=<?php echo $msg;?>&success=true";
            </script>

            <?php
        }
        catch(Exception $e){
            
            $msg= $e->getMessage();
            $msg= base64_encode($msg);
            ?>
    
            <script>
                window.location="../view/view-spare-parts.php?msg=<?php echo $msg;?>";
            </script>
            <?php
        }
        
    break;

    case "sparepart_list_filtered":

        $status = $_POST["status"];

        $sparePartResult = $sparePartObj->getSparePartsFiltered($status);

        while($sparePartRow = $sparePartResult->fetch_assoc()){
            $partId = $sparePartRow['part_id'];
            ?>
        <tr>
            <td><?php echo $sparePartRow['part_number'];?></td>
            <td><?php echo $sparePartRow['part_name'];?></td>
            <td><?php echo $sparePartRow['quantity_on_hand'];?></td>
            <td><?php echo $sparePartRow['reorder_level'];?></td>
            <td>
                <a href="issue-spare-parts.php?part_id=<?php echo base64_encode($partId); ?>"  
                    class="btn btn-xs btn-success" style="margin:2px;display:<?php echo checkPermissions(104); ?>">
                    <span class="glyphicon glyphicon-plus"></span>
                    Issue To Bus
                </a> 
                <a href="#" data-toggle="modal" onclick="removeSpareParts(<?php echo $partId;?>)" data-target="#add_remove_info" 
                    class="btn btn-xs btn-danger" style="margin:2px;display:<?php echo checkPermissions(105); ?>">
                    <span class="glyphicon glyphicon-remove"></span>
                    Remove
                </a> 
            </td>
        </tr>
        <?php }

    break;
}