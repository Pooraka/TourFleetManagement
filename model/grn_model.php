<?php

include_once '../commons/db_connection.php';

$dbCon= new DbConnection();

class GRN{
    
    public function createGRN($grnNumber,$poId,$quantityReceived,$inspectedBy,$grnNotes,$yetToReceive){
        
        $con = $GLOBALS["con"];
        
        $sql = "INSERT INTO grn (grn_number,po_id,grn_quantity_received,inspected_by,grn_notes,yet_to_receive) "
                . "VALUES(?,?,?,?,?,?)";
        
        $stmt = $con->prepare($sql);
        
        $stmt->bind_param("siiisi",$grnNumber,$poId,$quantityReceived,$inspectedBy,$grnNotes,$yetToReceive);
        
        $stmt->execute();
        
        $grnId = $con->insert_id;
        
        $stmt->close();
        return $grnId;
    }
    
    public function getGRNs(){
        
        $con = $GLOBALS["con"];
        
        $sql = "SELECT g.grn_id,g.grn_number,g.grn_quantity_received,g.yet_to_receive,g.grn_received_date,g.grn_notes,g.grn_status,g.inspected_by, "
                . "p.po_id,p.po_number,p.bid_id,p.part_id,p.quantity_ordered,p.po_unit_price,p.total_amount,p.order_date,p.created_by,p.approved_by,p.supplier_invoice_number "
                . "FROM grn g, purchase_order p WHERE g.po_id=p.po_id";
        
        $result = $con->query($sql) or die($con->error);
        return $result;
    }
    
    public function getGRN($grnId){
        
        $con = $GLOBALS["con"];
        
        $sql = "SELECT g.grn_id,g.grn_number,g.grn_quantity_received,g.yet_to_receive,g.grn_received_date,g.grn_notes,g.grn_status,g.inspected_by, "
                . "p.po_id,p.po_number,p.bid_id,p.part_id,p.quantity_ordered,p.po_unit_price,p.total_amount,p.order_date,p.created_by,p.approved_by,p.supplier_invoice_number "
                . "FROM grn g, purchase_order p WHERE g.po_id=p.po_id AND g.grn_id=?";
        
        $stmt = $con->prepare($sql);
        
        $stmt->bind_param("i",$grnId);
        
        $stmt->execute();
        
        $result = $stmt->get_result();
        
        $stmt->close();
        return $result;
    }

    public function getAllGRNsFiltered($dateFrom,$dateTo,$supplierId,$partId){

        $con = $GLOBALS["con"];

        $sql = "SELECT g.*,p.*,s.supplier_name,sp.part_name FROM grn g JOIN purchase_order p ON g.po_id=p.po_id "
                . "JOIN bid b ON p.bid_id=b.bid_id JOIN supplier s ON b.supplier_id=s.supplier_id "
                ."JOIN spare_part sp ON p.part_id=sp.part_id WHERE 1 ";

        if($dateFrom!="" && $dateTo!=""){
            $sql .= "AND g.grn_received_date BETWEEN '$dateFrom' AND '$dateTo' ";
        }

        if($supplierId!=""){
            $sql .= "AND s.supplier_id='$supplierId' ";
        }

        if($partId!=""){
            $sql .= "AND p.part_id='$partId' ";
        }

        $sql .= "ORDER BY g.grn_received_date DESC";

        $result = $con->query($sql) or die($con->error);
        return $result;
    }
}