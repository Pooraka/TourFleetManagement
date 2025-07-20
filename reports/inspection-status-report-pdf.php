<?php

if (empty($_GET["start_date"]) || empty($_GET["end_date"])) {
    
    exit("<b style='color:red'>Enter Start and End Dates </b>");
}

$startDate = new DateTime($_GET["start_date"]);
$endDate = new DateTime($_GET["end_date"]);

require_once '../commons/ReportPDF.php';
include_once '../model/inspection_model.php';
include_once '../model/bus_model.php';
include_once '../model/user_model.php';

$inspectionObj = new Inspection();
$inspectionResult = $inspectionObj->getAllInspections();

$busObj = new Bus();
$userObj = new User();

$pdf = new ReportPDF();
$pdf->AliasNbPages(); // Enable page numbers
$pdf->setReportTitle('Bus Inspection Status Report');

$pdf->AddPage("L", "A4"); // Add page after setting the title for Header() to pick it up

// Set initial content font and introductory text
$pdf->SetFont("Arial", "", 11);
$pdf->Cell(0, 10, 'Status of Inspections between '.$startDate->format("Y-m-d")." and ".$endDate->format("Y-m-d")." as follows,", 0, 1, 'L');
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
    
    if($inspectionRow["inspection_status"]==1){
        continue;
    }
    
    $inspectionDate = new DateTime($inspectionRow["inspection_date"]);
    
    if($inspectionDate<$startDate || $inspectionDate>$endDate){
        continue;
    }
    
    $busId = $inspectionRow["bus_id"];
    $busResult = $busObj->getBus($busId);
    $busRow = $busResult->fetch_assoc();
    $vehicleNo = $busRow["vehicle_no"];
    $make = $busRow["make"];
    $model = $busRow["model"];
    
    $status = match((int)$inspectionRow["inspection_status"]){
        
        1=>"Scheduled",
        2=>"Passed",
        3,4=>"Failed",
    };
    
    $inspectedBy = "N/A";
    $inspectionComments ="N/A";
    

    $userResult = $userObj->getUser($inspectionRow["inspected_by"]);
    $userRow = $userResult->fetch_assoc();
    $inspectedBy = $userRow["user_fname"]." ".$userRow["user_lname"];

    $inspectionComments = $inspectionRow["final_comments"];
        
  
    
    $pdf->Cell($colWidths[0], 6,$vehicleNo, 1, 0, 'L');
    $pdf->Cell($colWidths[1], 6,$make, 1, 0, 'L');
    $pdf->Cell($colWidths[2], 6,$model, 1, 0, 'L');
    $pdf->Cell($colWidths[3], 6,$inspectionDate->format("Y-m-d"), 1, 0, 'C');
    $pdf->Cell($colWidths[4], 6,$status, 1, 0, 'L');
    $pdf->Cell($colWidths[5], 6,$inspectedBy, 1, 0, 'L');
    $pdf->Cell($colWidths[6], 6,$inspectionComments, 1, 1, 'L');
    
}




$pdf->Output();