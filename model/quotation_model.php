<?php

include_once '../commons/db_connection.php';

$dbCon= new DbConnection();

class Quotation{
    
    public function getPendingQuotations(){
        
        $con = $GLOBALS["con"];

        $sql = "SELECT q.quotation_id, q.issued_date, q.customer_id, q.tour_start_date, q.tour_end_date, "
                . "q.pickup_location, q.dropoff_location, q.description, q.total_amount, q.quotation_status, "
                . "c.customer_fname, c.customer_lname FROM quotation q, customer c "
                . "WHERE q.customer_id = c.customer_id AND q.quotation_status='1'";
        
        $result = $con->query($sql) or die ($con->error);
        return $result;
    }
}