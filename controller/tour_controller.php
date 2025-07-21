<?php
include_once '../commons/session.php';
include_once '../model/tour_model.php';
include_once '../model/bus_model.php';
include_once '../model/customer_invoice_model.php';
include_once '../model/inspection_model.php';


//get user information from session
$userSession=$_SESSION["user"];
$user_id = $userSession['user_id'];

$tourObj = new Tour();
$customerInvoiceObj = new CustomerInvoice();
$busObj = new Bus();
$inspectionObj = new Inspection();

if(!isset($_GET["status"])){
    ?>
    <script>
        window.location= "../view/login.php";
    </script>
    <?php
}

$status= $_GET["status"];

switch ($status){
    
    case "get_data_to_add_tour":
        
        $invoiceId = $_POST['invoiceId'];
        
        $customerInvoiceResult = $customerInvoiceObj->getInvoice($invoiceId);
        $customerInvoiceRow = $customerInvoiceResult->fetch_assoc();
        
        $customerInvoiceItemResult = $customerInvoiceObj->getInvoiceItems($invoiceId);
        
        $startDate = $customerInvoiceRow['tour_start_date'];
        $endDate = $customerInvoiceRow['tour_end_date'];
        $destination = $customerInvoiceRow["destination"];
        
        $categoryIdArray = array();
        
        ?>
            <div class="row">
                &nbsp;
            </div>
            <div class="row">
                <div class="col-md-3">
                    <label class="control-label">Start Date</label>
                </div>
                <div class="col-md-3">
                    <label class="control-label"><?php echo $startDate;?></label>
                    <input type="hidden" name="start_date" value="<?php echo $startDate;?>"/>
                </div>
                <div class="col-md-3">
                    <label class="control-label">End Date</label>
                </div>
                <div class="col-md-3">
                    <label class="control-label"><?php echo $endDate;?></label>
                    <input type="hidden" name="end_date" value="<?php echo $endDate;?>"/>
                </div>
            </div>
            <div class="row">
                &nbsp;
            </div>
            <div class="row">
                <div class="col-md-6">
                    <label><b>Requested Bus Types & Quantity</b></label>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Bus Category</th>
                                <th>Quantity</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while($customerInvoiceItemRow = $customerInvoiceItemResult->fetch_assoc()){ ?>
                            <tr>
                                <td><?php echo $customerInvoiceItemRow['category_name'];?></td>
                                <td><?php echo $customerInvoiceItemRow['quantity'];?></td>
                            </tr>
                            <?php
                            
                                array_push($categoryIdArray,$customerInvoiceItemRow['category_id']);
                            } 
                            
                            ?>
                        </tbody>
                    </table>
                </div>
                <div class="col-md-3">
                    <label class="control-label">Destination</label>
                </div>
                <div class="col-md-3">
                    <label class="control-label"><?php echo $destination;?></label>
                    <input type="hidden" name="destination" value="<?php echo $destination;?>"/>
                </div>
            </div>
            <div class="row">
                &nbsp;
            </div>
            <?php 
            
              $busResult = $busObj->getBusAvailableForTourByRequestedCategory($startDate, $endDate, $categoryIdArray);
//                $busResult = $busObj->getBusAvailableForTour($startDate, $endDate);
            
            ?>
            <div class="row">
                <div class="col-md-6">
                    <label><b>Select The Desired Buses To Allocate To The Tour</b></label>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Select</th>
                                <th>Category</th>
                                <th>Vehicle No</th>
                                <th>Make</th>
                                <th>Model</th>
                                <th>Passengers</th> 
                            </tr>
                        </thead>
                        <tbody>
                            <?php while($busRow = $busResult->fetch_assoc()){ ?>
                            <tr>
                                <td>
                                    <input type="checkbox" name="bus[]" value="<?php echo $busRow['bus_id'];?>"/>
                                </td>
                                <td><?php echo $busRow['category_name'];?></td>
                                <td><?php echo $busRow['vehicle_no'];?></td>
                                <td><?php echo $busRow['make'];?></td>
                                <td><?php echo $busRow['model'];?></td>
                                <td><?php echo $busRow['capacity'];?></td>
                            </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
            
        <?php
        
    break;
    
    case "add_tour":
        
        try{
            
            $invoiceId = $_POST["invoice_id"];
            
            if($invoiceId==""){
                throw new Exception("Select An Invoice");
            }
            
            $invoiceItemResult = $customerInvoiceObj->getInvoiceItems($invoiceId);
            
            $startDate = $_POST['start_date'];
            $endDate = $_POST['end_date'];
            $destination = $_POST['destination'];
            
            if(empty($_POST["bus"])){
                throw new Exception("Please Select The Bus(es) To Assign");
            }
            
            $busArray = $_POST["bus"];
            
            $busCategoryResult = $busObj->getCategoryCountByBuses($busArray);
            
            
            
            //From Line 176 to 243 two cycles of two foreach loops used to check invoiceitems vs selected buses && selected buses vs invoiceitems
            $matchWithInvoiceItems = true;
            
            //Put invoice items into an array
            $invoiceItems = array();
            while ($invoiceItemRow = $invoiceItemResult->fetch_assoc()) {
                array_push($invoiceItems, $invoiceItemRow);
            }
            
            //Put bus categories into an array
            $busCategories = array();
            while ($busCategoryRow = $busCategoryResult->fetch_assoc()) {
                array_push($busCategories,$busCategoryRow);
            }
            
            //check If all invoice items are included in selected items
            foreach ($invoiceItems as $invoiceItemRow) {
                
                $categoryId = $invoiceItemRow['category_id'];
                $quantity = $invoiceItemRow['quantity'];

                $foundMatch = false;
                
                foreach ($busCategories as $busCategoryRow) {
                    
                    if($busCategoryRow['category_id'] == $categoryId && $busCategoryRow['quantity'] == $quantity){
                        
                        $foundMatch = true;
                        //break inner loop
                        break;
                    }
                }
                
                // If we didn’t find a match, set to false
                if (!$foundMatch) {
                    $matchWithInvoiceItems = false;
                    break;
                }
            }
            
            //check if all selected items are in invoice items
            foreach($busCategories as $busCategoryRow){
                
                $categoryId = $busCategoryRow['category_id'];
                $quantity = $busCategoryRow['quantity'];

                $foundMatch = false;
                
                foreach($invoiceItems as $invoiceItemRow){
                    
                    if($invoiceItemRow['category_id'] == $categoryId && $invoiceItemRow['quantity'] == $quantity){
                        
                        $foundMatch = true;
                        //break inner loop
                        break;
                    }
                }
                
                // If we didn’t find a match, set to false
                if (!$foundMatch) {
                    $matchWithInvoiceItems = false;
                    break;
                }
                
            }
            
            if(!$matchWithInvoiceItems){
                throw new Exception ("Please Select Buses As Requested By Invoice");
            }
            
            $tourId = $tourObj->addTour($invoiceId, $startDate, $endDate, $destination);
            
            foreach($busArray as $busId){
                
                $tourObj->addBusToTour($tourId, $busId);
                
                //Now if the tour is set to today or tommorrow we need to immediately assign a inspection
                $today = date('Y-m-d');
                $tomorrow = date('Y-m-d', strtotime('+1 day'));
                
                if ($startDate == $today || $startDate == $tomorrow) {
        
                    $inspectionObj->scheduleInspection($busId, $tourId);
                }
            }
            
            $customerInvoiceObj->changeInvoiceStatus($invoiceId,2);
            
            $msg = "Tour Assigned Successfully";
            $msg = base64_encode($msg);

            ?>

            <script>
                window.location="../view/add-tour.php?msg=<?php echo $msg;?>&success=true";
            </script>

            <?php
            
        }
        catch(Exception $e){
            
            $msg= $e->getMessage();
            $msg= base64_encode($msg);
            ?>
    
            <script>
                window.location="../view/add-tour.php?msg=<?php echo $msg;?>";
            </script>
            <?php
        }
        
    break;
    
    
    case "cancel_tour":
        
        $tourId = base64_decode($_GET["tour_id"]);
        
        $tourResult = $tourObj->getTour($tourId);
        $tourRow = $tourResult->fetch_assoc();
        
        $invoiceId = $tourRow["invoice_id"];
        
        $tourObj->changeTourStatus($tourId,-1);
        
        $customerInvoiceObj->changeInvoiceStatus($invoiceId,1);
        
        $inspectionObj->cancelInspectionOfATour($tourId);
        
        $msg = "Tour Cancelled Successfully";
        $msg = base64_encode($msg);

        ?>

        <script>
            window.location="../view/pending-tours.php?msg=<?php echo $msg;?>&success=true";
        </script>

        <?php
        
    break;

    case "load_tour":
        
        $tourId = $_POST['tourId'];
        
        ?>
            
            <div class="row">
                <div class="col-md-6">
                    <label>Enter Tour's Actual Mileage (Km)</label>
                </div>
                <div class="col-md-6">
                    <label>
                        <input type="number" name="actual_mileage" class="form-control" min="0" required/>
                        <input type="hidden" name="tour_id" value="<?php echo $tourId;?>"/>
                    </label>
                </div>
            </div>
        
        <?php
        
    break;

    case "complete_tour":
    
        try{
            $tourId = $_POST["tour_id"];
            $actualMileage = $_POST["actual_mileage"];

            if($actualMileage=="" || $actualMileage<=0){
                throw new Exception ("Enter Tour's Actual Mileage To Complete The Tour");
            }
            
            $busResult = $tourObj->getBusListOfATour($tourId);
            
            while($busRow=$busResult->fetch_assoc()){
                
                $busStatus = $busRow['bus_status'];
                
                if($busStatus==4){
                    throw new Exception("Replace The Inspection Failed Bus With The New Bus To Complete The Tour");
                }

            }
            
            $tourResult = $tourObj->getTour($tourId);
            $tourRow = $tourResult->fetch_assoc();
            
            $invoiceId = $tourRow["invoice_id"];
            
            $customerInvoiceObj->addActualTourMileage($invoiceId, $actualMileage);
            $customerInvoiceObj->changeInvoiceStatus($invoiceId,3);
            
            
            while($busRow=$busResult->fetch_assoc()){
                
                $busId = $busRow['bus_id'];
                $currentMileage = $busRow['current_mileage_km'];
                
                $newMileage = (int)$currentMileage + (int)$actualMileage;
                
                $newMileageAsAt = date('Y-m-d H:i:s', time());
                
                $busObj->updateBusMileage($busId, $newMileage, $newMileageAsAt);
            }
            
            $tourObj->changeTourStatus($tourId,3);
            
            $msg = "Tour Completed Successfully";
            $msg = base64_encode($msg);

            ?>

            <script>
                window.location="../view/pending-tours.php?msg=<?php echo $msg;?>&success=true";
            </script>

            <?php
        }
        catch(Exception $e){
            
            $msg= $e->getMessage();
            $msg= base64_encode($msg);
            ?>
    
            <script>
                window.location="../view/pending-tours.php?msg=<?php echo $msg;?>";
            </script>
            <?php
            
        }
        
    break;
    
    case "load_tour_bus_list":
        
        $tourId = $_POST['tourId'];
        
        $busResult = $tourObj->getBusListOfATour($tourId);
        
        ?>
            
            <div class="row">
                <div class="col-md-12">
                    <table class="table" id="tour_bus_list_table">
                        <thead>
                            <tr>
                                <th>Vehicle No</th>
                                <th>Category</th>
                                <th>Inspection Status</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php while($busRow = $busResult->fetch_assoc()){
                                
                                //Bus Information
                                $busId = $busRow['bus_id'];
                                
                                $inspectionData = $inspectionObj->getInspectionResultOfABusAssignedToATour($tourId,$busId);
                                
                                if($inspectionData->num_rows==0){
                                    
                                    $inspectionStatusDisplay = "Inspection Not Assigned Yet";
                                    
                                }elseif($inspectionData->num_rows==1){
                                    
                                    $inspectionRow = $inspectionData->fetch_assoc();
                                    
                                    if($inspectionRow["inspection_status"]==1){
                                        
                                        $inspectionStatusDisplay = "Inspection Assigned";
                                    }
                                    elseif($inspectionRow["inspection_status"]==2){
                                        
                                        $inspectionStatusDisplay = "Inspection Passed";
                                        
                                    }elseif($inspectionRow["inspection_status"]==3){
                                        
                                        $inspectionStatusDisplay = "Inspection Failed";
                                    }
                                }
                            
                                //Category Information
                                $categoryId = $busRow['category_id'];
                                $categoryResult = $busObj->getBusCategory($categoryId);
                                $categoryRow = $categoryResult->fetch_assoc();
                            ?>
                            
                            <tr>
                                <td><?php echo $busRow['vehicle_no']?></td>
                                <td><?php echo $categoryRow['category_name']?></td>
                                <td><?php echo $inspectionStatusDisplay;?></td>
                            </tr>
                        <?php }?>    
                        </tbody>
                    </table>
                </div>
            </div>
        
        <?php
        
    break;
    
    case "reassign_bus_to_tour":
        
        try{
            
            $inspectionId = $_POST["inspection_id"];
            $tourId = $_POST["tour_id"];
            $oldBusId = $_POST["old_bus_id"];
            
            $busId = $_POST["bus_id"];
            
            if($busId==""){
                throw new Exception("Select The New Bus");
            }
            
            $tourObj->reAssignABusForATour($tourId,$oldBusId,$busId);
            
            $inspectionObj->changeInspectionStatus($inspectionId,4);
            
            $inspectionObj->scheduleInspection($busId,$tourId);
            
            $msg = "New Bus Assigned Successfully & Scheduled An Inspection For The New Bus";
            $msg = base64_encode($msg);

            ?>

            <script>
                window.location="../view/inspection-failed.php?msg=<?php echo $msg;?>&success=true";
            </script>

            <?php
        }
        catch(Exception $e){
            
            $inspectionId = base64_encode($inspectionId);
            
            $msg= $e->getMessage();
            $msg= base64_encode($msg);
            ?>
    
            <script>
                window.location="../view/reassign-bus.php?msg=<?php echo $msg;?>&inspection_id=<?php echo $inspectionId;?>";
            </script>
            <?php
        }
        
    break;
    
    case "pending_tours_filtered":
        
        $dateFrom = $_POST["dateFrom"];
        $dateTo = $_POST["dateTo"];
        
        $tourResult = $tourObj->getOngoingToursFiltered($dateFrom,$dateTo);
        
        while($tourRow = $tourResult->fetch_assoc()){ ?>
        <tr>
            <td><?php echo $tourRow['customer_fname']." ".$tourRow['customer_lname'];?></td>
            <td style="white-space: nowrap"><?php echo $tourRow['start_date'];?></td>
            <td style="white-space: nowrap"><?php echo $tourRow['end_date'];?></td>
            <td><?php echo $tourRow['destination'];?></td>
            <td style="white-space: nowrap"><?php echo $tourRow['invoice_number'];?></td>
            <td>
                <a href="#" data-toggle="modal" onclick="loadTour(<?php echo $tourRow['tour_id'];?>)" data-target="#completeTourModal" 
                   class="btn btn-xs btn-success" style="margin:2px;display:<?php echo checkPermissions(84); ?>">
                    <span class="glyphicon glyphicon-ok"></span>                                                  
                    Complete
                </a>
                <a href="#" data-toggle="modal" onclick="loadTourBusList(<?php echo $tourRow['tour_id'];?>)" data-target="#bus_list" 
                   class="btn btn-xs btn-info" style="margin:2px;display:<?php echo checkPermissions(85); ?>">
                    <span class="glyphicon glyphicon-ok"></span>                                                  
                    View Assigned Buses
                </a>
                <a href="../controller/tour_controller.php?status=cancel_tour&tour_id=<?php echo base64_encode($tourRow['tour_id']);?>" 
                   class="btn btn-xs btn-danger" style="margin:2px;display:<?php echo checkPermissions(86); ?>">
                    <span class="glyphicon glyphicon-remove"></span>                                                  
                    Cancel
                </a>
            </td>
        </tr>
        <?php }
        
    break;

}
