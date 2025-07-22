<?php

include_once '../commons/db_connection.php';

$dbCon= new DbConnection();

class Tour{
    
    public function checkIfInvoiceHasAnActiveTour($invoiceId){
        
        $con = $GLOBALS["con"];
        
        $sql = "SELECT * FROM tour WHERE invoice_id=? AND tour_status!='-1'";
    
        $stmt = $con->prepare($sql);

        $stmt->bind_param("i", $invoiceId);

        $stmt->execute();

        $result = $stmt->get_result();

        $stmt->close();

        return $result;
    }
      
    public function addTour($invoiceId,$startDate,$endDate,$destination){
        
        $con = $GLOBALS["con"];
        
        $sql = "INSERT INTO tour (invoice_id,start_date,end_date,destination) VALUES (?,?,?,?)";
    
        $stmt = $con->prepare($sql);

        $stmt->bind_param("isss", $invoiceId, $startDate, $endDate, $destination); 

        $stmt->execute();

        $tourId = $con->insert_id;

        $stmt->close();

        return $tourId;
    }
    
    public function addBusToTour($tourId,$busId){
        
        $con = $GLOBALS["con"];
        
        $sql = "INSERT INTO bus_tour (bus_id,tour_id) VALUES (?,?)";
    
        $stmt = $con->prepare($sql);

        $stmt->bind_param("ii", $busId, $tourId); 

        $stmt->execute();

        $stmt->close();     
        
    }
    
    public function getOngoingToursFiltered($dateFrom,$dateTo){
        
        $con = $GLOBALS["con"];
        
        $sql = "SELECT t.*, i.invoice_number, c.customer_fname, c.customer_lname, c.customer_email 
            FROM tour t 
            JOIN customer_invoice i ON t.invoice_id = i.invoice_id 
            JOIN customer c ON i.customer_id = c.customer_id 
            WHERE t.tour_status IN (1, 2) ";
        
        if($dateFrom!="" && $dateTo!=""){
            
            $sql .= "AND (t.start_date <= '$dateTo' AND t.end_date >= '$dateFrom') ";
        }
        
        $result = $con->query($sql) or die($con->error);
        return $result;
    }
    
    public function changeTourStatus($tourId,$status){
        
        $con = $GLOBALS["con"];

        $sql = "UPDATE tour SET tour_status=? WHERE tour_id=?";
    
        $stmt = $con->prepare($sql);

        $stmt->bind_param("ii", $status, $tourId); 

        $stmt->execute();

        $stmt->close();
    }
    
    
    public function getTour($tourId){
        
        $con = $GLOBALS["con"];
        
        $sql = "SELECT t.*, i.invoice_number, c.customer_fname, c.customer_lname, c.customer_email"
                . " FROM tour t, customer_invoice i, customer c WHERE t.invoice_id=i.invoice_id AND i.customer_id=c.customer_id AND tour_id=?";
        
        $stmt = $con->prepare($sql);
        
        $stmt->bind_param("i",$tourId);
        
        $stmt->execute();
        
        $result = $stmt->get_result();
        
        $stmt->close();
        
        return $result;
    }
    
    public function getBusListOfATour($tourId){
        
        $con = $GLOBALS["con"];
        
        $sql = "SELECT b.* FROM bus b, bus_tour bt WHERE b.bus_id = bt.bus_id AND bt.tour_id=?";
        
        $stmt = $con->prepare($sql);
        
        $stmt->bind_param("i",$tourId);
        
        $stmt->execute();
        
        $result = $stmt->get_result();
        
        $stmt->close();
        
        return $result;
        
    }
    
    public function getToursForTomorow(){
        
        $con = $GLOBALS["con"];
        
        $tomorrow = date("Y-m-d", strtotime("+1 day"));
        
        $sql = "SELECT tour_id FROM tour WHERE start_date = ?";
        
        $stmt = $con->prepare($sql);
        
        $stmt->bind_param("s", $tomorrow);

        $stmt->execute();
        
        $result = $stmt->get_result();
        return $result;
    }
    
    public function reAssignABusForATour($tourId,$oldBusId,$newBusId){
        
        $con = $GLOBALS["con"];
        
        $sql = "UPDATE bus_tour SET bus_id=? WHERE tour_id=? AND bus_id=?";
        
        $stmt = $con->prepare($sql);
        
        $stmt->bind_param("iii",$newBusId,$tourId,$oldBusId);
        
        $stmt->execute();
        
        $stmt->close();
    }
    
    public function getPastToursFiltered($dateFrom,$dateTo,$tourStatus){
        
        $con = $GLOBALS["con"];
        
        $sql = "SELECT t.*, i.invoice_number, c.customer_fname, c.customer_lname, c.customer_email 
            FROM tour t 
            JOIN customer_invoice i ON t.invoice_id = i.invoice_id 
            JOIN customer c ON i.customer_id = c.customer_id 
            WHERE 1 ";
        
        if($dateFrom!="" && $dateTo!=""){
            
            $sql .= "AND (t.start_date <= '$dateTo' AND t.end_date >= '$dateFrom') ";
        }
        
        if($tourStatus==""){
            
            $sql.="AND t.tour_status IN (-1,3) ";
        }elseif($tourStatus!=""){
            $sql.="AND t.tour_status='$tourStatus' ";
        }
        
        $sql.= "ORDER BY t.start_date DESC ";
        
        $result = $con->query($sql) or die($con->error);
        return $result;
    }

    public function getOngoingTourCount(){
        
        $con = $GLOBALS["con"];
        
        $sql = "SELECT COUNT(*) as count FROM tour WHERE start_date <= CURDATE() AND end_date >= CURDATE()";

        $result = $con->query($sql) or die($con->error);
        
        $row = $result->fetch_assoc();
        return $row['count'];
    }

    public function getUpComingToursCount(){
        
        $con = $GLOBALS["con"];

        $sevenDaysFromNow = date("Y-m-d", strtotime("+7 days"));

        $sql = "SELECT COUNT(*) as count FROM tour WHERE start_date > CURDATE() AND start_date <= '$sevenDaysFromNow' AND tour_status != -1";

        $result = $con->query($sql) or die($con->error);

        $row = $result->fetch_assoc();
        return $row['count'];
    }

    public function getTourCountStartingToday(){
        
        $con = $GLOBALS["con"];
        
        $today = date("Y-m-d");
        
        $sql = "SELECT COUNT(*) as count FROM tour WHERE start_date = '$today' AND tour_status != -1";

        $result = $con->query($sql) or die($con->error);

        $row = $result->fetch_assoc();
        return $row['count'];
    }
}