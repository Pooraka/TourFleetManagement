<?php

include_once '../commons/db_connection.php';

$dbCon= new DbConnection();

class CustomerInvoice{
    
    public function generateCustomerInvoice($invoiceNumber,$quotationId,$invoiceDate,$invoiceAmount,$customerId,$invoiceDescription,
            $tourStartDate,$tourEndDate,$pickup,$destination,$dropoff,$roundTripMileage){
        
        $con = $GLOBALS["con"];
        
        $sql = "INSERT INTO customer_invoice (invoice_number,quotation_id,invoice_date,invoice_amount,customer_id, "
                . "invoice_description,tour_start_date,tour_end_date,pickup_location,destination,dropoff_location,round_trip_mileage) "
                . "VALUES ('$invoiceNumber','$quotationId','$invoiceDate','$invoiceAmount','$customerId','$invoiceDescription',"
                . "'$tourStartDate','$tourEndDate','$pickup','$destination','$dropoff','$roundTripMileage')";
        
        $con->query($sql) or die ($con->error);
        $invoiceId=$con->insert_id;
        return $invoiceId;
    }
    
    public function addInvoiceItems($invoiceId,$categoryId,$quantity){
        
        $con = $GLOBALS["con"];
        
        $sql = "INSERT INTO customer_invoice_item (invoice_id,category_id,quantity) VALUES "
                . "('$invoiceId','$categoryId','$quantity')";
        
        $con->query($sql) or die ($con->error);
    }
    
    public function getPendingCustomerInvoices(){
        
        $con = $GLOBALS["con"];

        $sql = "SELECT i.invoice_id, i.invoice_number, i.quotation_id, i.invoice_date, i.invoice_amount, "
                . "i.customer_id, i.invoice_status, i.invoice_description, i.tour_start_date, i.tour_end_date, "
                . "i.pickup_location, i.destination, i.dropoff_location, i.round_trip_mileage, i.actual_fare, i.actual_mileage, "
                . "c.customer_fname, c.customer_lname FROM customer_invoice i, customer c "
                . "WHERE c.customer_id = i.customer_id AND i.invoice_status IN('1','2','3')";
        
        $result = $con->query($sql) or die ($con->error);
        return $result;
    }
    
    public function getInvoicesToAssignTours(){
        
        $con = $GLOBALS["con"];

        $sql = "SELECT i.invoice_id, i.invoice_number, i.quotation_id, i.invoice_date, i.invoice_amount, "
                . "i.customer_id, i.invoice_status, i.invoice_description, i.tour_start_date, i.tour_end_date, "
                . "i.pickup_location, i.destination, i.dropoff_location, i.round_trip_mileage, i.actual_fare, i.actual_mileage, "
                . "c.customer_fname, c.customer_lname FROM customer_invoice i, customer c "
                . "WHERE c.customer_id = i.customer_id AND i.invoice_status='1'";
        
        $result = $con->query($sql) or die ($con->error);
        return $result;
    }
    
    public function getInvoice($invoiceId){
        
        $con = $GLOBALS["con"];

        $sql = "SELECT i.invoice_id, i.invoice_number, i.quotation_id, i.invoice_date, i.invoice_amount, "
                . "i.customer_id, i.invoice_status, i.invoice_description, i.tour_start_date, i.tour_end_date, "
                . "i.pickup_location, i.destination, i.dropoff_location, i.round_trip_mileage, i.actual_fare, i.actual_mileage, "
                . "c.customer_fname, c.customer_lname, c.customer_email FROM customer_invoice i, customer c "
                . "WHERE c.customer_id = i.customer_id AND i.invoice_id='$invoiceId'";
        
        $result = $con->query($sql) or die ($con->error);
        return $result;
        
    }
    
    public function getInvoiceItems($invoiceId){
        
        $con = $GLOBALS["con"];

        $sql = "SELECT i.item_id, i.invoice_id, i.category_id, i.quantity, c.category_name FROM "
                . "customer_invoice_item i, bus_category c WHERE i.category_id = c.category_id AND i.invoice_id = '$invoiceId' ";
        
        $result = $con->query($sql) or die ($con->error);
        return $result;
    }
    
    public function changeInvoiceStatus($invoiceId,$status){
        
        $con = $GLOBALS["con"];
        
        $sql = "UPDATE customer_invoice SET invoice_status='$status' WHERE invoice_id='$invoiceId'";
        
        $con->query($sql) or die ($con->error);
    }
    
    public function addActualTourMileage($invoiceId,$actualMileage){
        
        $con = $GLOBALS["con"];
        
        $sql = "UPDATE customer_invoice SET actual_mileage='$actualMileage' WHERE invoice_id='$invoiceId'";
        
        $con->query($sql) or die ($con->error);
        
    }
    
    public function addActualFare($invoiceId,$actualFair){
        
        $con = $GLOBALS["con"];
        
        $sql = "UPDATE customer_invoice SET actual_fare='$actualFair' WHERE invoice_id='$invoiceId'";
        
        $con->query($sql) or die ($con->error);
        
    }
    

    public function getPaidInvoices(){
        
        $con = $GLOBALS["con"];
        
        $sql = "SELECT ci.*,c.*,ti.* FROM customer_invoice ci, customer c, tour_income ti WHERE ti.invoice_id=ci.invoice_id "
                . "AND ci.customer_id = c.customer_id AND ci.invoice_status='4' AND ti.payment_status!='-1'";
        
        $result = $con->query($sql) or die ($con->error);
        return $result;
    }

    
    public function getPaidInvoicesToVerify(){
        
        $con = $GLOBALS["con"];
        
        $sql = "SELECT ci.*, c.*, ti.* FROM customer_invoice ci, customer c, tour_income ti WHERE c.customer_id=ci.customer_id "
                . "AND ti.invoice_id=ci.invoice_id AND ci.invoice_status='4' AND ti.payment_status NOT IN (-1,2)";
        
        $result = $con->query($sql) or die ($con->error);
        return $result;
    }
    
    public function setCustomerInvoiceActualFareToNull($invoiceId){
        
        $con = $GLOBALS["con"];
        
        $sql = "UPDATE customer_invoice SET actual_fare=null WHERE invoice_id='$invoiceId'";
        
        $con->query($sql) or die ($con->error);
    }
}