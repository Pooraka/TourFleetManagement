<?php

require_once '../commons/ReportPDF.php';
include_once '../model/bus_model.php';


$busObj = new Bus();

$busResult = $busObj->getAllBusesToService();

$pdf = new ReportPDF();
$pdf->AliasNbPages(); // Enable page numbers
$pdf->setReportTitle('Upcoming Bus Services Report');

$pdf->AddPage("P", "A4"); // Add page after setting the title for Header() to pick it up

// Set initial content font and introductory text
$pdf->SetFont("Arial", "", 11);
$pdf->Cell(0, 10, 'The list of upcoming bus services as of ' . date("H:i:s, Y-m-d") . ' is as below:', 0, 1, 'L');
$pdf->Ln(5); // Small space before the table

// Table Headers and Widths
$colWidths = [
    30, // Vehicle No.
    34, // Make
    30, // Model
    21, // Current KM
    22, // Next Svc. KM
    24, // Next Svc. Date
    29  // Status
];

$headers = [
    'Vehicle No.',
    'Make',
    'Model',
    'Current KM',
    'Next Svc. KM',
    'Next Svc. Date',
    'Status'
];

// Print table headers
$pdf->SetFont('Arial', 'B', 9);
$pdf->SetFillColor(200, 220, 255); // Lighter blue background for headers
$pdf->SetTextColor(0); // Black text for headers
for ($i = 0; $i < count($headers); $i++) {
    $pdf->Cell($colWidths[$i], 7, $headers[$i], 1, 0, 'C', true);
}
$pdf->Ln(); // New line after headers

$pdf->SetFont('Arial', '', 8); // Set font for data rows
$pdf->SetTextColor(0); // Black text for data

$today = new DateTime();
$today->setTime(0,0,0);
$fourteenDaysFromNow = (clone $today)->add(new DateInterval('P14D'));

while($busRow = $busResult->fetch_assoc()){
    
    $lastServiceDate = new DateTime($busRow["last_service_date"]);
    $serviceIntervalMonths =(int)$busRow["service_interval_months"];
    $nextServiceDate = (clone $lastServiceDate)->add(new DateInterval("P".$serviceIntervalMonths."M"));
    
    $lastServiceMileage =(int)$busRow["last_service_mileage_km"];
    $currentMileage = (int)$busRow["current_mileage_km"];
    $serviceIntervalKM = (int)$busRow["service_interval_km"];
    $nextServiceMileage = $lastServiceMileage+$serviceIntervalKM;
    
    
    if($nextServiceDate>$fourteenDaysFromNow && $nextServiceMileage-$currentMileage>1000){
        
        continue;
    }
    
    
    $status="";
    
    if($nextServiceDate<=$today || $currentMileage>=$nextServiceMileage){
        $status = "Service Due";
    }
    elseif($nextServiceDate<$fourteenDaysFromNow){
        $status = "Upcoming (Date)";
    }
    elseif($nextServiceMileage<=$currentMileage+1000){
        $status = "Upcoming (Mileage)";
    }    
        
    
    $pdf->Cell($colWidths[0], 6,$busRow["vehicle_no"], 1, 0, 'L');
    $pdf->Cell($colWidths[1], 6,$busRow["make"], 1, 0, 'L');
    $pdf->Cell($colWidths[2], 6,$busRow["model"], 1, 0, 'L');
    $pdf->Cell($colWidths[3], 6,number_format($currentMileage), 1, 0, 'R');
    $pdf->Cell($colWidths[4], 6,number_format($nextServiceMileage), 1, 0, 'R');
    $pdf->Cell($colWidths[5], 6,$nextServiceDate->format("Y-m-d"), 1, 0, 'C');
    $pdf->Cell($colWidths[6], 6,$status, 1, 1, 'L');
}

$pdf->Ln(10);

$pdf->SetFont('Arial', '', 10);
$pdf->SetTextColor(0); // Black text for data
$pdf->MultiCell(190,5,"This report highlights buses that are due for services, "
        . "service date is within next 14 days or services within next 1,000 Kms.",0,"L",false);








$pdf->Output();