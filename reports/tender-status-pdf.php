<?php

if (empty($_GET["start_date"]) || empty($_GET["end_date"])) {
    
    exit("<b style='color:red'>Enter Start and End Dates </b>");
}

$startDate = new DateTime($_GET["start_date"]);
$endDate = new DateTime($_GET["end_date"]);

require_once '../commons/ReportPDF.php';
include_once '../model/tender_model.php';
include_once '../model/bid_model.php';
include_once '../model/sparepart_model.php';



$tenderObj = new Tender();
$tenderResult = $tenderObj->getAllTenders();

$bidObj = new Bid();
$sparePartObj = new SparePart();

$pdf = new ReportPDF();
$pdf->AliasNbPages(); // Enable page numbers
$pdf->setReportTitle('Tender Status Report');

$pdf->AddPage("L", "A4"); // Add page after setting the title for Header() to pick it up

// Set initial content font and introductory text
$pdf->SetFont("Arial", "", 11);
$pdf->Cell(0, 10, 'Status of the tenders opened between '.$startDate->format("Y-m-d")." and ".$endDate->format("Y-m-d")." as follows,", 0, 1, 'L');
$pdf->Ln(5); // Small space before the table

$colWidths = [
    20,  // Tender ID
    45,  // Part Name 
    20,  // Quantity Required
    35,  // Open Date
    35,  // Close Date 
    20,  //Status
    55,  // Awarded Supplier
    40   // Awarded Unit Price
];


$headers = [
    'Tender ID',
    'Part Name',
    'Qty Req.', 
    'Open Date',
    'Close Date',
    'Status', 
    'Awarded Supplier',
    'Awarded Unit Price'
];

$pdf->SetFont('Arial', 'B', 9);
$pdf->SetFillColor(200, 220, 255); // Lighter blue background for headers
$pdf->SetTextColor(0); // Black text for headers
for ($i = 0; $i < count($headers); $i++) {
    $pdf->Cell($colWidths[$i], 7, $headers[$i], 1, 0, 'C', true);
}
$pdf->Ln();

$pdf->SetFont('Arial', '', 8);

while($tenderRow = $tenderResult->fetch_assoc()){
    
    $tenderOpenDate = new DateTime($tenderRow["open_date"]);
    
    if($tenderOpenDate<$startDate || $tenderOpenDate>$endDate){
        continue;
    }
    
    
    
    $partId = $tenderRow["part_id"];
    $sparePartResult = $sparePartObj->getSparePart($partId);
    $sparePartRow = $sparePartResult->fetch_assoc();
    $partName = $sparePartRow["part_name"];
    
    if(($tenderRow["awarded_bid"])!=""){
        
        $bidId = $tenderRow["awarded_bid"];
        $bidResult = $bidObj->getBid($bidId);
        $bidRow = $bidResult->fetch_assoc();

        $unitPrice = $bidRow["unit_price"];

        $supplierName = $bidRow["supplier_name"];
    }else{
        
        $supplierName="N/A";
        $unitPrice="0.00";
    }
    
    $status = match((int)$tenderRow["tender_status"]){
        
        -1=>"Cancelled",
        1=>"Open",
        2=>"Closed",
        3=>"Awarded",
    };
    
    
    $pdf->Cell($colWidths[0], 6,$tenderRow["tender_id"], 1, 0, 'C');
    $pdf->Cell($colWidths[1], 6,$partName, 1, 0, 'L');
    $pdf->Cell($colWidths[2], 6, number_format($tenderRow["quantity_required"]), 1, 0, 'R');
    $pdf->Cell($colWidths[3], 6,$tenderRow["open_date"], 1, 0, 'C'); 
    $pdf->Cell($colWidths[4], 6,$tenderRow["close_date"], 1, 0, 'C'); 
    $pdf->Cell($colWidths[5], 6,$status, 1, 0, 'L');
    $pdf->Cell($colWidths[6], 6,$supplierName, 1, 0, 'L');
    $pdf->Cell($colWidths[7], 6, "LKR ".number_format($unitPrice, 2), 1, 1, 'R');
}









$pdf->Output();