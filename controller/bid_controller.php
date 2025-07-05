<?php
include_once '../commons/session.php';
include_once '../model/tender_model.php';
include_once '../model/bid_model.php';


//get user information from session
$userSession=$_SESSION["user"];
$userId = $userSession['user_id'];

$tenderObj = new Tender();
$bidObj = new Bid();

if(!isset($_GET["status"])){
    ?>
    <script>
        window.location= "../view/login.php";
    </script>
    <?php
}

$status= $_GET["status"];

switch ($status){
    
    case "add_bid":
        
        try{
            
            $tenderId = $_POST["tender_id"];
            
            $supplierId = $_POST["supplier_id"];
            
            if($supplierId==""){
                throw new Exception("Select A Supplier");
            }
            
            $unitPrice = $_POST["unit_price"];
            
            if($unitPrice<=0){
                throw new Exception("Unit Price Must Be Above 0");
            }
            
            $bidId = $bidObj->addBid($tenderId, $supplierId, $unitPrice);
            
            $msg = "Bid ID $bidId Added Successfully";
            $msg = base64_encode($msg);
            ?>

                <script>
                    window.location="../view/add-bids.php?msg=<?php echo $msg; ?>&success=true&tender_id=<?php echo base64_encode($tenderId) ;?>";
                </script>

            <?php
        
        }
        catch(Exception $e){
            
            $msg= $e->getMessage();
            $msg= base64_encode($msg);
            ?>
    
            <script>
                window.location="../view/add-bids.php?msg=<?php echo $msg;?>&tender_id=<?php echo base64_encode($tenderId) ;?>";
            </script>
            <?php
        }
    
    break;
}