<?php

include_once '../commons/db_connection.php';

$dbCon= new DbConnection();

class CustomerInvoice{
    
    public function generateCustomerInvoice($invoiceNumber,$quotationId,$invoiceDate,$invoiceAmount,$customerId,$invoiceDescription,
            $tourStartDate,$tourEndDate,$pickup,$destination,$dropoff,$roundTripMileage,$advancePayment,$paidAmount){
        
        $con = $GLOBALS["con"];
        
        $sql = "INSERT INTO customer_invoice (invoice_number,quotation_id,invoice_date,invoice_amount,customer_id, "
            . "invoice_description,tour_start_date,tour_end_date,pickup_location,destination,dropoff_location,round_trip_mileage,advance_payment,paid_amount) "
            . "VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
        
        $stmt = $con->prepare($sql);
        
        $stmt->bind_param("sisdissssssidd", $invoiceNumber,$quotationId,$invoiceDate,$invoiceAmount,$customerId,$invoiceDescription,
            $tourStartDate,$tourEndDate,$pickup,$destination,$dropoff,$roundTripMileage,$advancePayment,$paidAmount);
        
        $stmt->execute();
    
        $invoiceId=$con->insert_id;

        $stmt->close();

        return $invoiceId;
    }
    
    public function addInvoiceItems($invoiceId,$categoryId,$quantity){
        
        $con = $GLOBALS["con"];
    
        $sql = "INSERT INTO customer_invoice_item (invoice_id,category_id,quantity) VALUES (?,?,?)";

        $stmt = $con->prepare($sql);

        $stmt->bind_param("iii", $invoiceId, $categoryId, $quantity);

        $stmt->execute();

        $stmt->close();
    }
    
    public function getPendingCustomerInvoicesFiltered($dateFrom,$dateTo){
        
        $con = $GLOBALS["con"];

        $sql = "SELECT i.invoice_id, i.invoice_number, i.quotation_id, i.invoice_date, i.invoice_amount, "
                . "i.customer_id, i.invoice_status, i.invoice_description, i.tour_start_date, i.tour_end_date, "
                . "i.pickup_location, i.destination, i.dropoff_location, i.round_trip_mileage, i.actual_fare, i.actual_mileage, "
                . "c.customer_fname, c.customer_lname FROM customer_invoice i, customer c "
                . "WHERE c.customer_id = i.customer_id AND i.invoice_status IN('1','2','3') ";
        
        if($dateFrom!="" && $dateTo!=""){
            $sql.="AND i.invoice_date BETWEEN '$dateFrom' AND '$dateTo' ";
        }
        
        $sql.="ORDER BY i.invoice_date DESC ";
        
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
                . "i.advance_payment, i.paid_amount, "
                . "c.customer_fname, c.customer_lname, c.customer_email FROM customer_invoice i, customer c "
                . "WHERE c.customer_id = i.customer_id AND i.invoice_id=?";
        
        $stmt = $con->prepare($sql);
        
        $stmt->bind_param("i",$invoiceId);
        
        $stmt->execute();
        
        $result = $stmt->get_result();
        
        $stmt->close();
        return $result;
        
    }
    
    public function getInvoiceItems($invoiceId){
        
        $con = $GLOBALS["con"];

        $sql = "SELECT i.item_id, i.invoice_id, i.category_id, i.quantity, c.category_name FROM "
                . "customer_invoice_item i, bus_category c WHERE i.category_id = c.category_id AND i.invoice_id =? ";
        
        $stmt = $con->prepare($sql);
        
        $stmt->bind_param("i",$invoiceId);
        
        $stmt->execute();
        
        $result = $stmt->get_result();
        
        $stmt->close();
        return $result;
    }
    
    public function changeInvoiceStatus($invoiceId,$status){
        
        $con = $GLOBALS["con"];
        
        $sql = "UPDATE customer_invoice SET invoice_status=? WHERE invoice_id=?";
        
        $stmt = $con->prepare($sql);
    
        $stmt->bind_param("ii", $status, $invoiceId);

        $stmt->execute();

        $stmt->close();
    }
    
    public function addActualTourMileage($invoiceId,$actualMileage){
        
        $con = $GLOBALS["con"];
        
        $sql = "UPDATE customer_invoice SET actual_mileage=? WHERE invoice_id=?";
        
        $stmt = $con->prepare($sql);
        
        $stmt->bind_param("ii",$actualMileage,$invoiceId);
        
        $stmt->execute();

        $stmt->close();
        
    }
    
    public function addActualFare($invoiceId,$actualFair){
        
        $con = $GLOBALS["con"];
        
        $sql = "UPDATE customer_invoice SET actual_fare=? WHERE invoice_id=?";
        
        $stmt = $con->prepare($sql);
        
        $stmt->bind_param("di",$actualFair,$invoiceId);
        
        $stmt->execute();

        $stmt->close();
    }
    

    public function getInvoicesWithFinalPayments(){
        
        $con = $GLOBALS["con"];
        
        $sql = "SELECT ci.*,c.*,ti.* FROM customer_invoice ci, customer c, tour_income ti WHERE ti.invoice_id=ci.invoice_id "
                . "AND ci.customer_id = c.customer_id AND ci.invoice_status='4' AND ti.payment_status!='-1' AND ti.tour_income_type='2'";
        
        $result = $con->query($sql) or die ($con->error);
        return $result;
    }

    
    public function getPaidInvoicesToVerify(){
        
        $con = $GLOBALS["con"];
        
        $sql = "SELECT ci.*, c.*, ti.* FROM customer_invoice ci, customer c, tour_income ti WHERE c.customer_id=ci.customer_id "
                . "AND ti.invoice_id=ci.invoice_id AND ti.tour_income_type=2 AND ci.invoice_status='4' AND ti.payment_status NOT IN (-1,2)";
        
        $result = $con->query($sql) or die ($con->error);
        return $result;
    }
    
