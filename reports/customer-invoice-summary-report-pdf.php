<?php

if (empty($_GET["start_date"]) || empty($_GET["end_date"])) {
    
    exit("<b style='color:red'>Enter Start and End Dates </b>");
}

$startDate = new DateTime($_GET["start_date"]);
$endDate = new DateTime($_GET["end_date"]);

require_once '../commons/ReportPDF.php';
include_once '../model/customer_invoice_model.php';
include_once '../model/customer_model.php';

$customerInvoiceObj = new CustomerInvoice();
$invoiceResult = $customerInvoiceObj->getAllInvoices();

$customerObj = new Customer();



$pdf = new ReportPDF();
$pdf->AliasNbPages(); // Enable page numbers
$pdf->setReportTitle('Customer Invoice Summary Report');

$pdf->AddPage("P", "A4"); // Add page after setting the title for Header() to pick it up

// Set initial content font and introductory text
$pdf->SetFont("Arial", "", 11);
$pdf->Cell(0, 10, 'Status of the invoices issued between '.$startDate->format("Y-m-d")." and ".$endDate->format("Y-m-d")." as follows,", 0, 1, 'L');
$pdf->Ln(5); // Small space before the table

$colWidths = [
    25,  // Invoice Number
    40,  // Customer Name
    25,  // Invoice Date
    25,  // Invoice Amount
    25,  // Tour Start Date
    25,  // Tour End Date
    25   // Invoice Status
];

$headers = [
    'Invoice No.',
    'Customer Name',
    'Invoice Date',
    'Amount',
    'Tour Start',
    'Tour End',
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
    
    $invoiceDate = new DateTime($invoiceRow["invoice_date"]);
    
    if($invoiceDate<$startDate || $invoiceDate>$endDate){
        continue;
    }
    
    $customerId = $invoiceRow["customer_id"];
    $customerResult = $customerObj->getCustomer($customerId);
    $customerRow = $customerResult->fetch_assoc();
    $customerName = $customerRow["customer_fname"]." ".$customerRow["customer_lname"];
    $invoiceNumber = $invoiceRow["invoice_number"];
    $tourStartDate = $invoiceRow["tour_start_date"];
    $tourEndDate = $invoiceRow["tour_end_date"];
    
    $status = match((int)$invoiceRow["invoice_status"]){
        
        -1=>"Cancelled",
        1,2=>"Pending Tour",
        3=>"Payment Pending",
        4=>"Paid",
    };
    
    if($invoiceRow["invoice_status"]==-1){
        $amount = "0";
    }elseif($invoiceRow["invoice_status"]==1 ||$invoiceRow["invoice_status"]==2 || $invoiceRow["invoice_status"]== 3){
        $amount = $invoiceRow["invoice_amount"];
    }else{
        $amount = $invoiceRow["actual_fare"];
    }
    
    $pdf->Cell($colWidths[0], 6,$invoiceNumber, 1, 0, 'L');
    $pdf->Cell($colWidths[1], 6,$customerName, 1, 0, 'L');
    $pdf->Cell($colWidths[2], 6,$invoiceDate->format("Y-m-d"), 1, 0, 'C');
    $pdf->Cell($colWidths[3], 6,number_format($amount, 2), 1, 0, 'R');
    $pdf->Cell($colWidths[4], 6,$tourStartDate, 1, 0, 'C');
    $pdf->Cell($colWidths[5], 6,$tourEndDate, 1, 0, 'C');
    $pdf->Cell($colWidths[6], 6,$status, 1, 1, 'L');
    
    
}

$pdf->Output();