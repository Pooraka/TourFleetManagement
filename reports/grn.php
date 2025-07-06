<?php

require_once '../commons/ReportPDF.php';
include_once '../model/purchase_order_model.php';
include_once '../model/bid_model.php';
include_once '../model/user_model.php';
include_once '../model/grn_model.php';


if(!isset($_GET['grn_id'])){?>
    <script>
        window.location="../errorpages/403.php";
    </script>
<?php }

$grnId = base64_decode($_GET['grn_id']);

$grnObj = new GRN();
$grnResult = $grnObj->getGRN($grnId);
$grnRow = $grnResult->fetch_assoc();

$poNumber = $grnRow['po_number'];

$poObj = new PurchaseOrder();
$poId = $grnRow['po_id'];
$poResult = $poObj->getPO($poId);
$poRow = $poResult->fetch_assoc();

$bidObj = new Bid();
$bidId = $grnRow['bid_id'];
$bidResult = $bidObj->getBid($bidId);
$bidRow = $bidResult->fetch_assoc();

$inspectedBy = $grnRow['inspected_by'];
$userObj = new User();
$userResult = $userObj->getUser($inspectedBy);
$userRow = $userResult->fetch_assoc();

//PDF Creation
$pdf = new ReportPDF();
$pdf->AliasNbPages(); // Enable page numbers
$pdf->setReportTitle('Goods Received Notice');

$pdf->AddPage("P", "A4"); // Add page after setting the title for Header() to pick it up

//Get the current Y position to align both columns to the top
$topY = $pdf->GetY();

//GRN Number
$pdf->SetFont("Arial", "B", 10);
$pdf->Cell(30,6,'GRN #:',0,0,'');
$pdf->SetFont("Arial", "", 10);
$pdf->Cell(45, 6,$grnRow['grn_number'],0,1,'R');

//Date Of Issue
$pdf->SetFont("Arial", "B", 10);
$pdf->Cell(30,6,'Issued Date:',0,0,'');
$pdf->SetFont("Arial", "", 10);
$pdf->Cell(45, 6,$grnRow['grn_received_date'],0,1,'R');

//PO Reference
$pdf->SetFont("Arial", "B", 10);
$pdf->Cell(30,6,'PO #:',0,0,'');
$pdf->SetFont("Arial", "", 10);
$pdf->Cell(45, 6,$poNumber,0,1,'R');

//PO Date
$pdf->SetFont("Arial", "B", 10);
$pdf->Cell(30,6,'PO Issued Date:',0,0,'');
$pdf->SetFont("Arial", "", 10);
$pdf->Cell(45, 6,$grnRow['order_date'],0,1,'R');

//Supplier Invoice No
$pdf->SetFont("Arial", "B", 10);
$pdf->Cell(30,6,'Supplier Inv No:',0,0,'');
$pdf->SetFont("Arial", "", 10);
$pdf->Cell(45, 6,$grnRow['supplier_invoice_number'],0,1,'R');
$y = (int)5+$pdf->GetY();

//Start with right column
$pdf->SetXY(140,$topY);

//Supplier Details
$pdf->SetFont("Arial", "B", 10);
$pdf->Cell(60,6,'Supplier Details:',0,2,'R');
$pdf->SetFont("Arial", "", 10);
$pdf->MultiCell(60, 6, $bidRow['supplier_name'], 0,'R','');
$pdf->setX(140);
$pdf->MultiCell(60, 6, $bidRow['supplier_email'], 0,'R','');
$pdf->setX(140);
$pdf->MultiCell(60, 6,$bidRow['supplier_contact'], 0,'R','');

$pdf->Ln(10);

//provide start x, start y, end x, end y 
$pdf->Line(10, $y, 200, $y);

//go down by 2 ys
$pdf->Ln(5);
$y = $pdf->GetY();
//Item Details Title
$pdf->SetY($y);
$pdf->SetFont("Arial", "B", 12);
$pdf->Cell(60,6,'Item Details',0,1,'');
$pdf->Ln(2);

