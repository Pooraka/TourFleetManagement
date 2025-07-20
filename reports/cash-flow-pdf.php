<?php

require_once '../commons/ReportPDF.php';
include_once '../model/finance_model.php';
include_once '../model/user_model.php';

$dateFrom = $_GET["dateFrom"];
$dateTo = $_GET["dateTo"];
$txnType = $_GET["txnType"];

$financeObj = new Finance();
$userObj = new User();

$cashFlowResult = $financeObj->getCashFlowFiltered($dateFrom, $dateTo, $txnType);

$pdf = new ReportPDF();
$pdf->AliasNbPages(); // Enable page numbers
$pdf->setReportTitle('Cash Flow Report');

$pdf->AddPage("P", "A4"); // Add page after setting the title for Header() to pick it up

// Set initial content font and introductory text
$pdf->SetFont("Arial", "", 11);

if($cashFlowResult->num_rows==0){
    $pdf->Cell(0, 10, "No Records Found For The Selected Parameters", 0, 1, 'L');
    
    $pdf->Output();
}

if($dateFrom!="" && $dateTo!=""){
    $pdf->Cell(0, 8, "Cash Flow between $dateFrom and $dateTo as follows,", 0, 1, 'L');
}elseif ($dateFrom=="" && $dateTo=="") {
    $pdf->Cell(0, 8, "Cash Flow as follows,", 0, 1, 'L');
}

if($txnType==1){
    $pdf->Cell(0, 8, "Transaction Type : Service Payments", 0, 1, 'L');
}elseif($txnType==2){
    $pdf->Cell(0, 8, "Transaction Type : Supplier Payments", 0, 1, 'L');
}elseif($txnType==3){
    $pdf->Cell(0, 8, "Transaction Type : Tour Income", 0, 1, 'L');
}else{
    $pdf->Cell(0, 8, "Transaction Type : All Types", 0, 1, 'L');
}


$pdf->Ln(5); // Small space before the table

$colWidths = [
    20,  // Transaction Date
    35,  // Type
    55,  // Description
    30,  // Performed By
    20,  // Debit/Credit
    30  // Amount (LKR)
];

$headers = [
    'Txn Date',
    'Txn Type',
    'Description',
    'Performed By',
    'Debit/Credit',
    'Amount (LKR)'
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

$netBalance = (float)0.00;
$debits = (float)0.00;
$credits = (float)0.00;

while($cashFlowRow = $cashFlowResult->fetch_assoc()){
    
    $txnDate = $cashFlowRow["cash_book_txn_date"];
    
    $txnType = match((int)$cashFlowRow["txn_type"]){
        
        1=>"Service Payment",
        2=>"Supplier Payment",
        3=>"Tour Income",
    };
    
    $amount = (float) $cashFlowRow["txn_amount"];
    $netBalance += $amount;
    
    if($cashFlowRow["debit_credit_flag"]==1){
        
        $drCrFlag="Debit";
        $debits+=$amount;
    }else{
        
        $drCrFlag="Credit";
        $credits+=$amount;
    }
    
    $description = $cashFlowRow["txn_description"];
    
    $userId = $cashFlowRow["txn_performed_by"];
    $userResult = $userObj->getUser($userId);
    $userRow = $userResult->fetch_assoc();
    $performedBy = substr($userRow["user_fname"],0,1)." ".$userRow["user_lname"];
    
    $pdf->Cell($colWidths[0], 6,$txnDate, 1, 0, 'C');
    $pdf->Cell($colWidths[1], 6,$txnType, 1, 0, 'L');
    $pdf->Cell($colWidths[2], 6,$description, 1, 0, 'L');
    $pdf->Cell($colWidths[3], 6,$performedBy, 1, 0, 'L');
    $pdf->Cell($colWidths[4], 6,$drCrFlag, 1, 0, 'C');
    $pdf->Cell($colWidths[5], 6,number_format($amount, 2), 1, 1, 'R');
}


$debits = -($debits);

$pdf->SetFont('Arial', 'B', 9);

$pdf->Cell(55, 6,"Debits (LKR): ".number_format($debits, 2), 1, 0, 'C');
$pdf->Cell(55, 6,"Credits (LKR): ".number_format($credits, 2), 1, 0, 'C');
$pdf->Cell(80, 6,"Net Balance (LKR): ".number_format($netBalance, 2), 1, 1, 'C');






if($cashFlowResult->num_rows!=0){
$pdf->Output();
}
