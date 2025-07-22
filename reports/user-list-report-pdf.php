<?php


require_once '../commons/ReportPDF.php';
include_once '../model/user_model.php';

$userStatus = $_GET["userStatus"];


$userObj = new User();
$userResult = $userObj->getAllUsersFiltered($userStatus);

$pdf = new ReportPDF();
$pdf->AliasNbPages(); // Enable page numbers
$pdf->setReportTitle('User List Report');

$pdf->AddPage("P", "A4"); // Add page after setting the title for Header() to pick it up

// Set initial content font and introductory text
$pdf->SetFont("Arial", "", 11);

$pdf->Cell(0, 8, 'User List as of ' . date("H:i:s, Y-m-d"), 0, 1, 'L');

if($userStatus!=""){
    
    $displayStatus = match((int)$userStatus){
        
        -1=>"Removed",
        1=>"Active",   
        0=>"De-activated",   
     
    };

    $pdf->Cell(0, 8,'User Status: '.$displayStatus, 0, 1, 'L');
}

$pdf->Ln(5); // Small space before the table

$colWidths = [
    50,  // Name
    40,  // User Role
    50,  // Email
    30   // Status
];

$headers = [
    'Name',
    'User Role',
    'Email',
    'Status'
];

if($userResult->num_rows == 0){
    $pdf->SetFont("Arial", "B", 11);
    $pdf->Cell(0, 10, 'No user records found for the selected filters.', 0, 1, 'C');
    $pdf->Output();
    exit;
}

$pdf->SetFont('Arial', 'B', 11);
$pdf->SetFillColor(200, 220, 255); // Lighter blue background for headers
$pdf->SetTextColor(0); // Black text for headers
for ($i = 0; $i < count($headers); $i++) {
    $pdf->Cell($colWidths[$i], 7, $headers[$i], 1, 0, 'C', true);
}
$pdf->Ln();

$pdf->SetFont('Arial', '', 9);

while ($userRow = $userResult->fetch_assoc()) {

    $status = match((int)$userRow['user_status']) {
        1 => "Active",
        0 => "De-activated",
        -1 => "Removed"
    };

    $pdf->Cell($colWidths[0], 6, $userRow['user_fname'] . " " . $userRow['user_lname'], 1, 0, 'L');
    $pdf->Cell($colWidths[1], 6, $userRow['role_name'], 1, 0, 'L');
    $pdf->Cell($colWidths[2], 6, $userRow['user_email'], 1, 0, 'L');
    $pdf->Cell($colWidths[3], 6, $status, 1, 1, 'C');
}

$pdf->Ln(5); // Add some space before the footer

$pdf->output('I', 'user-list-report.pdf'); // Output the PDF to the browser
exit;

