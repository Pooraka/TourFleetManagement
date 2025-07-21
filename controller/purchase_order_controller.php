<?php
include_once '../commons/session.php';
include_once '../model/purchase_order_model.php';
include_once '../model/tender_model.php';
include_once '../model/bid_model.php';
include_once '../model/sparepart_model.php';
include_once '../model/supplier_model.php';


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

$tenderObj = new Tender();
$bidObj = new Bid();
$poObj = new PurchaseOrder();
$sparePartObj = new SparePart();
$supplierObj = new Supplier();

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
    
    case "load_purchase_order_add_sparepart_page":
        
        $poId = $_POST['poId'];
        
        $poResult = $poObj->getPO($poId);
        $poRow = $poResult->fetch_assoc();
        
        $bidId = $poRow['bid_id'];
        $bidResult = $bidObj->getBid($bidId);
        $bidRow = $bidResult->fetch_assoc();
        
        $supplierName = $bidRow['supplier_name'];
        
        $partId = $poRow['part_id'];
        $sparePartResult = $sparePartObj->getSparePart($partId);
        $sparePartRow = $sparePartResult->fetch_assoc();
        
        ?>
            <div class="row">
                <div class="col-md-3" style="margin-bottom: 10px">
                    <span class="fa-solid fa-bus"></span>&nbsp;<b>Supplier Name</b>
                    </br>
                    <span><?php echo $supplierName; ?> </span>
                </div>
                <div class="col-md-3" style="margin-bottom: 10px">
                    <span class="fa-solid fa-bus"></span>&nbsp;<b>Part Number</b>
                    </br>
                    <span><?php echo $sparePartRow['part_number']; ?> </span>
                </div>
                <div class="col-md-3" style="margin-bottom: 10px">
                    <span class="fa-solid fa-bus"></span>&nbsp;<b>Part Number</b>
                    </br>
                    <span><?php echo $sparePartRow['part_name']; ?> </span>
                </div>
                <div class="col-md-3" style="margin-bottom: 10px">
                    <span class="fa-solid fa-bus"></span>&nbsp;<b>Quantity Ordered</b>
                    </br>
                    <span><?php echo number_format($poRow['quantity_ordered'],0); ?> </span>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4" style="margin-bottom: 10px">
                    <span class="fa-solid fa-bus"></span>&nbsp;<b>Quantity Received Already</b>
                    </br>
                    <span><?php echo number_format($poRow['quantity_received'],0); ?> </span>
                </div>
            </div>
            
        <?php
        
    break;

    
    case "supplier_cost_trend":

        header('Content-Type: application/json');

        try{

            $dateFrom = $_POST["dateFrom"];
            $dateTo = $_POST["dateTo"];
            $supplierId = $_POST["supplierId"];

            if($dateFrom == "" || $dateTo == ""){
                throw new Exception("Please select both start and end dates.");
            }

            if($dateFrom > $dateTo){
                throw new Exception("End date should be equal to or greater than start date.");
            }

            
            $supplierCostTrendResult = $poObj->getSupplierCostTrend($dateFrom, $dateTo, $supplierId);

            $dates = [];
            $costs = [];

            if($supplierCostTrendResult->num_rows > 0) {

                while ($supplierCostRow = $supplierCostTrendResult->fetch_assoc()) {

                    array_push($dates, $supplierCostRow['po_paid_date']);
                    array_push($costs, (float)$supplierCostRow['total_amount']);
                }
            }

            echo json_encode(['dates' => $dates, 'costs' => $costs]);
        }
        catch(Exception $e){

            echo json_encode(['error' => $e->getMessage()]);
        }
    break;
    
    case "supplier_payments_monthly_chart":
        
        header('Content-Type: application/json');
        
        try{
            
            $startMonth =  $_POST['startMonth'];
            $endMonth =  $_POST['endMonth']; 
            $supplierId =  $_POST['supplierId'];
            
            if (empty($startMonth) || empty($endMonth)) {
                throw new Exception("Start and End months are required.");
            }
            
            if ($startMonth > $endMonth) {
                throw new Exception("End month should be greater than start month.");
            }
            
            $supplierPaymentResult = $poObj->getMonthlySupplierPayments($startMonth, $endMonth, $supplierId);
            
            $months = [];
            $payments = [];
            
            if($supplierPaymentResult->num_rows>0){
                
                while($supplierPaymentRow = $supplierPaymentResult->fetch_assoc()){
                    
                    array_push($months,$supplierPaymentRow["month"]);
                    array_push($payments,$supplierPaymentRow["total_amount"]);
                }
            }
            
            echo json_encode(['months' => $months, 'payments' => $payments]);
        }
        catch(Exception $e){
            
            echo json_encode(['error' => $e->getMessage()]);
        }
        
    break;
    
    case "past_po_filtered":
        
        $dateFrom = $_POST['dateFrom'];
        $dateTo = $_POST['dateTo'];
        $poStatus = $_POST['poStatus'];
        $partId = $_POST['sparePart'];

        $poResult = $poObj->getPastPOsFiltered($dateFrom,$dateTo,$partId,$poStatus);
        
        while($poRow = $poResult->fetch_assoc()){

            $supplierId = $poRow["supplier_id"];

            $supplierResult = $supplierObj->getSupplier($supplierId);
            $supplierRow = $supplierResult->fetch_assoc();
            $supplierName = $supplierRow["supplier_name"];

            $sparePartId = $poRow["part_id"];
            $sparePartResult = $sparePartObj->getSparePart($sparePartId);
            $sparePartRow = $sparePartResult->fetch_assoc();
            $sparePartName = $sparePartRow["part_name"];

            $status = match((int)$poRow["po_status"]){

                3=>"Approved",
                4=>"Partially Received",
                5=>"All Parts Received",
                6=>"Paid",
            };

            ?>
        <tr>
            <td style="white-space:nowrap"><?php echo $poRow["order_date"];?></td>
            <td style="white-space:nowrap"><?php echo $poRow["po_number"];?></td>
            <td><?php echo $supplierName;?></td>
            <td><?php echo $poRow["supplier_invoice_number"];?></td>
            <td><?php echo $sparePartName;?></td>
            <td><?php echo number_format($poRow["quantity_ordered"]);?></td>
            <td><?php echo number_format($poRow["quantity_received"]);?></td>
            <td style="text-align:right"><?php echo number_format($poRow["total_amount"],2);?></td>
            <td><?php echo $status;?></td>
            <td>
                <a href="../reports/purchaseorder.php?po_id=<?php echo base64_encode($poRow['po_id']);?>" 
                   class="btn btn-xs btn-info" style="margin:2px;display:<?php echo checkPermissions(95); ?>" target="_blank">
                View PO
                </a>
                <a href="../documents/supplierinvoices/<?php echo $poRow['supplier_invoice'];?>" 
                   class="btn btn-xs btn-primary" style="margin:2px;display:<?php echo checkPermissions(163); ?>" target="_blank">
                View Supp Inv
                </a>
            </td>
        </tr>
        <?php }

    break;
}