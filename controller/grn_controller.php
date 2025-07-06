<?php
include_once '../commons/session.php';
include_once '../model/sparepart_model.php';
include_once '../model/purchase_order_model.php';
include_once '../model/grn_model.php';

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
$grnObj = new GRN();

$status= $_GET["status"];

switch ($status){
    
    case"add_grn":
        
        try{
        
            $poId = $_POST["po_id"];
            
            if($poId==""){
                throw new Exception("Select A Purchase Order");
            }
            
            //quantity received to create this grn
            $quantityReceived =(int)$_POST['quantity_received'];
            
            if($quantityReceived<=0){
                throw new Exception("Quantity Received Should Be Higher Than 0");
            }
            
            $grnNotes = $_POST['grn_notes'];
            
            if($grnNotes==""){
                throw new Exception("GRN Notes Cannot Be Empty");
            }
            
            $inspectedBy = $userId;
            
            $poResult = $poObj->getPO($poId);
            $poRow = $poResult->fetch_assoc();
            
            //quantity ordered as per po
            $quantityOrdered =(int)$poRow['quantity_ordered'];
            
            //so far how many items have been delivered
            $quantityReceivedAlready = (int)$poRow['quantity_received'];
            
            if($quantityOrdered<$quantityReceivedAlready+$quantityReceived){
                throw new Exception("Quantity Received Exceeds The Quantity Ordered");
            }
            
            $grnNumber = "GRN-".date('md').strtoupper(bin2hex(random_bytes(2)))."-".$poId;
            
            //create GRN
            $grnId = $grnObj->createGRN($grnNumber, $poId, $quantityReceived, $inspectedBy, $grnNotes);
            
            //Create a part_transaction record
            $partId = $poRow['part_id'];
            $transactionType = 2;
            $transactedBy = $userId;
            
            $transactionId = $sparePartObj->sparePartAddTransaction($partId,$transactionType,$quantityReceived,$grnId,$grnNotes,$transactedBy);
            
            //Update quantity on hand when receiving spare parts
            $sparePartResult = $sparePartObj->getSparePart($partId);
            $sparePartRow = $sparePartResult->fetch_assoc();
            $quantityOnHand =(int)$sparePartRow['quantity_on_hand'];
            
            $newQuantityOnHand = $quantityOnHand+$quantityReceived;
            
            $sparePartObj->addSpareParts($partId,$newQuantityOnHand);
            
            //Update purchase order table
            $allQuantityReceived = $quantityReceivedAlready+$quantityReceived;
            
            if($allQuantityReceived<$quantityOrdered){
                
                $poObj->updatePOWhenGRNCreated($poId,$allQuantityReceived,4);
                
            }elseif ($allQuantityReceived==$quantityOrdered) {
                
                $poObj->updatePOWhenGRNCreated($poId,$allQuantityReceived,5);
            }
            
            $msg = "GRN Created Successfully & Spare Part Levels Updated";
            $msg = base64_encode($msg);

            ?>

            <script>
                window.location="../view/add-spare-parts.php?msg=<?php echo $msg;?>&success=true";
            </script>

            <?php
        }
        catch(Exception $e){
            
            $msg= $e->getMessage();
            $msg= base64_encode($msg);
            ?>
    
            <script>
                window.location="../view/add-spare-parts.php?msg=<?php echo $msg;?>";
            </script>
            <?php
        }
        
    break;
}