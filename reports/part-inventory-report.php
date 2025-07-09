<?php

require_once '../commons/ReportPDF.php';
include_once '../model/sparepart_model.php';

$sparePartObj = new SparePart();
$sparePartResult = $sparePartObj->getSpareParts();

$pdf = new ReportPDF();
$pdf->AliasNbPages(); // Enable page numbers
$pdf->setReportTitle('Spare Part Inventory Report');

$pdf->AddPage("P", "A4"); // Add page after setting the title for Header() to pick it up

// Set initial content font and introductory text
$pdf->SetFont("Arial", "", 11);
$pdf->Cell(0, 10, 'The list of spare parts & their stock levels as of ' . date("H:i:s, Y-m-d") . ' is as below:', 0, 1, 'L');
$pdf->Ln(5); // Small space before the table

// Define table column widths
$colWidths = [
    45,  // Part Number
    70,  // Part Name
    25,  // Quantity On Hand
    25,  // Reorder Level
    25   // Stock Status
];

// Define table headers
$headers = [
    'Part Number',
    'Part Name',
    'Qty On Hand',
    'Reorder Level',
    'Stock Status'
];

// Print table headers
$pdf->SetFont('Arial', 'B', 9);
$pdf->SetFillColor(200, 220, 255); // Lighter blue background for headers
$pdf->SetTextColor(0); // Black text for headers

for($i = 0; $i < count($headers); $i++){
    $pdf->Cell($colWidths[$i],7,$headers[$i],1,0,"C",true);
}

$pdf->Ln();

$pdf->SetFont('Arial', '', 9);
$pdf->SetTextColor(0);

while ($sparePartRow = $sparePartResult->fetch_assoc()){
    
    $quantityOnHand = (int)$sparePartRow["quantity_on_hand"];
    $reorderLevel = (int)$sparePartRow["reorder_level"];
    
    $status = "";
    
    if($quantityOnHand<=$reorderLevel){
        $status = "Reorder Now";
    }else{
        $status = "Sufficient Stock";
    }
    
    $pdf->Cell($colWidths[0],6,$sparePartRow["part_number"],1,0,'L');
    $pdf->Cell($colWidths[1],6,$sparePartRow["part_name"],1,0,'L');
    $pdf->Cell($colWidths[2],6,number_format($quantityOnHand),1,0,'R');
    $pdf->Cell($colWidths[3],6,number_format($reorderLevel),1,0,'R');
    $pdf->Cell($colWidths[4],6,$status,1,1,'L');
}


$pdf->Ln(10);

$pdf->SetFont('Arial', '', 10);
$pdf->SetTextColor(0); // Black text for data
$pdf->MultiCell(190,5,"This report highlights the spare parts quantities on hand and the parts that should be reordered",0,"L",false);














$pdf->Output();