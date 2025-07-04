<?php
include_once '../commons/session.php';
include_once '../model/sparepart_model.php';

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
}