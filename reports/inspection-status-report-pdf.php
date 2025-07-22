<?php

require_once '../commons/ReportPDF.php';
include_once '../model/inspection_model.php';
include_once '../model/user_model.php';

// Get the start and end dates from the request
$dateFrom = $_GET["dateFrom"];
$dateTo = $_GET["dateTo"];
$resultId = $_GET["resultId"];
$inspectedId = $_GET["inspectedId"];

$inspectionObj = new Inspection();
$inspectionResult = $inspectionObj->getAllInspectionsFiltered($dateFrom,$dateTo,$resultId,$inspectedId);

$userObj = new User();

$pdf = new ReportPDF();
$pdf->AliasNbPages(); // Enable page numbers
$pdf->setReportTitle('Bus Inspection Result Report');

$pdf->SetFont("Arial", "", 11);
// Set initial content font and introductory text

$pdf->AddPage("L", "A4"); // Add page after setting the title for Header() to pick it up

if($dateFrom != "" && $dateTo != ""){

$pdf->Cell(0, 10, 'Status of Inspections between '.$dateFrom." and ".$dateTo." as follows,", 0, 1, 'L');
}else{
    $pdf->Cell(0, 10, 'Status of Inspections as follows:', 0, 1, 'L');
}

if($resultId != ""){

    $result = match((int)$resultId){
        1=>"Passed",
        0=>"Failed",
    };

    $pdf->Cell(0, 10, 'Inspection Result: '.$result, 0, 1, 'L');
}

$pdf->Ln(5); // Small space before the table

$colWidths = [
    18,  // Vehicle Number
    32,  // Make
    30,  // Model
    18,  // Last Inspection Date
    15,  // Inspection Result
    22,  // Inspected By
    132   // Inspector Comments
];


$headers = [
    'Vehicle No.',
    'Make',
    'Model',
    'Insp. Date',
    'Result',
    'Inspected By',
    'Comments'
];

if($inspectionResult->num_rows == 0) {
    $pdf->SetFont('Arial', 'B', 10);
    $pdf->Cell(0, 10, 'No inspections found for the given criteria.', 0, 1, 'C');
    $pdf->Output();
    exit;
}

$pdf->SetFont('Arial', 'B', 9);
$pdf->SetFillColor(200, 220, 255); // Lighter blue background for headers
$pdf->SetTextColor(0); // Black text for headers
for ($i = 0; $i < count($headers); $i++) {
    $pdf->Cell($colWidths[$i], 7, $headers[$i], 1, 0, 'C', true);
}
$pdf->Ln();

$pdf->SetFont('Arial', '', 8); 
$pdf->SetTextColor(0);

while($inspectionRow = $inspectionResult->fetch_assoc()){
    

    $vehicleNo = $inspectionRow["vehicle_no"];
    $make = $inspectionRow["make"];
    $model = $inspectionRow["model"];
    $inspectionDate = $inspectionRow["inspection_date"];

    $result = match((int)$inspectionRow["inspection_result"]){
        1=>"Passed",
        0=>"Failed",
        default=>"N/A"
    };
    

    $userResult = $userObj->getUser($inspectionRow["inspected_by"]);
    $userRow = $userResult->fetch_assoc();
    $inspectedBy = $userRow["user_fname"]." ".$userRow["user_lname"];

    $inspectionComments = $inspectionRow["final_comments"];
        
  
    
    $pdf->Cell($colWidths[0], 6,$vehicleNo, 1, 0, 'L');
    $pdf->Cell($colWidths[1], 6,$make, 1, 0, 'L');
    $pdf->Cell($colWidths[2], 6,$model, 1, 0, 'L');
    $pdf->Cell($colWidths[3], 6,$inspectionDate, 1, 0, 'C');
    $pdf->Cell($colWidths[4], 6,$result, 1, 0, 'L');
    $pdf->Cell($colWidths[5], 6,$inspectedBy, 1, 0, 'L');
    $pdf->Cell($colWidths[6], 6,$inspectionComments, 1, 1, 'L');
    
}




$pdf->Output();