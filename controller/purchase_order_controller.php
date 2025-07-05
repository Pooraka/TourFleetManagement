<?php
include_once '../commons/session.php';
include_once '../model/purchase_order_model.php';
include_once '../model/tender_model.php';
include_once '../model/bid_model.php';


//get user information from session
$userSession=$_SESSION["user"];
$userId = $userSession['user_id'];

$tenderObj = new Tender();
$bidObj = new Bid();
$poObj = new PurchaseOrder();

if(!isset($_GET["status"])){
    ?>
    <script>
        window.location= "../view/login.php";
    </script>
    <?php
}

$status= $_GET["status"];

switch ($status){
    
    case "generate_po":
        
        $tenderId = base64_decode($_GET['tender_id']);
        
        $tenderResult = $tenderObj->getTender($tenderId);
        $tenderRow = $tenderResult->fetch_assoc();
        
        $awardedBidId = $tenderRow['awarded_bid'];
        
        $bidResult = $bidObj->getBid($awardedBidId);
        $bidRow = $bidResult->fetch_assoc();
        
        $poNumber = "ST-PO-". strtoupper(bin2hex(random_bytes(2)))."-".$tenderId;
        $partId = $tenderRow['part_id'];
        $quantityOrdered = $tenderRow['quantity_required'];
        $poUnitPrice = $bidRow['unit_price'];
        $totalAmount = (int)$quantityOrdered * (float)$poUnitPrice;
        $createdBy = $userId;
        
        $poObj->generatePurchaseOrder($poNumber, $awardedBidId, $partId, $quantityOrdered, $poUnitPrice, $totalAmount, $createdBy);
        
        $bidObj->changeBidStatus($awardedBidId,3);
        
        $msg = "Purchase Order Generated Successfully For Tender ID $tenderId With The Awarded Bid ID $awardedBidId";
        $msg = base64_encode($msg);
        ?>

            <script>
                window.location="../view/awarded-bids.php?msg=<?php echo $msg; ?>&success=true";
            </script>

        <?php
        
    break;

    case "approve_po":
        
        try{
        
            $poId = base64_decode($_GET['po_id']);
            
            $approvedBy = $userId;
            
            $poObj->approvePO($poId, $approvedBy);
            
            $msg = "Purchase Order Approved Successfully";
            $msg = base64_encode($msg);
            ?>

                <script>
                    window.location="../view/pending-purchase-orders.php?msg=<?php echo $msg; ?>&success=true";
                </script>

            <?php
        
        }
        catch(Exception $e){
            
            $msg= $e->getMessage();
            $msg= base64_encode($msg);
            ?>
    
            <script>
                window.location="../view/pending-purchase-orders.php?msg=<?php echo $msg;?>";
            </script>
            <?php
        }
        
    break;

    case "reject_po":
        
        try{
        
            $poId = base64_decode($_GET['po_id']);
            
            $poResult = $poObj->getPO($poId);
            $poRow = $poResult->fetch_assoc();
            
            $bidId = $poRow['bid_id'];
            $bidResult = $bidObj->getBid($bidId);
            $bidRow = $bidResult->fetch_assoc();
            
            $tenderId = $bidRow['tender_id'];
            
            $bidObj->changeBidStatus($bidId,1);
            $tenderObj->changeTenderStatus($tenderId,1);
            $tenderObj->revokeBidFromTender($tenderId);
            
            $rejectedBy = $userId;
            
            $poObj->rejectPO($poId, $rejectedBy);
            
            $msg = "Purchase Order Rejected Successfully";
            $msg = base64_encode($msg);
            ?>

                <script>
                    window.location="../view/pending-purchase-orders.php?msg=<?php echo $msg; ?>&success=true";
                </script>

            <?php
        
        }
        catch(Exception $e){
            
            $msg= $e->getMessage();
            $msg= base64_encode($msg);
            ?>
    
            <script>
                window.location="../view/pending-purchase-orders.php?msg=<?php echo $msg;?>";
            </script>
            <?php
        }
        
    break;

    case "get_supplier_invoice":
        
        $poId = $_POST['poId'];
        
        ?>
            <div class="row">
                <div class="col-md-2">
                    <label>Attach Invoice</label>
                </div>
                <div class="col-md-5">
                    <label>
                        <input type="file" name="supplier_invoice" class="form-control"/>
                        <input type="hidden" name="po_id" value="<?php echo $poId;?>"/>
                    </label>
                </div>
                <div class="col-md-2">
                    <label>Invoice Number</label>
                </div>
                <div class="col-md-3">
                    <label>
                        <input type="text" name="supplier_invoice_number" class="form-control"/>
                    </label>
                </div>
            </div> 
        <?php
        
        
        
    break;

    case "add_supplier_invoice":
        
        try{
            
            $poId = $_POST['po_id'];
            
            $supplierInvoiceNumber = $_POST['supplier_invoice_number'];
            
            if($supplierInvoiceNumber==""){
                throw new Exception("Enter Invoice Number");
            }
            
            if (!isset($_FILES["supplier_invoice"]) || $_FILES["supplier_invoice"]['error'] == UPLOAD_ERR_NO_FILE) {
                throw new Exception("Attach The Invoice");
            }
            
            $supplierInvoiceFile = $_FILES["supplier_invoice"];
            
            $supplierInvoiceFileName = time()."_".$supplierInvoiceFile["name"];
            $path="../documents/supplierinvoices/$supplierInvoiceFileName";
            move_uploaded_file($supplierInvoiceFile["tmp_name"],$path);
            
            $poObj->addSupplierInvoice($poId,$supplierInvoiceFileName,$supplierInvoiceNumber);
            
            $poObj->changePOStatus($poId,3);
            
            $msg = "Supplier Invoice Attached Successfully";
            $msg = base64_encode($msg);
            ?>

                <script>
                    window.location="../view/pending-purchase-orders.php?msg=<?php echo $msg; ?>&success=true";
                </script>

            <?php
        
        }
        catch(Exception $e){
            
            $msg= $e->getMessage();
            $msg= base64_encode($msg);
            ?>
    
            <script>
                window.location="../view/pending-purchase-orders.php?msg=<?php echo $msg;?>";
            </script>
            <?php
            
        }
        
    break;
}