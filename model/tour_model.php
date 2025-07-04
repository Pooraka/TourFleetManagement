<?php

include_once '../commons/db_connection.php';

$dbCon= new DbConnection();

class Tour{
    
    public function checkIfInvoiceHasAnActiveTour($invoiceId){
        
        $con = $GLOBALS["con"];
        
        $sql = "SELECT * FROM tour WHERE invoice_id='$invoiceId' AND tour_status!='-1'";
        
        $result = $con->query($sql) or die($con->error);  
        return $result;
    }
      
    public function addTour($invoiceId,$startDate,$endDate,$destination){
        
        $con = $GLOBALS["con"];
        
        $sql = "INSERT INTO tour (invoice_id,start_date,end_date,destination) VALUES "
                . "('$invoiceId','$startDate','$endDate','$destination')";
        
        $con->query($sql) or die($con->error);
        
        $tourId = $con->insert_id;
        return $tourId;
    }
    
    public function addBusToTour($tourId,$busId){
        
        $con = $GLOBALS["con"];
        
        $sql = "INSERT INTO bus_tour (bus_id,tour_id) VALUES ('$busId','$tourId')";
        
        $con->query($sql) or die($con->error);      
        
    }
    
    public function getOngoingTours(){
        
        $con = $GLOBALS["con"];
        
        $sql = "SELECT t.*, i.invoice_number, c.customer_fname, c.customer_lname, c.customer_email"
                . " FROM tour t, customer_invoice i, customer c WHERE t.invoice_id=i.invoice_id AND i.customer_id=c.customer_id AND tour_status IN(1,2)";
        
        $result = $con->query($sql) or die($con->error);
        return $result;
    }
    
    public function changeTourStatus($tourId,$status){
        
        $con = $GLOBALS["con"];
        
        $sql = "UPDATE tour SET tour_status='$status' WHERE tour_id='$tourId'";
        
        $con->query($sql) or die($con->error);
    }
    
    
    public function getTour($tourId){
        
        $con = $GLOBALS["con"];
        
        $sql = "SELECT t.*, i.invoice_number, c.customer_fname, c.customer_lname, c.customer_email"
                . " FROM tour t, customer_invoice i, customer c WHERE t.invoice_id=i.invoice_id AND i.customer_id=c.customer_id AND tour_id='$tourId'";
        
        $result = $con->query($sql) or die($con->error);
        return $result;
    }
    
    public function getBusListOfATour($tourId){
        
        $con = $GLOBALS["con"];
        
        $sql = "SELECT b.* FROM bus b, bus_tour bt WHERE b.bus_id = bt.bus_id AND bt.tour_id='$tourId'";
        
        $result = $con->query($sql) or die($con->error);
        return $result;
        
    }
}