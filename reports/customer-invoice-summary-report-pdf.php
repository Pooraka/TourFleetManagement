<?php


$dateFrom = $_GET["dateFrom"];
$dateTo = $_GET["dateTo"];

$invoiceStatus = $_GET["invoiceStatus"];

require_once '../commons/ReportPDF.php';
include_once '../model/customer_invoice_model.php';
include_once '../model/customer_model.php';

$customerInvoiceObj = new CustomerInvoice();
$invoiceResult = $customerInvoiceObj->getAllInvoicesFiltered($dateFrom,$dateTo,$invoiceStatus);

$customerObj = new Customer();



$pdf = new ReportPDF();
$pdf->AliasNbPages(); // Enable page numbers
$pdf->setReportTitle('Customer Invoice Summary Report');

$pdf->AddPage("L", "A4"); // Add page after setting the title for Header() to pick it up

// Set initial content font and introductory text
$pdf->SetFont("Arial", "", 11);

if($invoiceResult->num_rows==0){
    $pdf->Cell(0, 10, "No Records Found For The Selected Parameters", 0, 1, 'L');
    
    $pdf->Output();
}

if($dateFrom!="" && $dateTo!=""){
    $pdf->Cell(0, 10, "Status of the invoices issued between $dateFrom and $dateTo as follows,", 0, 1, 'L');
}elseif ($dateFrom=="" && $dateTo=="") {
    $pdf->Cell(0, 10, "Status of the invoices issued as follows,", 0, 1, 'L');
}

$pdf->Ln(5); // Small space before the table

$colWidths = [
    30,  // Invoice Number
    40,  // Customer Name
    25,  // Invoice Date
    40,  // Invoice Amount
    40,  // Actual Fare
    40,  // Paid Amount
    25,  // Tour Date
    25   // Invoice Status
];

$headers = [
    'Invoice No.',
    'Customer Name',
    'Invoice Date',
    'Invoice Amount (LKR)',
    'Actual Fare (LKR)',
    'Paid Amount (LKR)',
    'Tour Date',
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

while($invoiceRow = $invoiceResult->fetch_assoc()){
    
    $customerId = $invoiceRow["customer_id"];
    $customerResult = $customerObj->getCustomer($customerId);
    $customerRow = $customerResult->fetch_assoc();
    $customerName = $customerRow["customer_fname"]." ".$customerRow["customer_lname"];
    $invoiceNumber = $invoiceRow["invoice_number"];
    $invoiceDate = $invoiceRow["invoice_date"];
    $tourStartDate = $invoiceRow["tour_start_date"];
  
    
    $status = match((int)$invoiceRow["invoice_status"]){
        
        -1=>"Cancelled",
        1,2,3=>"Advance Paid",
        4=>"Completed",
    };
    
    if($invoiceRow["invoice_status"]==4){
        $actualFare = number_format($invoiceRow["actual_fare"],2);
    }else{
        $actualFare ="N/A";
    }
    
    $pdf->Cell($colWidths[0], 6,$invoiceNumber, 1, 0, 'L');
    $pdf->Cell($colWidths[1], 6,$customerName, 1, 0, 'L');
    $pdf->Cell($colWidths[2], 6,$invoiceDate, 1, 0, 'C');
    $pdf->Cell($colWidths[3], 6,number_format($invoiceRow["invoice_amount"], 2), 1, 0, 'R');
    $pdf->Cell($colWidths[4], 6,$actualFare, 1, 0, 'R');
    $pdf->Cell($colWidths[5], 6,number_format($invoiceRow["paid_amount"], 2), 1, 0, 'R');
    $pdf->Cell($colWidths[6], 6,$tourStartDate, 1, 0, 'C');
    $pdf->Cell($colWidths[7], 6,$status, 1, 1, 'L');
    
    
}

if($invoiceResult->num_rows!=0){
$pdf->Output();
}