<?php
require_once '../commons/ReportPDF.php';
include_once '../model/customer_invoice_model.php';
include_once '../model/customer_model.php';
include_once '../model/finance_model.php';
include_once '../model/user_model.php';

if(!isset($_GET['invoice_id'])){?>
    <script>
        window.location="../errorpages/403.php";
    </script>
<?php }

//Invoice Details
$invoiceId = base64_decode($_GET['invoice_id']);
$customerInvoiceObj = new CustomerInvoice();
$customerInvoiceResult = $customerInvoiceObj->getInvoice($invoiceId);
$customerInvoiceRow = $customerInvoiceResult->fetch_assoc();

//get Invoice Item Deatils
$customerInvoiceItemResult = $customerInvoiceObj->getInvoiceItems($invoiceId);

//get customer contact details
$customerId = $customerInvoiceRow["customer_id"];
$customerObj = new Customer();
$customerContactResult = $customerObj->getCustomerContact($customerId, 1);
$customerContactRow = $customerContactResult->fetch_assoc();

//get tour_income transaction
$financeObj = new Finance();
$tourIncomeResult = $financeObj->getTourIncomeRecordByInvoiceIdAndTourIncomeType($invoiceId,2);

if($tourIncomeResult->num_rows==1){
    
    $tourIncomeRow = $tourIncomeResult->fetch_assoc();

    //Accepted User Info
    $receivedBy = $tourIncomeRow['received_by'];
    $userObj = new User();
    $userResult = $userObj->getUser($receivedBy);
    $userRow = $userResult->fetch_assoc();
    $userName = $userRow['user_fname']." ".$userRow['user_lname'];

    $receiptNo = $tourIncomeRow['receipt_number'];
}
$pdf = new ReportPDF();
$pdf->AliasNbPages(); // Enable page numbers
$pdf->setReportTitle('Final Receipt');

$pdf->AddPage("P", "A4"); // Add page after setting the title for Header() to pick it up

//Get the current Y position to align both columns to the top
$topY = $pdf->GetY();

if($tourIncomeResult->num_rows==1){
    
    //Recipt Number
    $pdf->SetFont("Arial", "B", 10);
    $pdf->Cell(30,6,'Receipt #:',0,0,'');
    $pdf->SetFont("Arial", "", 10);
    $pdf->Cell(25, 6,$receiptNo,0,1,'R');
    
}

//Generated Date
$pdf->SetFont("Arial", "B", 10);
$pdf->Cell(30,6,'Generated On:',0,0,'');
$pdf->SetFont("Arial", "", 10);
$pdf->Cell(25, 6,date('Y-m-d'),0,1,'R');

//Invoice Reference
$pdf->SetFont("Arial", "B", 10);
$pdf->Cell(30,6,'Invoice #:',0,0,'');
$pdf->SetFont("Arial", "", 10);
$pdf->Cell(25, 6,$customerInvoiceRow['invoice_number'],0,1,'R');

//Start with right column
$pdf->SetXY(140,$topY);

//Billed To
$pdf->SetFont("Arial", "B", 10);
$pdf->Cell(60,6,'Billed To:',0,2,'R');
$pdf->SetFont("Arial", "", 10);
$pdf->MultiCell(60, 6, $customerInvoiceRow['customer_fname'].' '.$customerInvoiceRow['customer_lname'], 0,'R','');
$pdf->setX(140);
$pdf->MultiCell(60, 6, $customerInvoiceRow['customer_email'], 0,'R','');
$pdf->setX(140);
$pdf->MultiCell(60, 6,$customerContactRow['contact_number'], 0,'R','');

$pdf->Ln(2);
$y = $pdf->GetY();

//provide start x, start y, end x, end y 
$pdf->Line(10, $y, 200, $y);

//go down by 2 xs
$pdf->Ln(2);

$pdf->SetFont("Arial", "B", 12);
$pdf->Cell(60,6,'Tour Details',0,1,'');
$pdf->Ln(2);

//Tour Details
$y = $pdf->GetY();
$pdf->SetFont("Arial", "B", 10);
$pdf->Cell(30,6,'Start Date:',0,0,'');
$pdf->SetFont("Arial", "", 10);
$pdf->Cell(25, 6,$customerInvoiceRow['tour_start_date'],0,1,'R');
$pdf->SetFont("Arial", "B", 10);
$pdf->Cell(30,6,'End Date:',0,0,'');
$pdf->SetFont("Arial", "", 10);
$pdf->Cell(25, 6,$customerInvoiceRow['tour_end_date'],0,1,'R');
$pdf->SetFont("Arial", "B", 10);
$pdf->Cell(35,6,'Description:',0,0,'');
$pdf->SetFont("Arial", "", 10);
$pdf->MultiCell(75, 6, $customerInvoiceRow['invoice_description'], 0, '', '');

//right side of tour section
$pdf->SetXY(130, $y);
$pdf->SetFont("Arial", "B", 10);
$pdf->Cell(30,6,'Pickup Location:',0,0,'R');
$pdf->SetFont("Arial", "", 10);
$pdf->Cell(40, 6,$customerInvoiceRow['pickup_location'],0,2,'R');
$pdf->setX(130);
$pdf->SetFont("Arial", "B", 10);
$pdf->Cell(30,6,'Destination:',0,0,'R');
$pdf->SetFont("Arial", "", 10);
$pdf->Cell(40, 6,$customerInvoiceRow['destination'],0,2,'R');
$pdf->setX(130);
$pdf->SetFont("Arial", "B", 10);
$pdf->Cell(30,6,'Dropoff Location:',0,0,'R');
$pdf->SetFont("Arial", "", 10);
$pdf->Cell(40, 6,$customerInvoiceRow['dropoff_location'],0,2,'R');
$pdf->setX(130);
$pdf->SetFont("Arial", "B", 10);
$pdf->Cell(30,6,'Total Mileage:',0,0,'R');
$pdf->SetFont("Arial", "", 10);
$pdf->Cell(40, 6,$customerInvoiceRow['actual_mileage']." Km",0,2,'R');

