<?php

include_once '../commons/db_connection.php';

$dbCon= new DbConnection();

class Bus{
    
    public function getAllBuses(){
        
        $con = $GLOBALS["con"];

        $sql = "SELECT b.bus_id, b.category_id, b.vehicle_no, b.make, b.model, b.year, b.capacity, "
                . "b.ac_available, b.service_interval_km, b.current_mileage_km, b.current_mileage_as_at, b.last_service_mileage_km, "
                . "b.service_interval_months, b.last_service_date, b.bus_status, c.category_name  "
                . "FROM bus b, bus_category c WHERE b.category_id = c.category_id AND b.bus_status != '-1'";
        
        $result = $con->query($sql) or die ($con->error);
        return $result;
    }
    
    public function getAllBusesFiltered($busStatus,$categoryId){
        
        $con = $GLOBALS["con"];

        $sql = "SELECT b.bus_id, b.category_id, b.vehicle_no, b.make, b.model, b.year, b.capacity, "
                . "b.ac_available, b.service_interval_km, b.current_mileage_km, b.current_mileage_as_at, b.last_service_mileage_km, "
                . "b.service_interval_months, b.last_service_date, b.bus_status, c.category_name  "
                . "FROM bus b, bus_category c WHERE b.category_id = c.category_id  ";
        
        if($busStatus!=""){
            
            $sql.="AND b.bus_status = '$busStatus' ";
        }else{
            $sql.="AND b.bus_status != '-1' ";
        }
        
        if($categoryId!=""){
            $sql.="AND b.category_id ='$categoryId' ";
        }
        
        $result = $con->query($sql) or die ($con->error);
        return $result;
    }
    
    public function getAllBusCategories(){
        
        $con = $GLOBALS["con"];
        
        $sql="SELECT * FROM bus_category";
        
        $result = $con->query($sql) or die ($con->error);
        return $result;
    }
    
    public function checkIfBusIsAlreadyExist($vehicleNo){
        
        $con = $GLOBALS["con"];
    
        $sql="SELECT * FROM bus WHERE vehicle_no = ?";

        $stmt = $con->prepare($sql);

        $stmt->bind_param("s", $vehicleNo);

        $stmt->execute();

        $result = $stmt->get_result();

        $stmt->close();

        return $result;
    }
    
    
    public function addBus($category, $vehicleNo, $make, $model, $year, $capacity, $ac, 
            $serviceIntervalKM, $lastServiceKM, $serviceIntervalMonths, $lastServiceDate){
        
        $con = $GLOBALS["con"];
        
        $sql = "INSERT INTO bus (category_id, vehicle_no, make, model, year, capacity, ac_available, "
                . "service_interval_km, last_service_mileage_km, service_interval_months, last_Service_date)"
                . " VALUES (?,?,?,?,?,?,?,?,?,?,?)";
        
        $stmt = $con->prepare($sql);
        
        $types = "isssiisiiis";
        
        $stmt->bind_param($types,$category,$vehicleNo,$make,$model,$year,$capacity,$ac,
                $serviceIntervalKM,$lastServiceKM,$serviceIntervalMonths,$lastServiceDate);
        
        $stmt->execute();
        
        $busId=$con->insert_id;
        
        $stmt->close();
        
        return $busId;
    }
    
    public function updateBusMileage($busId, $currentMileage, $currentMileageAsAt){
        
        $con = $GLOBALS["con"];
    
        $sql = "UPDATE bus SET current_mileage_km = ?, current_mileage_as_at = ? WHERE bus_id =?";

        $stmt = $con->prepare($sql);

        $stmt->bind_param("isi", $currentMileage, $currentMileageAsAt, $busId);

        $stmt->execute();

        $stmt->close();
    }
    
    public function removeBus($busId,$userId){
        
        $con = $GLOBALS["con"];
    
        $sql = "UPDATE bus SET bus_status = '-1', removed_by = ? WHERE bus_id = ?";

        $stmt = $con->prepare($sql);

        $stmt->bind_param("ii",$userId, $busId);

        $stmt->execute();

        $stmt->close();
    }
    
    public function getBus($busId){
        
        $con = $GLOBALS["con"];
        
        $sql = "SELECT b.bus_id, b.category_id, b.vehicle_no, b.make, b.model, b.year, b.capacity, "
                . "b.ac_available, b.service_interval_km, b.current_mileage_km, b.current_mileage_as_at, b.last_service_mileage_km, "
                . "b.service_interval_months, b.last_service_date, b.bus_status, c.category_name  "
                . "FROM bus b, bus_category c WHERE b.category_id = c.category_id AND b.bus_id = ?";
        
        $stmt = $con->prepare($sql);
        
        $stmt->bind_param("i",$busId);
        
        $stmt->execute();
        
        $result = $stmt->get_result();
        return $result;
    }
    
