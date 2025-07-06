<?php

include_once '../commons/db_connection.php';

$dbCon= new DbConnection();

class GRN{
    
    public function createGRN($grnNumber,$poId,$quantityReceived,$inspectedBy,$grnNotes,$yetToReceive){
        
        $con = $GLOBALS["con"];
        
        $sql = "INSERT INTO grn (grn_number,po_id,grn_quantity_received,inspected_by,grn_notes,yet_to_receive) "
                . "VALUES(?,?,?,?,?)";
        
        $stmt = $con->prepare($sql);
        
        $stmt->bind_param("siiisi",$grnNumber,$poId,$quantityReceived,$inspectedBy,$grnNotes,$yetToReceive);
        
        $stmt->execute();
        
        $grnId = $con->insert_id;
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
        return $result;
    }
}