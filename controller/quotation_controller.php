<?php
include_once '../commons/session.php';
include_once '../model/quotation_model.php';
include_once '../model/customer_model.php';


//get user information from session
$userSession=$_SESSION["user"];
$user_id = $userSession['user_id'];



if(!isset($_GET["status"])){
    ?>
    <script>
        window.location= "../errorpages/403.php";
    </script>
    <?php
}

$customerObj = new Customer();
$quotationObj = new Quotation();

$status= $_GET["status"];

switch ($status){
    
    case "generate_quotation":
        
        try{
        
            $nic = $_POST["nic"];
            
            if($nic==""){
                throw new Exception ("NIC cannot be empty");
            }
            if($nic!=""){
                
                $customerResult = $customerObj->checkIfCustomerExist($nic);
                
                if($customerResult->num_rows!=1){
                    throw new Exception ("Customer Does Not Exist");
                }
                
                $customerRow = $customerResult->fetch_assoc();
                $customerId = $customerRow["customer_id"];
            }
            
            $startDate = $_POST["start_date"];
            
            if($startDate==""){
                throw new Exception ("Start Date Cannot Be Empty");
            }
            
            $endDate = $_POST["end_date"];
            
            if($endDate==""){
                throw new Exception ("End Date Cannot Be Empty");
            }
            
            if($startDate>$endDate){
                throw new Exception("Start Date Cannot Be Greater Than End Date");
            }
            
            $pickup = $_POST["pickup"];
            
            if($pickup==""){
                throw new Exception("Pickup Location Cannot Be Empty");
            }
            
            $dropoff = $_POST["dropoff"];
            
            if($dropoff==""){
                throw new Exception("Dropoff Location Cannot Be Empty");
            }
            
            $roundTripMileage = $_POST["round_trip"];
            
            if($roundTripMileage==0){
                throw new Exception("Round Trip Mileage Cannot Be Empty");
            }
            if($roundTripMileage<0){
                throw new Exception ("Round Trip Mileage Cannot Be Less Than 0");
            }
            
            $amount = $_POST["amount"];
            
            if($amount==0){
                throw new Exception("Amount Cannot Be Empty");
            }
            if($amount<0){
                throw new Exception ("Amount Cannot Be Less Than 0");
            }
            
            $destination = $_POST["destination"];
            
            if($destination==""){
                throw new Exception("Destination Cannot Be Empty");
            }
            
            $description = $_POST["description"];
            
            if($description==""){
                throw new Exception("Description Cannot Be Empty");
            }
            
            if(!isset($_POST["request_count"]) || array_sum($_POST["request_count"])==0){
                throw new Exception ("Please enter a count for at least one bus category");
            }
            
            $busCategoryArray = $_POST["request_count"];
            
            $quotationId = $quotationObj->generateQuotation($customerId, $startDate, $endDate, $pickup, 
                    $destination, $dropoff, $description, $roundTripMileage, $amount);
            
            foreach($busCategoryArray as $categoryId=>$quantity){
                
                if($quantity){
                    $quotationObj->addQuotationItems($quotationId, $categoryId, $quantity);
                }
            }
            
            $msg = "Quotation No: ".$quotationId." Generated Successfully";
            $msg = base64_encode($msg);
            
            ?>
            
            <script>
                window.location="../view/generate-quotation.php?msg=<?php echo $msg;?>&success=true";
            </script>
            
            <?php
        }
        catch(Exception $e){
            
            $msg= $e->getMessage();
            $msg= base64_encode($msg);
            ?>
    
            <script>
                window.location="../view/generate-quotation.php?msg=<?php echo $msg;?>";
            </script>
            <?php

        }
              
    break;
    
    case "cancel_quotation":
        
        $quotationId = base64_decode($_GET['quotation_id']);
        
        $quotationObj->changeQuotationStatus($quotationId, -1);
        
        $msg = "Quotation No: " . $quotationId . " Cancelled Successfully";
        $msg = base64_encode($msg);
        ?>
                    
            <script>
                window.location="../view/pending-quotations.php?msg=<?php echo $msg; ?>&success=true";
            </script>
                    
        <?php
        
    break;

    case "pending_quotation_filtered":

        $dateFrom = $_POST["dateFrom"];
        $dateTo = $_POST["dateTo"];

        $pendingQuotationResult = $quotationObj->getPendingQuotationsFitered($dateFrom,$dateTo);

        while($pendingQuotationRow = $pendingQuotationResult->fetch_assoc()){?>
        <tr>
            <td style="text-align: center"><?php echo $pendingQuotationRow['quotation_id'];?></td>
            <td style="white-space: nowrap"><?php echo $pendingQuotationRow['issued_date'];?></td>
            <td><?php echo htmlspecialchars($pendingQuotationRow['customer_fname']." ".$pendingQuotationRow['customer_lname']);?></td>
            <td style="white-space: nowrap;text-align: right"><?php echo "LKR ".number_format($pendingQuotationRow['total_amount'],2);?></td>
            <td style="white-space: nowrap"><?php echo $pendingQuotationRow['tour_start_date'];?></td>
            <td>
                <a href="../reports/quotation.php?quotation_id=<?php echo base64_encode($pendingQuotationRow['quotation_id']);?>" 
                    class="btn btn-xs btn-info" style="margin:2px;display:<?php echo checkPermissions(78);?>" target="_blank">
                    <span class="glyphicon glyphicon-search"></span>                                                  
                    View
                </a>
                <a href="#" class="btn btn-xs btn-success" data-toggle="modal" data-target="#generateInvoiceModal" 
                    onclick="generateInvoiceModal(<?php echo $pendingQuotationRow['quotation_id'];?>)" 
                    style="margin:2px;display:<?php echo checkPermissions(79);?>">
                    <span class="glyphicon glyphicon-ok"></span>                                                  
                    Generate Invoice
                </a>
                <a href="../controller/quotation_controller.php?status=cancel_quotation&quotation_id=<?php echo base64_encode($pendingQuotationRow['quotation_id']);?>" 
                    class="btn btn-xs btn-danger" style="margin:2px;display:<?php echo checkPermissions(80);?>">
                    <span class="glyphicon glyphicon-remove"></span>                                                  
                    Cancel
                </a>
            </td>
        </tr>
        <?php }

    break;
}