    public function updateBus($busId, $category, $vehicleNo, $make, $model, $year, $capacity, $ac, $serviceIntervalKM, $lastServiceKM, $serviceIntervalMonths, $lastServiceDate){
        
        $con = $GLOBALS["con"];
    
        $sql = "UPDATE bus SET category_id=?, vehicle_no=?, make=?, model=?, year=?, capacity=? "
                . ", ac_available=?, service_interval_km=?, last_service_mileage_km=?, service_interval_months=? "
                . ", last_service_date=? WHERE bus_id =?";

        $stmt = $con->prepare($sql);

        $stmt->bind_param("isssiisiiisi", $category, $vehicleNo, $make, $model, $year, $capacity, $ac, $serviceIntervalKM, $lastServiceKM, $serviceIntervalMonths, $lastServiceDate, $busId);

        $stmt->execute();

        $stmt->close();
    }
    
    public function getAllBusesToService(){
        
        $con = $GLOBALS["con"];

        $sql = "SELECT b.bus_id, b.category_id, b.vehicle_no, b.make, b.model, b.year, b.capacity, "
                . "b.ac_available, b.service_interval_km, b.current_mileage_km, b.current_mileage_as_at, b.last_service_mileage_km, "
                . "b.service_interval_months, b.last_service_date, b.bus_status, c.category_name  "
                . "FROM bus b, bus_category c WHERE b.category_id = c.category_id "
                . "AND b.bus_status != '-1' AND b.bus_status != '3' ";
        
        $result = $con->query($sql) or die ($con->error);
        return $result;
    }
    
    public function changeBusStatus($busId, $status){
        
        $con = $GLOBALS["con"];
        
        $sql = "UPDATE bus SET bus_status=? WHERE bus_id=?";
        
        $stmt = $con->prepare($sql); 
        
        $stmt->bind_param("ii",$status,$busId); 
        
        $stmt->execute(); 
        
        $stmt->close(); 
    }
    
    public function updateServicedBus($busId,$lastServiceMileage,$lastServiceDate){
        
        $con = $GLOBALS["con"];
    
        $sql = "UPDATE bus SET bus_status='1', last_service_mileage_km=?, "
                . "last_service_date=? WHERE bus_id=?";

        $stmt = $con->prepare($sql);

        $stmt->bind_param("iis", $lastServiceMileage, $lastServiceDate, $busId);

        $stmt->execute();

        $stmt->close();
    }
    
    public function getOperationalBuses(){
        
        $con = $GLOBALS["con"];

        $sql = "SELECT b.bus_id, b.category_id, b.vehicle_no, b.make, b.model, b.year, b.capacity, "
                . "b.ac_available, b.service_interval_km, b.current_mileage_km, b.current_mileage_as_at, b.last_service_mileage_km, "
                . "b.service_interval_months, b.last_service_date, b.bus_status, c.category_name  "
                . "FROM bus b, bus_category c WHERE b.category_id = c.category_id AND b.bus_status = '1'";
        
        $result = $con->query($sql) or die ($con->error);
        return $result;
        
    }
    
    public function getServiceDueBuses(){
       
        $con = $GLOBALS["con"];

        $sql = "SELECT b.bus_id, b.category_id, b.vehicle_no, b.make, b.model, b.year, b.capacity, "
                . "b.ac_available, b.service_interval_km, b.current_mileage_km, b.current_mileage_as_at, b.last_service_mileage_km, "
                . "b.service_interval_months, b.last_service_date, b.bus_status, c.category_name  "
                . "FROM bus b, bus_category c WHERE b.category_id = c.category_id AND b.bus_status = '2'";
        
        $result = $con->query($sql) or die ($con->error);
        return $result;
    }
    

    public function getBusAvailableForTour($startDate,$endDate){
        
        
        $startDate = date('Y-m-d',strtotime($startDate.'-1 day'));
        $endDate = date('Y-m-d',strtotime($endDate.'+1 day'));
        
        $con = $GLOBALS["con"];

        $sql = "SELECT b.*,c.category_name FROM bus b, bus_category c WHERE b.category_id=c.category_id AND b.bus_id NOT IN"
                . " (SELECT DISTINCT bt.bus_id FROM bus_tour bt JOIN tour t ON bt.tour_id = t.tour_id "
                . "WHERE ?<= t.end_date AND ? >= t.start_date AND t.tour_status != '-1')  "
                . "AND b.bus_status = '1'";
        
        $stmt = $con->prepare($sql);
        
        $stmt->bind_param("ss",$startDate,$endDate);
        
        $stmt->execute();
        
        $result = $stmt->get_result();
        
        $stmt->close();
        return $result;
    }
    