    public function setCustomerInvoiceActualFareToNull($invoiceId){
        
        $con = $GLOBALS["con"];
        
        $sql = "UPDATE customer_invoice SET actual_fare=null WHERE invoice_id=?";
        
        $stmt = $con->prepare($sql);
        
        $stmt->bind_param("i",$invoiceId);
        
        $stmt->execute();
        
        $stmt->close();
    }
    
    public function getAllInvoices(){
        
        $con = $GLOBALS["con"];
        
        $sql = "SELECT * FROM customer_invoice";
        
        $result = $con->query($sql) or die ($con->error);
        return $result;
    }
    
    public function getRevenueByCustomers(){
        
        $con = $GLOBALS["con"];
        
        $sql = "SELECT c.customer_fname, c.customer_lname, c.customer_nic, c.customer_email, "
                . "SUM(i.paid_amount) AS total_amount, "
                . "COUNT(i.invoice_id) AS tours "
                . "FROM customer_invoice i JOIN customer c ON i.customer_id = c.customer_id WHERE i.invoice_status IN (-1,1,2,3,4) "
                . "GROUP BY c.customer_fname, c.customer_lname, c.customer_nic, c.customer_email ORDER BY total_amount DESC";
        
        $result = $con->query($sql) or die($con->error);
        return $result;
    }
    
    public function updateInvoiceAfterFinalPayment($paidAmount,$actualFare,$invoiceId){
        
        $con = $GLOBALS["con"];
        
        $sql = "UPDATE customer_invoice SET invoice_status=4, paid_amount=?, actual_fare=? WHERE invoice_id=?";
        
        $stmt = $con->prepare($sql);
        
        $stmt->bind_param("ddi",$paidAmount,$actualFare,$invoiceId);
        
        $stmt->execute();
        
        $stmt->close();
    }
    
    public function updateInvoiceAfterRefund($paidAmount,$invoiceId){
        
        $con = $GLOBALS["con"];
        
        $sql = "UPDATE customer_invoice SET paid_amount=? WHERE invoice_id=?";
        
        $stmt = $con->prepare($sql);
        
        $stmt->bind_param("di",$paidAmount,$invoiceId);
        
        $stmt->execute();
        
        $stmt->close();
    }
    
    public function getBookingHistory(){
        
        $con = $GLOBALS["con"];
        
        $sql = "SELECT ci.*,c.* FROM customer_invoice ci JOIN customer c ON c.customer_id = ci.customer_id "
                . "WHERE ci.invoice_status IN (-1,4)";
        
        $result = $con->query($sql) or die($con->error);
        
        return $result;
    }
    
    public function getBookingHistoryFiltered($invoiceDate,$invoiceStatus){
        
        $con = $GLOBALS["con"];
        
        $sql = "SELECT ci.*,c.* FROM customer_invoice ci JOIN customer c ON c.customer_id = ci.customer_id "
                . "WHERE 1=1 ";
        
        if($invoiceDate!=""){
            $sql.="AND ci.invoice_date='$invoiceDate' ";
        }
        
        if($invoiceStatus!=""){
            $sql.="AND ci.invoice_status='$invoiceStatus' ";
        }
        
        $result = $con->query($sql) or die($con->error);
        
        return $result;   
    }
    
    public function getAllInvoicesFiltered($dateFrom,$dateTo,$status){
        
        $con = $GLOBALS["con"];
        
        $sql = "SELECT * FROM customer_invoice WHERE 1 ";
        
        if($dateFrom !="" && $dateTo!=""){
            $sql.="AND invoice_date BETWEEN '$dateFrom' AND '$dateTo' ";
        }
        
        if($status==1){
            $sql.="AND invoice_status IN (1,2,3) ";
        }elseif($status==4){
            $sql.="AND invoice_status='4' ";
        }elseif($status==-1){
            $sql.="AND invoice_status='-1' ";
        }
        
        
        $result = $con->query($sql) or die ($con->error);
        return $result;
    }

    public function getPendingInvoiceCount(){
        
        $con = $GLOBALS["con"];
        
        $sql = "SELECT COUNT(*) AS pending_invoice_count FROM customer_invoice WHERE invoice_status IN (1,2,3)";
        
        $result = $con->query($sql) or die ($con->error);
        
        $row = $result->fetch_assoc();
        
        return $row['pending_invoice_count'];
    }

    public function getAdvancePaymentAmountReceivedWithinLast7Days(){

        $con = $GLOBALS["con"];
        
        $today = date('Y-m-d');
        $dateFrom = date('Y-m-d', strtotime($today . ' -7 days'));

        $sql= "SELECT SUM(paid_amount) AS total_advance_payment FROM tour_income 
                WHERE tour_income_type=1 AND payment_status !=-1 AND payment_date BETWEEN '$dateFrom' AND '$today'";

        $result = $con->query($sql) or die ($con->error);

        $row = $result->fetch_assoc();

        return $row['total_advance_payment'];
    }

    public function getRefundAmountsWithinLast7Days(){

        $con = $GLOBALS["con"];
        
        $today = date('Y-m-d');
        $dateFrom = date('Y-m-d', strtotime($today . ' -7 days'));

        $sql= "SELECT SUM(ABS(paid_amount)) AS total_refund_amount FROM tour_income 
                WHERE tour_income_type=3 AND payment_status !=-1 AND payment_date BETWEEN '$dateFrom' AND '$today'";

        $result = $con->query($sql) or die ($con->error);

        $row = $result->fetch_assoc();

        return $row['total_refund_amount'];
    }
}