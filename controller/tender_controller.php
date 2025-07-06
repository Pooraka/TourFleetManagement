<?php
include_once '../commons/session.php';
include_once '../model/tender_model.php';


//get user information from session
$userSession=$_SESSION["user"];
$userId = $userSession['user_id'];

$tenderObj = new Tender();

if(!isset($_GET["status"])){
    ?>
    <script>
        window.location= "../view/login.php";
    </script>
    <?php
}

$status= $_GET["status"];

switch ($status){
    
    case "add_tender":
        
        try{
        
            $partId = $_POST["part_id"];
            
            if($partId==""){
                throw new Exception("Select The Spare Part To Add Tender");
            }
            
            $quantityRequired = $_POST["quantity"];
            
            if($quantityRequired<=0){
                throw new Exception("Enter A Value Above 0 To Quantity Required");
            }
            
            $openDate = $_POST["open_date"];
            
            if($openDate==""){
                throw new Exception("Open Date Cannot Be Empty");
            }
            
            $closeDate = $_POST["close_date"];
            
            if($closeDate==""){
                throw new Exception("Close Date Cannot Be Empty");
            }
            
            $tenderDescription = $_POST["tender_description"];
            
            if($tenderDescription==""){
                throw new Exception("Description Cannot Be Empty");
            }
            
            if (!isset($_FILES["advertisement"]) || $_FILES["advertisement"]['error'] == UPLOAD_ERR_NO_FILE) {
                throw new Exception("Attach The Advertisement");
            }
            
            $advertisementFile = $_FILES["advertisement"];

            $advertisementFileName = time()."_".$advertisementFile["name"];
            $path="../documents/tenderadvertisements/$advertisementFileName";
            move_uploaded_file($advertisementFile["tmp_name"],$path);
            
            $tenderObj->createTender($partId, $quantityRequired, $tenderDescription, $advertisementFileName, $openDate, $closeDate,$userId);
            
            $msg = "Tender Created Successfully";
            $msg = base64_encode($msg);
            ?>

                <script>
                    window.location="../view/open-tenders.php?msg=<?php echo $msg; ?>&success=true";
                </script>

            <?php
        
        }
        catch(Exception $e){
            
            $msg= $e->getMessage();
            $msg= base64_encode($msg);
            ?>
    
            <script>
                window.location="../view/add-tender.php?msg=<?php echo $msg;?>";
            </script>
            <?php
        }
        
    break;
    
    case "cancel_tender":
        
        try{
            
            $tenderId = base64_decode($_GET["tender_id"]);
            
            $tenderObj->changeTenderStatus($tenderId,-1);
        
            $msg = "Tender Cancelled Successfully";
            $msg = base64_encode($msg);

            ?>
                <script>
                    window.location="../view/open-tenders.php?msg=<?php echo $msg;?>&success=true";
                </script>
            <?php
        
        }
        catch(Exception $e){
            
            $msg= $e->getMessage();
            $msg= base64_encode($msg);
            ?>
    
            <script>
                window.location="../view/open-tenders.php?msg=<?php echo $msg;?>";
            </script>
            <?php   
        }
        
    break;
}