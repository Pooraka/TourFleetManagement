<?php
include_once '../commons/session.php';
include_once '../model/bus_model.php';
include_once '../model/service_detail_model.php';

//get user information from session
$userSession=$_SESSION["user"];
$userId = $userSession['user_id'];

$busObj = new Bus();
$serviceDetailObj = new ServiceDetail();

if(!isset($_GET["status"])){
    ?>
    <script>
        window.location= "../view/login.php";
    </script>
    <?php
}

$status= $_GET["status"];

switch ($status){
    
    case "initiate_service":
        
        try{
        
            $serviceStationId = $_POST["service_station_id"];
            $busId = $_POST["bus_id"];
            $currentMileage = $_POST["currentmileage"];
            
            if($serviceStationId==""){
                throw new Exception("Select a Service Station");
            }
            if($busId==""){
                throw new Exception("Select a Vehicle");
            }
            if($currentMileage==""){
                throw new Exception("Enter the Current Mileage");
            }
            if($currentMileage<0){
                throw new Exception("Current Mileage Cannot Be Less Than 0 Km");
            }

            
            $busResult = $busObj->getBus($busId);
            $busRow = $busResult->fetch_assoc();
            
            $existingCurrentMileage = $busRow["current_mileage_km"];
            
            if($currentMileage<$existingCurrentMileage){
                throw new Exception("Entered current mileage cannot be less than bus's existing current mileage");
            }
            
            $startDate = date('Y-m-d');
/**            $currentMileageAsAt = date('Y-m-d H:i:s', time());
 *              $busObj->updateBusMileage($busId, $currentMileage, $currentMileageAsAt);
 * 
 *  This is commented as now when services are initiated bus mileage is not needed to updated until
 * service is completed
 */
            
            $serviceDetailObj->initiateService($busId, $serviceStationId, $startDate, $currentMileage,$busRow['bus_status'],$userId);

            $busObj->changeBusStatus($busId,3);
            
            $msg= $busRow['vehicle_no']." Service Initiated";
            $msg= base64_encode($msg);
            ?>
    
            <script>
                window.location="../view/view-ongoing-services.php?msg=<?php echo $msg;?>&success=true";
            </script>
            <?php
        }
        catch(Exception $e){
            
            $msg= $e->getMessage();
            $msg= base64_encode($msg);
            ?>
    
            <script>
                window.location="../view/initiate-service.php?msg=<?php echo $msg;?>";
            </script>
            <?php
        }
    break;
    
    case "cancel_service":
        
        $serviceId = $_GET['service_id'];
        $serviceId = base64_decode($serviceId);
        
        $serviceDetailResult = $serviceDetailObj->getServiceDetail($serviceId);
        $serviceDetailRow = $serviceDetailResult->fetch_assoc();
        
        //if someone tries to cancel a service that is not ongoing(by URL) they will be directed to view ongoing services
        if ($serviceDetailRow['service_status'] != 1) {
            ?>
                
                <script>
                    window.location="/tourfleetmanagement/view/view-ongoing-services.php";
                </script>
            <?php
            exit();
        }

        $previousBusStatus = $serviceDetailRow['previous_bus_status'];
        $busId = $serviceDetailRow['bus_id'];
        
        $busObj->changeBusStatus($busId, $previousBusStatus);
        
        $cancelledDate = date('Y-m-d');
        
        $serviceDetailObj->cancelService($serviceId,$userId,$cancelledDate);
        
        $msg = "Service Cancelled Successfully";
        $msg = base64_encode($msg);
        
        ?>
            <script>
                window.location="../view/view-ongoing-services.php?msg=<?php echo $msg;?>&success=true";
            </script>
        <?php
        
    break;

    case "complete_service":
        
        try{
            
            $serviceId = $_POST['service_id'];
            $cost = $_POST['cost'];
            $completedDate = date('Y-m-d');
            $invoice = $_FILES['invoice'];
            $mileageAtService = $_POST['mileage_at_service'];
            $busId = $_POST['bus_id'];
            $extension="";
            $invoiceNumber = $_POST['invoice_number'];
            
            if($invoiceNumber==""){
                throw new Exception("Invoice Number Cannot Be Empty");
            }
            if($cost==""){
                throw new Exception("Cost Cannot Be Empty");
            }
            if($cost<=0){
                throw new Exception("Cost Must Be Higher Than 0 LKR");
            }
            if($invoice['size'] <= 0){
                throw new Exception("Invoice Must Be Attached");
            }
            if($invoice['type']=="image/jpeg"){
                $extension=".jpg";
            }elseif ($invoice['type']=="image/png") {
                $extension=".jpg";
            }elseif ($invoice['type']=="application/pdf") {
                $extension=".pdf";
            }else{
                throw new Exception("Invoice File Type Not Supported, Please Attach a PDF/PNG/JPEG");
            }
            
            $fileName= uniqid('svsinv_').$extension;
            $path="../documents/busserviceinvoices/$fileName";
            move_uploaded_file($invoice["tmp_name"],$path);
            
            $serviceDetailObj->completeService($serviceId, $completedDate, $cost, $fileName, $userId,$invoiceNumber);
            $busObj->updateServicedBus($busId, $mileageAtService, $completedDate);
            
            $currentMileageAsAt = date('Y-m-d H:i:s', time());
            $busObj->updateBusMileage($busId, $mileageAtService, $currentMileageAsAt);
            
            $msg = "Service Completed Successfully";
            $msg = base64_encode($msg);
            ?>
                <script>
                    window.location="../view/view-ongoing-services.php?msg=<?php echo $msg; ?>&success=true";
                </script>
            <?php
        }
        catch(Exception $e){
            
            $serviceId = base64_encode($serviceId);
            
            $msg= $e->getMessage();
            $msg= base64_encode($msg);
            ?>
    
            <script>
                window.location="../view/complete-service.php?msg=<?php echo $msg;?>&service_id='<?php echo $serviceId;?>'";
            </script>
            <?php
        }
        
    break;
    
    case "update_service":
        
        try{
        
            $serviceId = $_POST['service_id'];
            $cost = $_POST['cost'];
            $invoiceNumber = $_POST['invoice_number'];
            $invoice = $_FILES['invoice'];
            $extension="";
            
            $serviceDetailResult = $serviceDetailObj->getServiceDetail($serviceId);
            $serviceDetailRow = $serviceDetailResult->fetch_assoc();
            
            $prevInvoice = $serviceDetailRow['invoice'];
            
            if($invoiceNumber==""){
                throw new Exception("Invoice Number Cannot Be Empty");
            }
            if($cost==""){
                throw new Exception("Cost Cannot Be Empty");
            }
            if($cost<=0){
                throw new Exception("Cost Must Be Higher Than 0 LKR");
            }
            
            
            if($invoice['name']!=""){
                
                if($invoice['type']=="image/jpeg"){
                    
                $extension=".jpg";
                
                }elseif ($invoice['type']=="image/png") {
                    
                $extension=".jpg";
                
                }elseif ($invoice['type']=="application/pdf") {
                    
                $extension=".pdf";
                
                }else{
                    
                throw new Exception("Invoice File Type Not Supported, Please Attach a PDF/PNG/JPEG");
                }
                
                //Adding new invoice
                $fileName= uniqid('svsinv_').$extension;
                $path="../documents/busserviceinvoices/$fileName";
                move_uploaded_file($invoice["tmp_name"],$path);
                
                //remove old invoice
                unlink("../documents/busserviceinvoices/"."$prevInvoice");
            }else{
                
                $fileName = $prevInvoice;
            }
        
            $serviceDetailObj->updatePastService($serviceId, $cost, $fileName,$invoiceNumber);
            
            $msg = "Service Record Updated Successfully";
            $msg = base64_encode($msg);
            
            ?>
            
            <script>
                window.location="../view/service-history.php?msg=<?php echo $msg;?>&success=true";
            </script>
            
            <?php
        }
        catch(Exception $e){
            
            $serviceId = base64_encode($serviceId);
            
            $msg= $e->getMessage();
            $msg= base64_encode($msg);
            ?>
    
            <script>
                window.location="../view/edit-service-record.php?msg=<?php echo $msg;?>&service_id='<?php echo $serviceId;?>'";
            </script>
            <?php
        }
    break;
    
    case "service_cost_trend":
        
        header('Content-Type: application/json');
        
        try{
        
            $dateFrom = $_POST["dateFrom"];
            $dateTo = $_POST["dateTo"];
            $serviceStationId = $_POST["serviceStationId"];

            if($dateFrom == "" || $dateTo == ""){
                throw new Exception("Please select both start and end dates.");
            }

            if($dateFrom > $dateTo){
                throw new Exception("End date should be equal to or greater than start date.");
            }
            
            $serviceCostTrendResult = $serviceDetailObj->getServiceCostTrend($dateFrom, $dateTo, $serviceStationId);

            $dates = [];
            $costs = [];
            
            if ($serviceCostTrendResult->num_rows > 0) {
                while ($serviceCostTrendRow = $serviceCostTrendResult->fetch_assoc()) {

                    array_push($dates, $serviceCostTrendRow['paid_date']);
                    array_push($costs, (float)$serviceCostTrendRow['total_cost']);
                }
            }

            echo json_encode(['dates' => $dates, 'costs' => $costs]);
        
        }
        catch(Exception $e){
            
            echo json_encode(['error' => $e->getMessage()]);
            
        }
    
    break;
    
    case "service_payments_monthly_chart":
        
        header('Content-Type: application/json');
        
        try{

            $startMonth =  $_POST['startMonth'];
            $endMonth =  $_POST['endMonth'];
            $serviceStationId =  $_POST['serviceStationId'];

            if (empty($startMonth) || empty($endMonth)) {
                throw new Exception("Start and End months are required.");
            }
            
            if ($startMonth > $endMonth) {
                throw new Exception("End month should be greater than start month.");
            }

            $servicePaymentResult = $serviceDetailObj->getMonthlyServicePayments($startMonth,$endMonth,$serviceStationId);

            $months = [];
            $payments = [];

            if($servicePaymentResult->num_rows>0){

                while($servicePaymentRow = $servicePaymentResult->fetch_assoc()){

                    array_push($months,$servicePaymentRow["month"]);
                    array_push($payments,$servicePaymentRow["total_cost"]);
                }
            }
            
            echo json_encode(['months' => $months, 'payments' => $payments]);
        
        }
        catch(Exception $e){
            
            echo json_encode(['error' => $e->getMessage()]);
        }
        
    break;
    
    case "service_list_of_a_payment_modal":

        $paymentId = $_POST['paymentId'];

        $serviceDetailResult = $serviceDetailObj->getServiceListOfPayment($paymentId);

        ?> 
        <div class="row">
            <div class="col-md-12">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Vehicle No</th>
                            <th>Serviced Date</th>
                            <th>Station Name</th>
                            <th>Cost (LKR)</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($serviceDetailRow = $serviceDetailResult->fetch_assoc()){?>
                        <tr>
                            <td><?php echo $serviceDetailRow["vehicle_no"];?></td>
                            <td><?php echo $serviceDetailRow["completed_date"];?></td>
                            <td><?php echo $serviceDetailRow["service_station_name"];?></td>
                            <td><?php echo number_format($serviceDetailRow["cost"],2);?></td>
                            <td>
                                <a href="view-service-record.php?service_id=<?php echo base64_encode($serviceDetailRow["service_id"]); ?>" 
                                   class="btn btn-primary" style="margin:2px;display:<?php echo checkPermissions(123); ?>" target="_blank">                                                
                                     View Service
                                </a>
                            </td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
        
        
        <?php

    break;

    case "all_past_services_filtered":

        $status = $_POST['status'];

        $serviceDetailResult = $serviceDetailObj->getPastServicesFiltered($status);

        while($serviceDetailRow = $serviceDetailResult->fetch_assoc()){
                                            
                    $busId = $serviceDetailRow['bus_id'];
                    $busResult = $busObj->getBus($busId);
                    $busRow = $busResult->fetch_assoc();
                    $serviceStatus = $serviceDetailRow['service_status'];
                    $statusDisplay = match($serviceStatus){
                        '-1'=>'Cancelled',
                        '1'=>'Ongoing',
                        '2'=>'Completed',
                        '3'=>'Completed & Paid'
                    };
                    
                    $statusDisplayClass = match($serviceStatus){
                        '-1'=>'label label-danger',
                        '1'=>'label label-warning',
                        '2'=>'label label-success',
                        '3'=>'label label-primary'
                    };
                    
                    $serviceId = $serviceDetailRow['service_id'];
                    $serviceId = base64_encode($serviceId);
        ?>
        <tr>
            <td><?php echo $busRow['vehicle_no'];?></td>
            <td><?php echo $servicedDate = ($serviceDetailRow['completed_date']=="")?"Not Applicable":$serviceDetailRow['completed_date'];?> </td>
            <td><?php echo number_format($serviceDetailRow['mileage_at_service'],0);?>&nbsp; Km </td>
            <td><?php echo $serviceDetailRow['invoice_number'];?></td>
            <td><span class="<?php echo $statusDisplayClass;?>"><?php echo $statusDisplay;?></span> </td>
            <td>
                <a href="view-service-record.php?service_id=<?php echo $serviceId; ?>" 
                    class="btn btn-info" style="margin:2px;display:<?php echo checkPermissions(123); ?>">
                    <span class="glyphicon glyphicon-search"></span>                                                  
                    View
                </a>
                <?php if($serviceStatus==2){ ?>
                <a href="edit-service-record.php?service_id=<?php echo $serviceId; ?>" 
                    class="btn btn-warning" style="margin:2px;display:<?php echo checkPermissions(124); ?>">
                    <span class="glyphicon glyphicon-pencil"></span>
                    Edit
                </a>
                <?php } ?>
            </td>
        </tr>
        <?php            
        }
        
    break;
}
