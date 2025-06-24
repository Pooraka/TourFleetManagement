<?php

include_once '../commons/db_connection.php';

$dbCon= new DbConnection();

class CustomerInvoice{
    
    public function getPendingCustomerInvoices(){
        
        $con = $GLOBALS["con"];

        $sql = "SELECT i.invoice_id, i.invoice_number, i.quotation_id, i.invoice_date, i.invoice_amount, "
                . "i.customer_id, i.invoice_status, i.invoice_description, i.tour_start_date, i.tour_end_date, "
                . "i.pickup_location, i.dropoff_location, i.round_trip_mileage, i.actual_fare, c.customer_fname, "
                . "c.customer_lname FROM customer_invoice i, customer c "
                . "WHERE c.customer_id = i.customer_id AND i.invoice_status='1'";
        
        $result = $con->query($sql) or die ($con->error);
        return $result;
    }
    
    public function getInvoiceInformation($invoiceId){
        
        $con = $GLOBALS["con"];

        $sql = "SELECT i.invoice_id, i.invoice_number, i.quotation_id, i.invoice_date, i.invoice_amount, "
                . "i.customer_id, i.invoice_status, i.invoice_description, i.tour_start_date, i.tour_end_date, "
                . "i.pickup_location, i.dropoff_location, i.round_trip_mileage, i.actual_fare, i.actual_mileage, c.customer_fname, "
                . "c.customer_lname FROM customer_invoice i, customer c "
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
}