//Item Details
$x = $pdf->GetX();

$pdf->SetFont('Arial', 'B', 8);
$pdf->SetFillColor(200, 220, 255); // Lighter blue background for headers
$pdf->SetTextColor(0,0,0); // Black text for headers

$pdf->Cell(65, 7,"Part Name", 1, 0, 'C', true);
$pdf->Cell(25, 7,"Quantity Ordered", 1, 0, 'C', true);
$pdf->Cell(25, 7,"Delivered Before", 1, 0, 'C', true);
$pdf->Cell(25, 7,"Quantity Received", 1, 0, 'C', true);
$pdf->Cell(25, 7,"Yet To Receive", 1, 0, 'C', true);
$pdf->Cell(25, 7,"Unit Price (LKR)", 1, 0, 'C', true);
$pdf->Ln(null);
$y = $pdf->GetY();
$pdf->SetXY($x, $y);

//Table data
$pdf->SetFont('Arial', '', 8);
$pdf->SetFillColor(255, 255, 255);
$pdf->SetTextColor(0);

// Store the starting X and Y position
$startX = $pdf->GetX();
$startY = $pdf->GetY();

$pdf->MultiCell(65, 7, $poRow['part_name'], 1, 'C');
$pdf->SetXY($startX + 65, $startY);

$pdf->MultiCell(25, 7, $poRow['quantity_ordered'], 1, 'C'); 
$pdf->SetXY($startX + 90, $startY); 

$deliveredBefore = $poRow['quantity_ordered']-$grnRow['grn_quantity_received']-$grnRow['yet_to_receive'];

$pdf->MultiCell(25, 7, $deliveredBefore, 1, 'C'); 
$pdf->SetXY($startX + 115, $startY); 

$pdf->MultiCell(25, 7, $grnRow['grn_quantity_received'], 1, 'C');
$pdf->SetXY($startX + 140, $startY);


$pdf->MultiCell(25, 7, $grnRow['yet_to_receive'], 1, 'C');
$pdf->SetXY($startX + 165, $startY);


$pdf->MultiCell(25, 7,number_format($poRow['po_unit_price'],2), 1, 'R');

$pdf->Ln(10);

//Acknowledgement Title
$pdf->SetFont("Arial", "B", 12);
$pdf->Cell(60,6,'Acknowledgement',0,1,'');
$pdf->Ln(2);

$pdf->SetFont("Arial", "B", 10);
$pdf->Cell(45,6,'Received & Inspected By:',0,0,'');
$pdf->SetFont("Arial", "", 10);
$pdf->Cell(40, 6,$userRow['user_fname']." ".$userRow['user_lname'],0,1,'L');

$pdf->Ln(5);
$pdf->SetFont("Arial", "", 10);

$yetToReceive = (int)$grnRow['yet_to_receive'];
$quantityReceived = (int)$grnRow['grn_quantity_received'];
$partName = $poRow['part_name'];
$totalReceived = $deliveredBefore+$quantityReceived;

if($yetToReceive==0){
    
    $pdf->MultiCell(190,5,"I hereby acknowledge the receipt of all items in full for Purchase Order #: ".$poNumber,0,"L",false);
    
}elseif($yetToReceive>0){
    
    $pdf->MultiCell(190,5,"I hereby acknowledge receipt of $quantityReceived units in this delivery, "
            . "bringing the total received quantity to $totalReceived. A remaining balance of $yetToReceive units for Purchase Order #ST-PO-9AF1-3 is yet to be delivered.",0,"L",false);
    
}

$pdf->Ln(15);
$pdf->SetX(10);

$pdf->SetFont("Arial", "B", 12);
$pdf->Cell(190, 8,'This document serves as official confirmation of the goods received on the date specified.',0,1,'C');




$pdf->Output();