<?php

require_once '../commons/ReportPDF.php';
include_once '../model/purchase_order_model.php';
include_once '../model/bid_model.php';
include_once '../model/tender_model.php';
include_once '../model/supplier_model.php';


if(!isset($_GET['po_id'])){?>
    <script>
        window.location="../errorpages/403.php";
    </script>
<?php }

$poId = base64_decode($_GET['po_id']);

$poObj = new PurchaseOrder();
$poResult = $poObj->getPO($poId);
$poRow = $poResult->fetch_assoc();

$bidId = $poRow['bid_id'];
$bidObj = new Bid();
$bidResult = $bidObj->getBid($bidId);
$bidRow = $bidResult->fetch_assoc();

$tenderId = $bidRow['tender_id'];
$tenderObj = new Tender();
$tenderResult = $tenderObj->getTender($tenderId);
$tenderRow = $tenderResult->fetch_assoc();

$supplierId = $bidRow['supplier_id'];
$supplierObj = new Supplier();
$supplierResult = $supplierObj->getSupplier($supplierId);
$supplierRow = $supplierResult->fetch_assoc();


$pdf = new ReportPDF();
$pdf->AliasNbPages(); // Enable page numbers
$pdf->setReportTitle('Purchase Order');

$pdf->AddPage("P", "A4"); // Add page after setting the title for Header() to pick it up

//Get the current Y position to align both columns to the top
$topY = $pdf->GetY();

//PO Number
$pdf->SetFont("Arial", "B", 10);
$pdf->Cell(42,6,'Purchase Order #:',0,0,'');
$pdf->SetFont("Arial", "", 10);
$pdf->Cell(27, 6,$poRow['po_number'],0,1,'R');

//Date Of Issue
$pdf->SetFont("Arial", "B", 10);
$pdf->Cell(42,6,'Issued Date:',0,0,'');
$pdf->SetFont("Arial", "", 10);
$pdf->Cell(27, 6,$poRow['order_date'],0,1,'R');

//Tender Reference
$pdf->SetFont("Arial", "B", 10);
$pdf->Cell(42,6,'Tender #:',0,0,'');
$pdf->SetFont("Arial", "", 10);
$pdf->Cell(27, 6,$tenderId,0,1,'R');

//Start with right column
$pdf->SetXY(140,$topY);

//Issued To
$pdf->SetFont("Arial", "B", 10);
$pdf->Cell(60,6,'Issued To:',0,2,'R');
$pdf->SetFont("Arial", "", 10);
$pdf->MultiCell(60, 6, $supplierRow['supplier_name'], 0,'R','');
$pdf->setX(140);
$pdf->MultiCell(60, 6, $supplierRow['supplier_email'], 0,'R','');
$pdf->setX(140);
$pdf->MultiCell(60, 6,$supplierRow['supplier_contact'], 0,'R','');

$pdf->Ln(2);
$y = $pdf->GetY();

//provide start x, start y, end x, end y 
$pdf->Line(10, $y, 200, $y);

//go down by 2 xs
$pdf->Ln(2);

//Shipping Details Title
$y = $pdf->GetY();
$pdf->SetFont("Arial", "B", 12);
$pdf->Cell(60,6,'Ship To',0,1,'');
$pdf->Ln(2);

//Shipping Details
$pdf->SetFont("Arial", "", 10);
$pdf->Cell(25, 6,'Warehouse Controller',0,1,'');
$pdf->Cell(25, 6,'Skyline Tours',0,1,'');
$pdf->Cell(25, 6,'No. 145/2, Embulgama, Hanwella.',0,1,'');
$pdf->SetFont("Arial", "B", 10);
$pdf->Cell(25, 6,'Contact:',0,0,'');
$pdf->SetFont("Arial", "", 10);
$pdf->Cell(25, 6,'0362258479',0,1,'');
$yaftershippingdetails = $pdf->GetY();

//Delivery Instructions title
$pdf->SetXY(130, $y);
$pdf->SetFont("Arial", "B", 12);
$pdf->Cell(70,6,'Delivery Instructions',0,1,'R');
$pdf->Ln(2);

//Delivery Instructions
$pdf->SetX(120);
$pdf->SetFont("Arial", "", 10);
$pdf->Cell(0, 6,'1) Deliver between 9 AM and 5 PM.',0,1,'');
$pdf->SetX(120);
$pdf->Cell(0, 6,'2) Contact Warehouse Controller before delivery.',0,1,'');
$pdf->SetY($yaftershippingdetails);
$pdf->Ln(2);


//provide start x, start y, end x, end y 
$pdf->Line(10, $yaftershippingdetails, 200, $yaftershippingdetails);

//go down by 2 xs
$pdf->Ln(2);

//Item Details Title
$pdf->SetFont("Arial", "B", 12);
$pdf->Cell(60,6,'Item Details',0,1,'');
$pdf->Ln(2);

//Item Details
$x = $pdf->GetX();
$pdf->SetFont('Arial', 'B', 8);
$pdf->SetFillColor(200, 220, 255); // Lighter blue background for headers
$pdf->SetTextColor(0,0,0); // Black text for headers
$pdf->Cell(45, 7,"Part Number", 1, 0, 'C', true);
$pdf->Cell(75, 7,"Part Name", 1, 0, 'C', true);
$pdf->Cell(15, 7,"Quantity", 1, 0, 'C', true);
$pdf->Cell(25, 7,"Unit Price", 1, 0, 'C', true);
$pdf->Cell(30, 7,"Total (LKR)", 1, 1, 'C', true);
$pdf->SetX($x);

//Table data
$pdf->SetFont('Arial', '', 9);
$pdf->SetFillColor(255, 255, 255);
$pdf->SetTextColor(0);


// Store the starting X and Y position
$startX = $pdf->GetX();
$startY = $pdf->GetY();

$pdf->MultiCell(45, 7, $tenderRow['part_number'], 1, 'C');
$pdf->SetXY($startX + 45, $startY);

$pdf->MultiCell(75, 7, $tenderRow['part_name'], 1, 'C'); 
$pdf->SetXY($startX + 120, $startY); 


$pdf->MultiCell(15, 7, $poRow['quantity_ordered'], 1, 'C');
$pdf->SetXY($startX + 135, $startY);


$pdf->MultiCell(25, 7, "LKR ".number_format($poRow['po_unit_price'],2), 1, 'R');
$pdf->SetXY($startX + 160, $startY);


$pdf->MultiCell(30, 7, "LKR ".number_format($poRow['total_amount'],2), 1, 'R');

$pdf->Ln(5);
$y = $pdf->GetY();

//provide start x, start y, end x, end y 
$pdf->Line(10, $y, 200, $y);

//go down by 2 xs
$pdf->Ln(2);

//Terms & Conditions Title
$pdf->SetFont("Arial", "B", 12);
$pdf->Cell(60,6,'Terms & Conditions',0,1,'');
$pdf->Ln(2);

//Terms & Conditions
$pdf->SetX(10);
$pdf->SetFont("Arial", "", 10);
$pdf->Cell(190, 6,'1) Please include the Purchase Order number on all related invoices and shipping documents.',0,1,'');
$pdf->Cell(190, 6,'2) Notify us immediately if you are unable to ship as specified.',0,1,'');
$pdf->Cell(190, 6,'3) Please provide your invoice as soon as the purchase order is received.',0,1,'');
$pdf->Cell(190, 6,'4) Payments will be made once all goods are delivered.',0,1,'');
$pdf->Ln(5);

$pdf->SetFont("Arial", "B", 12);
$pdf->Cell(190, 8,'We look forward to your confirmation of this order.',0,1,'C');


$pdf->Output();