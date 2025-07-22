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
            
            if($openDate>$closeDate){
                
                throw new Exception("Open Date Cannot Be Greater Than Close Date");
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

    case "get_bids_of_past_tender":
        
        $tenderId = $_POST["tenderId"];
        
        $tenderResult = $tenderObj->getTender($tenderId);
        $tenderRow = $tenderResult->fetch_assoc();
        $awardedBidId = $tenderRow["awarded_bid"];
        
        $bidListResult = $bidObj->getBidsOfATenderIncludingRemoved($tenderId);
        
        
        ?>
            <div class="row">
                <div class="col-md-12">
                    <table class="table" id="bid_list_table">
                        <thead>
                            <tr>
                                <th>Bid ID</th>
                                <th>Supplier</th>
                                <th>Unit Price</th>
                                <th>Bid Date</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while($bidListRow = $bidListResult->fetch_assoc()){
                                
                                
                                $status = match((int)$bidListRow["bid_status"]){
                                    
                                    -1=>"Removed",
                                    1=>"Not Awarded",
                                    2,3=>"Awarded",
                                }
                                ?>
                            <tr>
                                <td><?php echo $bidListRow["bid_id"];?></td>
                                <td><?php echo $bidListRow["supplier_name"];?></td>
                                <td style="text-align: right"><?php echo "LKR ".number_format((float)$bidListRow["unit_price"],2);?></td>
                                <td><?php echo $bidListRow["bid_date"];?></td>
                                <td><?php echo $status;?></td>
                            </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
            
        <?php
        
    break;
    
    case "past_tenders_filtered":

        $dateFrom = $_POST["dateFrom"];
        $dateTo = $_POST["dateTo"];
        $tenderStatus = $_POST["tenderStatus"];
        $partId = $_POST["partId"];

        $tenderResult = $tenderObj->getPastTendersFiltered($dateFrom, $dateTo, $partId, $tenderStatus);
        
        while($tenderRow = $tenderResult->fetch_assoc()){
                                
            $statusDisplay = match((int)$tenderRow["tender_status"]){

                -1=>"Cancelled",
                3=>"Bid Awarded",
            };

            $statusClass = match((int)$tenderRow["tender_status"]){

                -1=>"label label-danger",
                3=>"label label-success"
            };

            ?>
        <tr>
            <td style="white-space: nowrap"><?php echo date("Y-m-d", strtotime($tenderRow["created_at"])); ?></td>
            <td><?php echo $tenderRow["tender_id"];?></td>
            <td><?php echo $tenderRow["part_name"];?></td>
            <td><?php echo $tenderRow["quantity_required"];?></td>
            <td style="white-space: nowrap"><?php echo $tenderRow["open_date"];?></td>
            <td style="white-space: nowrap"><?php echo $tenderRow["close_date"];?></td>
            <td><span class="<?php echo $statusClass?>"><?php echo $statusDisplay;?></span></td>
            <td>
                <a href="../documents/tenderadvertisements/<?php echo $tenderRow["advertisement_file_name"];?>" 
                   class="btn btn-sm btn-info" style="margin:2px;display:<?php echo checkPermissions(69); ?>" target="_blank">                                                 
                    Advertisement
                </a>
                <?php if($tenderRow["tender_status"]==3){ ?>
                <button type="button" id="viewBidsBtn" class="btn btn-sm btn-primary" 
                        style="margin:2px;display:<?php echo checkPermissions(71); ?>"
                        onclick="getBidsToView(<?php echo $tenderRow["tender_id"];?>)"
                        data-toggle="modal" data-target="#viewBidsModal"
                        >
                    View Bids
                </button>
                <?php }?>
            </td>
        </tr>
        <?php }

    break;
}