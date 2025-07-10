<?php

if (empty($_GET["start_date"]) || empty($_GET["end_date"])) {
    
    exit("<b style='color:red'>Enter Start and End Dates </b>");
}

$startDate = new DateTime($_GET["start_date"]);
$endDate = new DateTime($_GET["end_date"]);

require_once '../commons/ReportPDF.php';
include_once '../model/purchase_order_model.php';
include_once '../model/sparepart_model.php';
include_once '../model/bid_model.php';

$poObj = new PurchaseOrder();
$poResult = $poObj->getAllPOs();

$bidObj = new Bid();
$sparePartObj = new SparePart();

$pdf = new ReportPDF();
$pdf->AliasNbPages(); // Enable page numbers
$pdf->setReportTitle('Purchase Order Status Report');

$pdf->AddPage("L", "A4"); // Add page after setting the title for Header() to pick it up

// Set initial content font and introductory text
$pdf->SetFont("Arial", "", 11);
$pdf->Cell(0, 10, 'Purchases Orders Generated between '.$startDate->format("Y-m-d")." and ".$endDate->format("Y-m-d")." as follows,", 0, 1, 'L');
$pdf->Ln(5); // Small space before the table

$colWidths = [
    29,  // PO Number
    50,  // Part Name
    45,  // Supplier Name
    18,  // Qty Ordered
    18,  // Qty Received
    25,  // Unit Price
    30,  // Total Amount
    25,  // Order Date
    25   // PO Status
];


$headers = [
    'PO No.',
    'Part Name',
    'Supplier',
    'Qty Ord.',
    'Qty Rec.',
    'Unit Price',
    'Total Amount',
    'Order Date',
    'Status'
];


$pdf->SetFont('Arial', 'B', 9);
$pdf->SetFillColor(200, 220, 255); // Lighter blue background for headers
$pdf->SetTextColor(0); // Black text for headers
for ($i = 0; $i < count($headers); $i++) {
    $pdf->Cell($colWidths[$i], 7, $headers[$i], 1, 0, 'C', true);
}
$pdf->Ln();


$pdf->SetFont('Arial', '', 8);
$pdf->SetTextColor(0);


while($poRow = $poResult->fetch_assoc()){
    
    $poDate = new DateTime($poRow["order_date"]);
    
    if($poDate<$startDate || $poDate>$endDate){
        
        continue;
    }
    
    $poNumber = $poRow["po_number"];
    
    //Part Info
    $partId = $poRow["part_id"];
    $sparePartResult = $sparePartObj->getSparePart($partId);
    $sparePartRow = $sparePartResult->fetch_assoc();
    $partName = $sparePartRow["part_name"];
    
    //Get Supplier Info
    $bidId = $poRow["bid_id"];
    $bidResult = $bidObj->getBid($bidId);
    $bidRow = $bidResult->fetch_assoc();
    $supplierName = $bidRow["supplier_name"];
    
    $quantityOrdered = $poRow["quantity_ordered"];
    
    $quantityReceived = 0;
    
    if($poRow["quantity_received"]!=""){
        $quantityReceived = $poRow["quantity_received"];
    }
    
    $unitPrice = $poRow["po_unit_price"];
    
    $totalAmount = $poRow["total_amount"];
    
    $status = match((int)$poRow["po_status"]){
        
        -1=>"Rejected",
        1=>"Pending Approval",
        2,3=>"Approved",
        4=>"Paritally Received",
        5=>"Completed",
        6=>"Paid",
    };
    
    $pdf->Cell($colWidths[0], 6,$poNumber, 1, 0, 'L');
    $pdf->Cell($colWidths[1], 6,$partName, 1, 0, 'L');
    $pdf->Cell($colWidths[2], 6,$supplierName, 1, 0, 'L');
    $pdf->Cell($colWidths[3], 6, number_format($quantityOrdered), 1, 0, 'R');
    $pdf->Cell($colWidths[4], 6, number_format($quantityReceived), 1, 0, 'R');
    $pdf->Cell($colWidths[5], 6, number_format($unitPrice, 2), 1, 0, 'R');
    $pdf->Cell($colWidths[6], 6, number_format($totalAmount, 2), 1, 0, 'R');
    $pdf->Cell($colWidths[7], 6,$poDate->format("Y-m-d"), 1, 0, 'C');
    $pdf->Cell($colWidths[8], 6,$status, 1, 1, 'L');
    
}


$pdf->Output();