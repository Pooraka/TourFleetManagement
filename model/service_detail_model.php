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
    
    public function getPastServicesFiltered($status){
        
        $con = $GLOBALS["con"];
        
        $sql = "SELECT * FROM service_detail WHERE 1 ";
        
        if($status!=""){
            $sql.="AND service_status='$status' ";
        }else{
            $sql.="AND service_status!='1' ";
        }
        
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
        
        $sql = "UPDATE service_detail SET payment_id=?, paid_date='$paidDate', service_status='3' WHERE service_id =?";
    
        $stmt = $con->prepare($sql);

        $stmt->bind_param("ii", $paymentId, $serviceId); 

        $stmt->execute();

        $stmt->close();
        
    }
    
    public function getServiceCostTrend($dateFrom,$dateTo,$serviceStationId){
        
        $con = $GLOBALS["con"];
        
        $sql = "SELECT sd.paid_date, SUM(sd.cost) AS total_cost FROM service_detail sd WHERE sd.service_status='3' ";
        
        if($dateFrom!="" && $dateTo!=""){
            $sql.="AND sd.paid_date BETWEEN '$dateFrom' AND '$dateTo' ";
        }
        
        if($serviceStationId!=""){
            $sql.="AND sd.service_station_id='$serviceStationId' ";
        }
        
        $sql.="GROUP BY sd.paid_date ORDER BY sd.paid_date ASC";
        
        $result = $con->query($sql) or die($con->error);
        return $result;
    }
    
    public function getMonthlyServicePayments($startMonth,$endMonth,$serviceStationId){
        
        $con = $GLOBALS["con"];
        
        $sql = "SELECT DATE_FORMAT(paid_date,'%Y-%m') AS month, SUM(cost) AS total_cost "
                . "FROM service_detail WHERE service_status='3' ";
        
        if($startMonth!="" && $endMonth!=""){
            
            $sql.="AND DATE_FORMAT(paid_date,'%Y-%m') BETWEEN '$startMonth' AND '$endMonth' ";
        }
        
        if($serviceStationId!=""){
            
            $sql.="AND service_station_id='$serviceStationId' ";
        }
        
        $sql.="GROUP BY month ORDER BY month ASC";
        
        $result = $con->query($sql) or die($con->error);
        return $result;
    }

    public function getServiceListOfPayment($paymentId){
        
        $con = $GLOBALS["con"];
        $sql = "SELECT sd.*, b.*, ss.service_station_name FROM service_detail sd JOIN bus b ON sd.bus_id = b.bus_id ".
                "JOIN service_station ss ON sd.service_station_id = ss.service_station_id ".
                " WHERE sd.payment_id = ?";

        $stmt = $con->prepare($sql);

        $stmt->bind_param("i", $paymentId);

        $stmt->execute();

        $result = $stmt->get_result();

        $stmt->close();

        return $result;
    }
}