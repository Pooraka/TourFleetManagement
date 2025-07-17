<?php
require_once '../commons/ReportPDF.php';
include_once '../model/customer_invoice_model.php';
include_once '../model/customer_model.php';

if(!isset($_GET['invoice_id'])){?>
    <script>
        window.location="../errorpages/403.php";
    </script>
<?php }

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

$pdf = new ReportPDF();
$pdf->AliasNbPages(); // Enable page numbers
$pdf->setReportTitle('Booking Confirmation');

$pdf->AddPage("P", "A4"); // Add page after setting the title for Header() to pick it up

//Get the current Y position to align both columns to the top
$topY = $pdf->GetY();

//Invoice Number
$pdf->SetFont("Arial", "B", 10);
$pdf->Cell(30,6,'Invoice #:',0,0,'');
$pdf->SetFont("Arial", "", 10);
$pdf->Cell(25, 6,$customerInvoiceRow['invoice_number'],0,1,'R');

//Date Of Issue
$pdf->SetFont("Arial", "B", 10);
$pdf->Cell(30,6,'Invoice Date:',0,0,'');
$pdf->SetFont("Arial", "", 10);
$pdf->Cell(25, 6,$customerInvoiceRow['invoice_date'],0,1,'R');

//Quotation Reference
$pdf->SetFont("Arial", "B", 10);
$pdf->Cell(30,6,'Quotation #:',0,0,'');
$pdf->SetFont("Arial", "", 10);
$pdf->Cell(25, 6,$customerInvoiceRow['quotation_id'],0,1,'R');

//Start with right column
$pdf->SetXY(140,$topY);

//Confirmed For
$pdf->SetFont("Arial", "B", 10);
$pdf->Cell(60,6,'Confirmed For:',0,2,'R');
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

//Tour Details Heading
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
$pdf->Cell(30,6,'Estimated Mileage:',0,0,'R');
$pdf->SetFont("Arial", "", 10);
$pdf->Cell(40, 6,$customerInvoiceRow['round_trip_mileage']." Km",0,2,'R');

$pdf->Ln(2);
$y = $pdf->GetY();

//provide start x, start y, end x, end y 
$pdf->Line(10, $y, 200, $y);

//go down by 2 xs
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

$pdf->Ln(10);

$AfterBusDetailsy =$pdf->GetY();


$invoiceAmount = (float)$customerInvoiceRow['invoice_amount'];
$advancePayment = (float)$customerInvoiceRow['advance_payment'];
$balanceAmount = $invoiceAmount - $advancePayment;

//Amount Details
$pdf->SetXY(120, $BusDetaily);
$pdf->SetFont("Arial", "B", 10);
$pdf->Cell(40,6,'Invoice Amount:',0,0,'R');
$pdf->SetFont("Arial", "", 10);
$pdf->Cell(40, 6,"LKR ".number_format($invoiceAmount,2),0,2,'R');
$pdf->SetX(120);
$pdf->SetFont("Arial", "B", 10);
$pdf->Cell(40,6,'Advance Payment:',0,0,'R');
$pdf->SetFont("Arial", "", 10);
$pdf->Cell(40, 6,"LKR ".number_format($advancePayment,2),0,2,'R');

$pdf->Ln(2);
$pdf->Line(125, $pdf->GetY(), 200, $pdf->GetY());
$pdf->Ln(2);
$pdf->SetX(120);
$pdf->SetFont("Arial", "B", 10);
$pdf->Cell(40,6,'Balance Amount:',0,0,'R');
$pdf->SetFont("Arial", "", 10);
$pdf->Cell(40, 6,"LKR ".number_format($balanceAmount,2),0,2,'R');

$pdf->Ln(2);
$pdf->Line(125, $pdf->GetY(), 200, $pdf->GetY());
$pdf->Ln(1);
$pdf->Line(125, $pdf->GetY(), 200, $pdf->GetY());



$pdf->SetY($AfterBusDetailsy);
$pdf->Ln(20);
$pdf->Line(10, $pdf->GetY(), 200, $pdf->GetY());
$pdf->Ln(2);

$pdf->SetFont("Arial", "B", 12);
$pdf->Cell(60,6,'Terms & Conditions',0,1,'');
$pdf->Ln(2);

//Terms and conditions
$pdf->SetFont("Arial", "", 11);
$pdf->MultiCell(190, 6,'1) Final fare is subject to change once the tour is completed.', 0, '', false);
$pdf->MultiCell(190, 6,'2) Remaining Balance should be paid on the day of the tour, once final fare is informed.', 0, '', false);
$pdf->MultiCell(190, 6,'3) Any damages to the bus caused by passengers will be borne by the customer.', 0, '', false);

$pdf->Ln(10);
$pdf->SetFont("Arial", "B", 12);
$pdf->Cell(190,6,'Thank you for your business',0,1,'C');

$pdf->Ln(5);
$pdf->SetFont("Arial", "", 10);
$pdf->Cell(190,6,'Please contact us if you have any questions about this bookings.',0,1,'C');


// Output the PDF
$pdf->Output();

