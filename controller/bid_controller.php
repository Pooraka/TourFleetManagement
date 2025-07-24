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
    
    case "remove_bid":
        
        $tenderId = base64_decode($_GET['tender_id']);
        
        $bidId = base64_decode($_GET['bid_id']);
        
        $bidObj->changeBidStatus($bidId,-1);
        
        $msg = "Bid ID $bidId Removed Successfully";
        $msg = base64_encode($msg);
        ?>

            <script>
                window.location="../view/view-bids.php?msg=<?php echo $msg; ?>&success=true&tender_id=<?php echo base64_encode($tenderId) ;?>";
            </script>

        <?php
        
    break;

    case "award_bid";
    
        $tenderId = base64_decode($_GET['tender_id']);
        
        $bidId = base64_decode($_GET['bid_id']);
        
        $bidObj->changeBidStatus($bidId,2);
        
        $tenderObj->addAwardedBidToTender($tenderId, $bidId);
        
        $tenderObj->changeTenderStatus($tenderId,3);
        
        $msg = "Bid ID $bidId Awared to Tender $tenderId Successfully";
        $msg = base64_encode($msg);
        ?>

            <script>
                window.location="../view/open-tenders.php?msg=<?php echo $msg; ?>&success=true";
            </script>

        <?php
        
    break;

    case "revoke_award":
        
        $tenderId = base64_decode($_GET['tender_id']);

        $tenderResult = $tenderObj->getTender($tenderId);
        $tenderRow = $tenderResult->fetch_assoc();

        $tenderCloseDate = $tenderRow['close_date'];
        $today = date("Y-m-d");
        
        $bidId = base64_decode($_GET['bid_id']);
        
        
        $tenderObj->revokeBidFromTender($tenderId);
        
        $bidObj->changeBidStatus($bidId,1);

        if($tenderCloseDate <= $today) {
            $tenderObj->changeTenderStatus($tenderId,2);
        }else{
            $tenderObj->changeTenderStatus($tenderId,1);
        }

        
        $msg = "Bid ID $bidId Revoked Successfully";
        $msg = base64_encode($msg);
        ?>

            <script>
                window.location="../view/awarded-bids.php?msg=<?php echo $msg; ?>&success=true";
            </script>

        <?php
        
    break;
}