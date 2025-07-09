<?php

if (empty($_GET["start_date"]) || empty($_GET["end_date"])) {
    
    exit("<b style='color:red'>Enter Start and End Dates </b>");
}

$startDate = new DateTime($_GET["start_date"]);
$startDate->setTime(0,0,0);
$endDate = new DateTime($_GET["end_date"]);
$endDate->setTime(23,59,59);

require_once '../commons/ReportPDF.php';
include_once '../model/sparepart_model.php';
include_once '../model/bus_model.php';
include_once '../model/user_model.php';


$sparePartObj = new SparePart();
$transactionResult = $sparePartObj->getAllTransactions();

$busObj = new Bus();
$userObj = new User();

$pdf = new ReportPDF();
$pdf->AliasNbPages(); // Enable page numbers
$pdf->setReportTitle('Spare Part Transaction History Report');

$pdf->AddPage("L", "A4"); // Add page after setting the title for Header() to pick it up

// Set initial content font and introductory text
$pdf->SetFont("Arial", "", 11);
$pdf->Cell(0, 10, 'Spare Part Transactions performed between '.$startDate->format("Y-m-d")." and ".$endDate->format("Y-m-d")." as follows,", 0, 1, 'L');
$pdf->Ln(5); // Small space before the table

$colWidths = [
    20,  // Transaction ID
    40,  // Part Number
    55,  // Part Name
    35,  // Transaction Type
    20,  // Quantity
    30,  // Bus (if applicable)
    30,  // Transacted By
    30   // Transaction Timestamp
];

// Define table headers
$headers = [
    'Trans. ID',
    'Part No.',
    'Part Name',
    'Type',
    'Qty',
    'Bus No.',
    'Transacted By',
    'Timestamp'
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

while($transactionRow = $transactionResult->fetch_assoc()){
    
    $timestamp = new DateTime($transactionRow["transacted_at"]);
    
    if($timestamp<$startDate || $timestamp>$endDate){
        continue;
    }
    
    $transactionId = $transactionRow["transaction_id"];
    $partNumber = $transactionRow["part_number"];
    $partName = $transactionRow["part_name"];
    
    $transactionType = match((int)$transactionRow["transaction_type"]){
        
        1=>"Initial Load",
        2=>"Purchase",
        3=>"Issued To Bus",
        4=>"Removed",
    };
    
    $quantity = $transactionRow["quantity"];
    
    $vehicleNo = "N/A";
    
    if($transactionRow["transaction_type"]==3){
        
        $busId = $transactionRow["bus_id"];
        $busResult = $busObj->getBus($busId);
        $busRow = $busResult->fetch_assoc();
        
        $vehicleNo = $busRow["vehicle_no"];
    }
    
    $userId = $transactionRow["transacted_by"];
    $userResult = $userObj->getUser($userId);
    $userRow = $userResult->fetch_assoc();
    $transactedBy = substr($userRow["user_fname"],0,1).". ".$userRow["user_lname"];
    
    
    $pdf->Cell($colWidths[0], 6,$transactionId, 1, 0, 'C');
    $pdf->Cell($colWidths[1], 6,$partNumber, 1, 0, 'L');
    $pdf->Cell($colWidths[2], 6,$partName, 1, 0, 'L');
    $pdf->Cell($colWidths[3], 6,$transactionType, 1, 0, 'L');
    $pdf->Cell($colWidths[4], 6,number_format($quantity), 1, 0, 'R');
    $pdf->Cell($colWidths[5], 6,$vehicleNo, 1, 0, 'C'); // Bus (if applicable)
    $pdf->Cell($colWidths[6], 6,$transactedBy, 1, 0, 'L'); // Transacted By
    $pdf->Cell($colWidths[7], 6,$timestamp->format("Y-m-d H:i"), 1, 1, 'C');
}

$pdf->Output();