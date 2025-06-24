<?php

include_once '../commons/db_connection.php';

$dbCon= new DbConnection();

class ServiceDetail{
    
    public function initiateService($busId, $serviceStationId,$startDate,$currentMileage,$previousBusStatus,$userId){
        
        $con = $GLOBALS["con"];
        
        $sql ="INSERT INTO service_detail (bus_id,service_station_id,start_date,mileage_at_service,previous_bus_status,initiated_by) "
                . "VALUES ('$busId','$serviceStationId','$startDate','$currentMileage','$previousBusStatus','$userId')";
        
        $con->query($sql) or die ($con->error);
        $serviceId=$con->insert_id;
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
        
        $sql = "UPDATE service_detail SET service_status = '-1', cancelled_by = '$userId', cancelled_date='$cancelledDate'  WHERE service_id ='$serviceId'";
        
        $con->query($sql) or die ($con->error);
    }
    
    public function getServiceDetail($serviceId){
        
        $con = $GLOBALS["con"];
        
        $sql = "SELECT * FROM service_detail WHERE service_id='$serviceId'";
        
        $result = $con->query($sql) or die ($con->error);
        return $result;
    }
    
    public function completeService($serviceId,$completedDate,$cost,$invoice,$userId,$invoiceNumber){
        
        $con = $GLOBALS["con"];
        
        $sql = "UPDATE service_detail SET service_status ='2', completed_date='$completedDate', "
                . "cost='$cost', invoice='$invoice', completed_by='$userId', invoice_number='$invoiceNumber' WHERE service_id ='$serviceId'";
        
        $con->query($sql) or die ($con->error);
    }
    
    public function getPastServices(){
        
        $con = $GLOBALS["con"];
        
        $sql = "SELECT * FROM service_detail WHERE service_status!='1'";
        
        $result = $con->query($sql) or die ($con->error);
        return $result;
    }
    
    public function updatePastService($serviceId,$cost,$invoice,$invoiceNumber){
        
        $con = $GLOBALS["con"];
        
        $sql = "UPDATE service_detail SET cost='$cost', invoice='$invoice', invoice_number='$invoiceNumber' WHERE service_id ='$serviceId'";
        
        $con->query($sql) or die ($con->error);
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
                . "d.service_station_id = s.service_station_id AND d.bus_id = b.bus_id AND d.service_station_id = '$serviceStationId' AND d.service_status='2'";
        
        $result = $con->query($sql) or die ($con->error);
        return $result;
    }
    
    public function updatePaidService($serviceId,$paymentId){
        
        $con = $GLOBALS["con"];
        
        $sql = "UPDATE service_detail SET payment_id='$paymentId', service_status='3' WHERE service_id ='$serviceId'";
        
        $con->query($sql) or die ($con->error);
        
    }
    
    public function getMonthlyServiceCostTrend($startMonth, $endMonth){
        
        $con = $GLOBALS["con"];
        
        $sql = "SELECT DATE_FORMAT(completed_date,'%Y-%m') AS month, SUM(cost) AS total_cost "
                . "FROM service_detail "
                . "WHERE service_status = '3' AND DATE_FORMAT(completed_date, '%Y-%m') >= '" . $startMonth . "' AND DATE_FORMAT(completed_date, '%Y-%m') <= '" . $endMonth . "' "
                . "GROUP BY month ORDER BY month ASC";
        
        $result = $con->query($sql) or die ($con->error);
        return $result;
        
    }
}