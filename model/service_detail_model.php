<?php

include_once '../commons/db_connection.php';

$dbCon= new DbConnection();

class ServiceDetail{
    
    public function initiateService($busId, $serviceStationId,$startDate,$currentMileage,$previousBusStatus,$userId){
        
        $con = $GLOBALS["con"];
        
        $sql ="INSERT INTO service_detail (bus_id,service_station_id,start_date,mileage_at_service,previous_bus_status,initiated_by) "
            . "VALUES (?,?,?,?,?,?)";
    
        $stmt = $con->prepare($sql);

        $stmt->bind_param("iisiii", $busId, $serviceStationId, $startDate, $currentMileage, $previousBusStatus, $userId); 

        $stmt->execute();

        $serviceId=$con->insert_id;

        $stmt->close();

        return $serviceId;
    }
    
    public function getOngoingServices(){ 
        
        $con = $GLOBALS["con"];
        
        $sql = "SELECT * FROM service_detail WHERE service_status='1'";
        
        $result = $con->query($sql) or die ($con->error);
        return $result;
    }
    
    public function cancelService($serviceId,$userId,$cancelledDate){
        
        $con = $GLOBALS["con"];
        
        $sql = "UPDATE service_detail SET service_status = '-1', cancelled_by = ?, cancelled_date=? WHERE service_id =?";
    
        $stmt = $con->prepare($sql);

        $stmt->bind_param("isi", $userId, $cancelledDate, $serviceId); 

        $stmt->execute();

        $stmt->close();
    }
    
    public function getServiceDetail($serviceId){
        
        $con = $GLOBALS["con"];
        
        $sql = "SELECT * FROM service_detail WHERE service_id=?";
        
        $stmt = $con->prepare($sql);

        $stmt->bind_param("i", $serviceId); // Bind the serviceId parameter as an integer

        $stmt->execute(); // Execute the statement

        $result = $stmt->get_result(); // Get the result object
        
        $stmt->close(); //Close the statement
        return $result;
    }
    
    public function completeService($serviceId,$completedDate,$cost,$invoice,$userId,$invoiceNumber){
        
        $con = $GLOBALS["con"];

        $sql = "UPDATE service_detail SET service_status ='2', completed_date=?, "
            . "cost=?, invoice=?, completed_by=?, invoice_number=? WHERE service_id =?";
    
        $stmt = $con->prepare($sql);

        $stmt->bind_param("sdsisi", $completedDate, $cost, $invoice, $userId, $invoiceNumber, $serviceId); 

        $stmt->execute();

        $stmt->close();
    }
    
    public function getPastServices(){
        
        $con = $GLOBALS["con"];
        
        $sql = "SELECT * FROM service_detail WHERE service_status!='1'";
        
        $result = $con->query($sql) or die ($con->error);
        return $result;
    }
    
    public function updatePastService($serviceId,$cost,$invoice,$invoiceNumber){
        
        $con = $GLOBALS["con"];
        
        $sql = "UPDATE service_detail SET cost=?, invoice=?, invoice_number=? WHERE service_id =?";
    
        $stmt = $con->prepare($sql);

        $stmt->bind_param("dssi", $cost, $invoice, $invoiceNumber, $serviceId); 

        $stmt->execute();

        $stmt->close();
    }
    
    public function getPaymentPendingServiceStations(){
        
        $con = $GLOBALS["con"];
        
        $sql = "SELECT s.service_station_name, s.service_station_id, SUM(d.cost) AS total_due, COUNT(d.service_id) AS service_count "
                . "FROM service_detail d, service_station s "
                . "WHERE service_status='2' AND s.service_station_id = d.service_station_id GROUP BY service_station_id";
        
        $result = $con->query($sql) or die ($con->error);
        return $result;
    }
    
    public function getPaymentPendingServices($serviceStationId){
        
        $con = $GLOBALS["con"];

        $sql = "SELECT * FROM service_detail d, bus b, service_station s WHERE "
            . "d.service_station_id = s.service_station_id AND d.bus_id = b.bus_id AND d.service_station_id = ? AND d.service_status='2'";
    
        $stmt = $con->prepare($sql);

        $stmt->bind_param("i", $serviceStationId); 

        $stmt->execute();

        $result = $stmt->get_result();

        $stmt->close();

        return $result;
    }
    
    public function updatePaidService($serviceId,$paymentId){
        
        $con = $GLOBALS["con"];
        
        $paidDate = date("Y-m-d");
        
        $sql = "UPDATE service_detail SET payment_id=?, paid_date='$paidDate' service_status='3' WHERE service_id =?";
    
        $stmt = $con->prepare($sql);

        $stmt->bind_param("ii", $paymentId, $serviceId); 

        $stmt->execute();

        $stmt->close();
        
    }
    
    
    public function getMonthlyServiceCostTrend($startMonth, $endMonth){
        
        $con = $GLOBALS["con"];
        
        $serviceStatus = '3';
        
        $sql = "SELECT DATE_FORMAT(completed_date,'%Y-%m') AS month, SUM(cost) AS total_cost "
                . "FROM service_detail "
                . "WHERE service_status = ? AND DATE_FORMAT(completed_date, '%Y-%m') BETWEEN ? AND ? " 
                . "GROUP BY month ORDER BY month ASC";
        
        $stmt = $con->prepare($sql); // Prepare the statement

        $stmt->bind_param("sss", $serviceStatus, $startMonth, $endMonth); // Bind parameters: all three are strings ('s')

        $stmt->execute(); // Execute and get the result object

        $result = $stmt->get_result();
        
        $stmt->close();
        return $result;
        
    }
}