    public function getBusAvailableForTourByRequestedCategory($startDate,$endDate,$categoryIdArray){
        
        
        $categoryIdString = implode(',', $categoryIdArray);
        
        $startDate = date('Y-m-d',strtotime($startDate.'-1 day'));
        $endDate = date('Y-m-d',strtotime($endDate.'+1 day'));
        
        $con = $GLOBALS["con"];

        $sql = "SELECT b.*, c.category_name FROM bus b, bus_category c WHERE b.category_id=c.category_id AND b.bus_id NOT IN"
                . " (SELECT DISTINCT bt.bus_id FROM bus_tour bt JOIN tour t ON bt.tour_id = t.tour_id "
                . "WHERE ?<= t.end_date AND ? >= t.start_date AND t.tour_status != '-1')  "
                . "AND b.bus_status = '1' AND b.category_id IN ($categoryIdString)";
        
        $stmt = $con->prepare($sql);
        
        $stmt->bind_param("ss",$startDate,$endDate);
        
        $stmt->execute();
        
        $result = $stmt->get_result();
        return $result;
    }


    /**
     * Sends a list of bus categories for tours
     * 
     * This generates a list of bus categories along with the quantity
     * available given a time period.
     * Used to filter out buses booked for tours when generating quotations
     * 
     * @param date $startDate
     * @param date $endDate
     * @return array|null associative array with category_name, category_id, and a count as keys
     */    
    public function getBusCategoryAvailableForTour($startDate,$endDate){

        $startDate = date('Y-m-d',strtotime($startDate.'-1 day'));
        $endDate = date('Y-m-d',strtotime($endDate.'+1 day'));

        $con = $GLOBALS["con"];

        $sql = "SELECT b.category_id, c.category_name, COUNT(b.bus_id) AS count "
                . "FROM bus b, bus_category c WHERE b.bus_id NOT IN"
                . " ( SELECT DISTINCT bt.bus_id FROM bus_tour bt JOIN tour t ON bt.tour_id = t.tour_id "
                . "WHERE ?<= t.end_date AND ? >= t.start_date AND t.tour_status !='-1')  "
                . "AND c.category_id = b.category_id AND b.bus_status = '1' GROUP BY category_id";

        $stmt = $con->prepare($sql);

        $stmt->bind_param("ss",$startDate,$endDate);

        $stmt->execute();

        $result = $stmt->get_result();
        
        $stmt->close();
        return $result;
    }
    
    public function getCategoryCountByBuses($busArray){
        
        $busIdString = implode(',', $busArray);
        
        $con = $GLOBALS["con"];
        
        $sql = "SELECT b.category_id, COUNT(b.category_id) AS quantity FROM bus b WHERE bus_id IN ($busIdString) GROUP BY b.category_id;";
        
        $result = $con->query($sql) or die ($con->error);
        return $result;     
    }
    
    public function getBusCategory($categoryId){
        
        $con = $GLOBALS["con"];
        
        $sql = "SELECT * FROM bus_category WHERE category_id=?";
        
        $stmt = $con->prepare($sql);
        
        $stmt->bind_param("i",$categoryId);
        
        $stmt->execute();
        
        $result = $stmt->get_result();
        
        $stmt->close();
        return $result;
    }
    
    public function getBusFleetFiltered($categoryId,$status){
        
        $con = $GLOBALS["con"];
        
        $sql = "SELECT b.*,c.* FROM bus b JOIN bus_category c ON c.category_id = b.category_id WHERE b.bus_status!='-1' ";
        
        if($categoryId!="all"){
            
            $sql.="AND b.category_id='$categoryId' ";
        }
        
        if($status!="all"){
            $sql.="AND b.bus_status='$status' ";
        }
        
        $result = $con->query($sql) or die($con->error);
        return $result;
    }

    public function getEntireBusFleetCategoryCount(){

        $con = $GLOBALS["con"];
        
        $sql = "SELECT b.category_id, c.category_name, COUNT(b.bus_id) AS count "
                . "FROM bus b JOIN bus_category c ON b.category_id = c.category_id "
                . "WHERE b.bus_status != '-1' GROUP BY b.category_id, c.category_name";

        $result = $con->query($sql) or die($con->error);
        return $result;
    }

    public function getBusFleetStatusBreakdown(){

        $con = $GLOBALS["con"];
        
        $sql = "SELECT bus_status,

        CASE 
            WHEN bus_status = '1' THEN 'Operational'
            WHEN bus_status = '2' THEN 'Service Due'
            WHEN bus_status = '3' THEN 'In Service'
            WHEN bus_status = '4' THEN 'Inspection Failed'
            ELSE 'Unknown'
        END AS status_name,
        COUNT(bus_id) AS count FROM bus WHERE bus_status != '-1' GROUP BY bus_status, status_name";

        $result = $con->query($sql) or die($con->error);
        return $result;
    }

    public function getUpComingServicesBusCount() {
        
        $con = $GLOBALS["con"];
        
        $sql = "
                    SELECT COUNT(bus_id) AS count 
                    FROM bus
                    WHERE 
                        bus_status NOT IN ('-1', '3','2')
                    AND (
                        
                        ((last_service_mileage_km + service_interval_km) - current_mileage_km) <= 1000
                        OR

                        DATE_ADD(last_service_date, INTERVAL service_interval_months MONTH) <= DATE_ADD(CURDATE(), INTERVAL 14 DAY)
                    )";

        $result = $con->query($sql) or die($con->error);

        $row = $result->fetch_assoc();
        return $row['count'];
    }
}