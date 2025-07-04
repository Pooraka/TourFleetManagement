<?php
include_once '../commons/session.php';
include_once '../model/bus_model.php';


//get user information from session
$userSession=$_SESSION["user"];
$user_id = $userSession['user_id'];

$busObj = new Bus();

if(!isset($_GET["status"])){
    ?>
    <script>
        window.location= "../view/login.php";
    </script>
    <?php
}

$status= $_GET["status"];

switch ($status){
    
    case "add_bus":
        
        try{
            
            $vehicleNo = $_POST["vehicleno"];
            $make = $_POST["make"];
            $model = $_POST["model"];
            $year = $_POST["year"];
            $capacity = $_POST["capacity"];
            $serviceIntervalKM = $_POST["serviceintervalkm"];
            $currentMileage = $_POST["currentmileage"];
            $lastServiceKM = $_POST["lastservicekm"];
            $serviceIntervalMonths = $_POST["serviceintervalmonths"];
            $lastServiceDate = $_POST["lastservicedate"];
            $ac = $_POST["ac"];
            $category = $_POST["category"];
            
            if($vehicleNo == ""){
                throw new Exception("Vehicle number cannot be empty");
            }

            $patternVehicleNo = "/^([A-Z]{3}[-][0-9]{4}|[A-Z]{2}[-][0-9]{4}|[0-9]{3}[-][0-9]{4}|[0-9]{2}[-][0-9]{4})$/";

            if(!preg_match($patternVehicleNo, $vehicleNo)){
                throw new Exception("Invalid vehicle number format");
            }

            if($make == ""){
                throw new Exception("Make cannot be empty");
            }

            if($model == ""){
                throw new Exception("Model cannot be empty");
            }

            if($year == ""){
                throw new Exception("Year cannot be empty");
            }

            $patternYear = "/^(19|20)\d{2}$/";

            if(!preg_match($patternYear, $year)){
                throw new Exception("Invalid year format");
            }

            if($capacity == ""){
                throw new Exception("Passenger capacity cannot be empty");
            }

            if(is_nan($capacity) || $capacity <= 0){
                throw new Exception("Passenger capacity must be a positive number");
            }

            if($serviceIntervalKM == ""){
                throw new Exception("Service interval cannot be empty");
            }

            if(is_nan($serviceIntervalKM) || $serviceIntervalKM <= 0){
                throw new Exception("Service interval must be a positive number");
            }

            if($currentMileage == ""){
                throw new Exception("Current mileage cannot be empty");
            }

            if(is_nan($currentMileage) || $currentMileage < 0){
                throw new Exception("Current mileage must be 0 Km or above");
            }

            if($lastServiceKM == ""){
                throw new Exception("Last service mileage cannot be empty");
            }

            if(is_nan($lastServiceKM) || $lastServiceKM < 0){
                throw new Exception("Last service mileage must be 0 Km or above");
            }

            if($serviceIntervalMonths == ""){
                throw new Exception("Service interval months cannot be empty");
            }

            if(is_nan($serviceIntervalMonths) || $serviceIntervalMonths <= 0){
                throw new Exception("Service interval months must be a positive number");
            }

            if($lastServiceDate == ""){
                throw new Exception("Last service date cannot be empty");
            }

            if($ac == ""){
                throw new Exception("Please select if AC is available or not");
            }

            if($category == ""){
                throw new Exception("Please select a category");
            }

            if($lastServiceKM > $currentMileage){
                throw new Exception("Last service mileage cannot be greater than current mileage");
            }
            
            //Check if the user entered vehicle number already exists in the database
            $busResult = $busObj->checkIfBusIsAlreadyExist($vehicleNo);
            
            if($busResult->num_rows>0){
                throw new Exception ("Vehicle Number Already Exist");
            }
            
            $busId = $busObj->addBus($category, $vehicleNo, $make, $model, $year, $capacity, $ac, $serviceIntervalKM, $lastServiceKM, $serviceIntervalMonths, $lastServiceDate);
            
            $currentMileageAsAt = date('Y-m-d H:i:s', time());
            
            $busObj->updateBusMileage($busId, $currentMileage, $currentMileageAsAt);
            
            $msg = "Bus $vehicleNo Added Successfully";
            $msg = base64_encode($msg);

            ?>

            <script>
                window.location="../view/view-buses.php?msg=<?php echo $msg;?>";
            </script>

            <?php
            
        }
        catch(Exception $e){
            
            $msg= $e->getMessage();
            $msg= base64_encode($msg);
            ?>
    
            <script>
                window.location="../view/add-bus.php?msg=<?php echo $msg;?>";
            </script>
            <?php

        }
        
    break;
    
    
    case "remove_bus":
        
        $busId = $_GET['bus_id'];
        $busId = base64_decode($busId);
        
        $busObj->removeBus($busId,$user_id);
        
        $msg = "Bus Removed Successfully";
        $msg = base64_encode($msg);
        
        ?>
            <script>
                window.location="../view/view-buses.php?msg=<?php echo $msg;?>";
            </script>
        <?php
        
    break;
        
    case "update_bus":
        
        try{
            
            $busId = $_POST["bus_id"];
            $vehicleNo = strtoupper(trim($_POST["vehicleno"]));
            $make = $_POST["make"];
            $model = $_POST["model"];
            $year = $_POST["year"];
            $capacity = $_POST["capacity"];
            $serviceIntervalKM = $_POST["serviceintervalkm"];
            $currentMileage = $_POST["currentmileage"];
            $lastServiceKM = $_POST["lastservicekm"];
            $serviceIntervalMonths = $_POST["serviceintervalmonths"];
            $lastServiceDate = $_POST["lastservicedate"];
            $ac = $_POST["ac"];
            $category = $_POST["category"];
            
            if($vehicleNo == ""){
                throw new Exception("Vehicle number cannot be empty");
            }

            $patternVehicleNo = "/^([A-Z]{3}[-][0-9]{4}|[A-Z]{2}[-][0-9]{4}|[0-9]{3}[-][0-9]{4}|[0-9]{2}[-][0-9]{4})$/";

            if(!preg_match($patternVehicleNo, $vehicleNo)){
                throw new Exception("Invalid vehicle number format");
            }

            if($make == ""){
                throw new Exception("Make cannot be empty");
            }

            if($model == ""){
                throw new Exception("Model cannot be empty");
            }

            if($year == ""){
                throw new Exception("Year cannot be empty");
            }

            $patternYear = "/^(19|20)\d{2}$/";

            if(!preg_match($patternYear, $year)){
                throw new Exception("Invalid year format");
            }

            if($capacity == ""){
                throw new Exception("Passenger capacity cannot be empty");
            }

            if(is_nan($capacity) || $capacity <= 0){
                throw new Exception("Passenger capacity must be a positive number");
            }

            if($serviceIntervalKM == ""){
                throw new Exception("Service interval cannot be empty");
            }

            if(is_nan($serviceIntervalKM) || $serviceIntervalKM <= 0){
                throw new Exception("Service interval must be a positive number");
            }

            if($currentMileage == ""){
                throw new Exception("Current mileage cannot be empty");
            }

            if(is_nan($currentMileage) || $currentMileage < 0){
                throw new Exception("Current mileage must be 0 Km or above");
            }

            if($lastServiceKM == ""){
                throw new Exception("Last service mileage cannot be empty");
            }

            if(is_nan($lastServiceKM) || $lastServiceKM < 0){
                throw new Exception("Last service mileage must be 0 Km or above");
            }

            if($serviceIntervalMonths == ""){
                throw new Exception("Service interval months cannot be empty");
            }

            if(is_nan($serviceIntervalMonths) || $serviceIntervalMonths <= 0){
                throw new Exception("Service interval months must be a positive number");
            }

            if($lastServiceDate == ""){
                throw new Exception("Last service date cannot be empty");
            }

            if($ac == ""){
                throw new Exception("Please select if AC is available or not");
            }

            if($category == ""){
                throw new Exception("Please select a category");
            }

            if($lastServiceKM > $currentMileage){
                throw new Exception("Last service mileage cannot be greater than current mileage");
            }
            
            $existingBusResult = $busObj->getBus($busId);
            $existingBusRow = $existingBusResult->fetch_assoc();
            
            if($existingBusRow['current_mileage_km']!=$currentMileage){
                
                $currentMileageAsAt = date('Y-m-d H:i:s', time());
                
                $busObj->updateBusMileage($busId, $currentMileage, $currentMileageAsAt);
            }
            
            $busObj->updateBus($busId, $category, $vehicleNo, $make, $model, $year, $capacity, $ac, $serviceIntervalKM, $lastServiceKM, $serviceIntervalMonths, $lastServiceDate);
            
            $msg = "Bus $vehicleNo Updated Successfully";
            $msg = base64_encode($msg);
            
            ?>
            
            <script>
                window.location="../view/view-buses.php?msg=<?php echo $msg;?>";
            </script>
            
            <?php
        }
        
        catch(Exception $e){
            
            $msg= $e->getMessage();
            $msg= base64_encode($msg);
            ?>
    
            <script>
                window.location="../view/edit-bus.php?msg=<?php echo $msg;?>";
            </script>
            <?php
            
            
        }
        
    break;
    
    case "categories_available_for_tour":
        
        $startDate = $_POST['startDate'];
        $endDate = $_POST['endDate'];
        
        if($startDate>$endDate){
            echo "<center><b style='color:red;'>Start date cannot be greater than end date</b></center>";
        }else{
        
            $busCategoryResult = $busObj->getBusCategoryAvailableForTour($startDate, $endDate);
            ?>  
            <table class="table">
                <thead>
                    <tr>
                        <th>Bus Category</th>
                        <th>Available Count</th>
                        <th>Requested Count</th>
                    </tr>
                </thead>
                <tbody>
                            <?php
            /*This loop is used to get bus categories needed at quotation generate feature
             *Further associative array is set to be created using input[@type=number]
             * element with category_id dynamically
             */                 
            while($busCategoryRow = $busCategoryResult->fetch_assoc()){?>

                    <tr>
                        <td><?php echo $busCategoryRow['category_name'];?></td>
                        <td><?php echo $busCategoryRow['count'];?></td>
                        <td><input type="number" name="request_count[<?php echo $busCategoryRow['category_id']; ?>]" 
                                   class="form-control" 
                                   placeholder="Enter count" 
                                   min="0" 
                                   max="<?php echo $busCategoryRow['count'];?>"
                                   ></td>
                    </tr>
            <?php } ?>
                </tbody>
            </table>
                <?php
        }
    break;
}