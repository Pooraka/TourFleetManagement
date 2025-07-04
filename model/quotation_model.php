<?php

include_once '../commons/db_connection.php';

$dbCon= new DbConnection();

class Quotation{
    
    public function generateQuotation($customerId, $startDate, $endDate, $pickup, $destination, 
            $dropoff, $description, $roundTripMileage, $amount){
        
        $issuedDate = date('Y-m-d');
        
        $con = $GLOBALS["con"];
        
        $sql = "INSERT INTO quotation (issued_date, customer_id, tour_start_date, tour_end_date, pickup_location, "
                . "destination, dropoff_location, description, round_trip_mileage, total_amount) VALUES (?,?,?,?,?,?,?,?,?,?)";
        
        $type ="sissssssid";
        
        $stmt = $con->prepare($sql);
        
        $stmt->bind_param($type,$issuedDate,$customerId,$startDate,$endDate,$pickup,
                $destination,$dropoff,$description,$roundTripMileage,$amount);
        
        if($stmt->execute()){
            return $con->insert_id;
        }else{
            return false;
        }
        
    }
    
    public function addQuotationItems($quotationId, $categoryId,$quantity){
        
        $con = $GLOBALS["con"];
        
        $sql = "INSERT INTO quotation_item (quotation_id, category_id, quantity) VALUES (?,?,?)";
        
        $stmt = $con->prepare($sql);
        
        $stmt->bind_param("iii",$quotationId,$categoryId,$quantity);
        
        $stmt->execute();
    }
    
    public function getPendingQuotations(){
        
        $con = $GLOBALS["con"];

        $sql = "SELECT q.quotation_id, q.issued_date, q.customer_id, q.tour_start_date, q.tour_end_date, "
                . "q.pickup_location, q.dropoff_location, q.description, q.total_amount, q.quotation_status, "
                . "c.customer_fname, c.customer_lname FROM quotation q, customer c "
                . "WHERE q.customer_id = c.customer_id AND q.quotation_status='1'";
        
        $result = $con->query($sql) or die ($con->error);
        return $result;
    }
    
    public function getQuotation($quotationId){
        
        $con = $GLOBALS["con"];
        
        $sql = "SELECT q.issued_date, q.customer_id, q.tour_start_date, q.tour_end_date, "
                . "q.pickup_location, q.destination, q.dropoff_location, q.description, q.round_trip_mileage, "
                . "q.total_amount, c.customer_fname, c.customer_lname, c.customer_email, "
                . "CASE "
                . " WHEN q.quotation_status = 1 THEN 'Pending Customer Approval'"
                . "END AS status "
                . "FROM quotation q, customer c WHERE q.customer_id = c.customer_id AND q.quotation_status='1' AND q.quotation_id =?";
        
        $stmt = $con->prepare($sql);
        
        $stmt->bind_param("i",$quotationId);
        
        $stmt->execute();
        
        $result = $stmt->get_result();
        return $result;
    }
    
    public function getQuotationItems($quotationId){
        
        $con = $GLOBALS["con"];
        
        $sql = "SELECT  q.item_id, q.quotation_id, q.category_id, q.quantity, c.category_name "
                . "FROM quotation_item q, bus_category c WHERE q.category_id = c.category_id AND q.quotation_id=?" ;
        
        $stmt = $con->prepare($sql);
        
        $stmt->bind_param("i",$quotationId);
        
        $stmt->execute();
        
        $result = $stmt->get_result();
        return $result;
    }
    
    public function changeQuotationStatus($quotationId,$status){
        
        $con = $GLOBALS["con"];
        
        $sql = "UPDATE quotation SET quotation_status='$status' WHERE quotation_id='$quotationId'";
        
        $con->query($sql) or die ($con->error);
    }
}