$pdf->Ln(2);
$y = $pdf->GetY();

//provide start x, start y, end x, end y 
$pdf->Line(10, $y, 200, $y);

//go down by 2 xs
$pdf->Ln(2);

$pdf->SetFont("Arial", "B", 12);
$pdf->Cell(60,6,'Bus Details',0,1,'');
$pdf->Ln(2);

//Bus Details
$BusDetaily = $pdf->GetY();
$x = $pdf->GetX();
$pdf->SetFont('Arial', 'B', 10);
$pdf->SetFillColor(200, 220, 255); // Lighter blue background for headers
$pdf->SetTextColor(0); // Black text for headers
$pdf->Cell(40, 7,"Bus Category", 1, 0, 'C', true);
$pdf->Cell(40, 7,"Quantity Requested", 1, 1, 'C', true);
$pdf->SetX($x);

//Table data
$pdf->SetFont('Arial', '', 9);
$pdf->SetFillColor(255, 255, 255);
$pdf->SetTextColor(0);

while($customerInvoiceItemRow = $customerInvoiceItemResult->fetch_assoc()){
    
    $pdf->Cell(40, 6,$customerInvoiceItemRow['category_name'], 1, 0, 'C', true);
    $pdf->Cell(40, 6,$customerInvoiceItemRow['quantity'], 1, 1, 'C', true);
}

$AfterBusDetailsy =$pdf->GetY();


$pdf->SetY($AfterBusDetailsy);
$pdf->Ln(20);
$pdf->Line(10, $pdf->GetY(), 200, $pdf->GetY());
$pdf->Ln(2);

//Payment Info Title
$pdf->SetX(120);
$pdf->SetFont("Arial", "B", 12);
$pdf->Cell(80,6,'Payment Information',0,1,'R');
$pdf->Ln(3);


$estimatedAmount = (float)$customerInvoiceRow["invoice_amount"];
$actualAmount = (float)$customerInvoiceRow["actual_fare"];
$advanceAmount = (float)$customerInvoiceRow["advance_payment"];

if($tourIncomeResult->num_rows==1){
    $balanceAmount = (float)$tourIncomeRow['paid_amount'];
}else{
    $balanceAmount = (float)0.00;
}

//Payment Info
$y = $pdf->GetY();
$pdf->SetX(120);
$pdf->SetFont("Arial", "B", 10);
$pdf->Cell(45,6,'Estimated Amount',0,0,'');
$pdf->SetFont("Arial", "", 10);
$pdf->Cell(35, 6,"LKR ".number_format($estimatedAmount,2),0,1,'R');
$pdf->Ln(5);
$pdf->SetX(120);
$pdf->SetFont("Arial", "B", 10);
$pdf->Cell(45,6,'Actual Amount',0,0,'');
$pdf->SetFont("Arial", "", 10);
$pdf->Cell(35, 6,"LKR ".number_format($actualAmount,2),0,1,'R');
$pdf->SetX(120);
$pdf->SetFont("Arial", "B", 10);
$pdf->Cell(45,6,'Advance Amount',0,0,'');
$pdf->SetFont("Arial", "", 10);
$pdf->Cell(35, 6,"LKR ".number_format($advanceAmount,2),0,1,'R');

$pdf->Line(120, $pdf->GetY(),200, $pdf->GetY());

$pdf->SetX(120);
$pdf->SetFont("Arial", "B", 10);
$pdf->Cell(45,6,'Balance Amount Paid',0,0,'');
$pdf->SetFont("Arial", "", 10);
$pdf->Cell(35, 6,"LKR ".number_format($balanceAmount,2),0,1,'R');

$pdf->Ln(2);  
$pdf->Line(120, $pdf->GetY(),200, $pdf->GetY());
$pdf->Ln(1);
$pdf->Line(120, $pdf->GetY(),200, $pdf->GetY());

if($tourIncomeResult->num_rows==1){
    $pdf->Ln(5); 
    $pdf->SetX(120);
    $pdf->SetFont("Arial", "B", 10);
    $pdf->Cell(45,6,'Payment Date',0,0,'');
    $pdf->SetFont("Arial", "", 10);
    $pdf->Cell(35, 6,$tourIncomeRow['payment_date'],0,1,'R');
    $pdf->SetX(120);
    $pdf->SetFont("Arial", "B", 10);
    $pdf->Cell(45,6,'Received By:',0,0,'');
    $pdf->SetFont("Arial", "", 10);
    $pdf->Cell(35, 6,$userName,0,1,'R');
}

$pdf->Ln(15);
$pdf->SetFont("Arial", "B", 20);
$pdf->SetTextColor(0, 220, 0);
$pdf->Cell(190,10,'Fully Paid',1,1,'C');

$pdf->Ln(10);
$pdf->SetFont("Arial", "B", 12);
$pdf->SetTextColor(0, 0, 0);
$pdf->Cell(190,6,'Thank you for your business',0,1,'C');



// Output the PDF
$pdf->Output();

