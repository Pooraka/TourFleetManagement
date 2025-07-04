<?php
require_once '../commons/ReportPDF.php';
include_once '../model/quotation_model.php';
include_once '../model/customer_model.php';

if(!isset($_GET['quotation_id'])){?>
    <script>
        window.location="../errorpages/403.php";
    </script>
<?php }

$quotationId = base64_decode($_GET['quotation_id']);
$quotationObj = new Quotation();

$quotationResult = $quotationObj->getQuotation($quotationId);
$quotationRow = $quotationResult->fetch_assoc();

//get quotation item details
$quotationItemResult = $quotationObj->getQuotationItems($quotationId);

//get customer contact details
$customerId = $quotationRow["customer_id"];
$customerObj = new Customer();
$customerContactResult = $customerObj->getCustomerContact($customerId, 1);
$customerContactRow = $customerContactResult->fetch_assoc();


$pdf = new ReportPDF();
$pdf->AliasNbPages(); // Enable page numbers
$pdf->setReportTitle('Quotation');

$pdf->AddPage("P", "A4"); // Add page after setting the title for Header() to pick it up



//Get the current Y position to align both columns to the top
$topY = $pdf->GetY();

//Quotation Number
$pdf->SetFont("Arial", "B", 10);
$pdf->Cell(30,6,'Quotation #:',0,0,'');
$pdf->SetFont("Arial", "", 10);
$pdf->Cell(15, 6,$quotationId,0,1,'R');

//Date Of Issue
$pdf->SetFont("Arial", "B", 10);
$pdf->Cell(30,6,'Date Of Issue:',0,0,'');
$pdf->SetFont("Arial", "", 10);
$pdf->Cell(25, 6,$quotationRow['issued_date'],0,1,'R');

//Valid Until
$pdf->SetFont("Arial", "B", 10);
$pdf->Cell(30,6,'Valid Until:',0,0,'');
$pdf->SetFont("Arial", "", 10);
$pdf->Cell(25, 6,date('Y-m-d',strtotime($quotationRow['issued_date'].'+7 day')),0,1,'R');

//Start with right column
$pdf->SetXY(140,$topY);

//Billing Details
$pdf->SetFont("Arial", "B", 10);
$pdf->Cell(60,6,'Quotated To:',0,2,'R');
$pdf->SetFont("Arial", "", 10);
$pdf->MultiCell(60, 6, $quotationRow['customer_fname'].' '.$quotationRow['customer_lname'], 0,'R','');
$pdf->setX(140);
$pdf->MultiCell(60, 6, $quotationRow['customer_email'], 0,'R','');
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
$pdf->Cell(25, 6,$quotationRow['tour_start_date'],0,1,'R');
$pdf->SetFont("Arial", "B", 10);
$pdf->Cell(30,6,'End Date:',0,0,'');
$pdf->SetFont("Arial", "", 10);
$pdf->Cell(25, 6,$quotationRow['tour_end_date'],0,1,'R');
$pdf->SetFont("Arial", "B", 10);
$pdf->Cell(35,6,'Description:',0,0,'');
$pdf->SetFont("Arial", "", 10);
$pdf->MultiCell(75, 6, $quotationRow['description'], 0, '', '');


//right side of tour section
$pdf->SetXY(130, $y);
$pdf->SetFont("Arial", "B", 10);
$pdf->Cell(30,6,'Pickup Location:',0,0,'R');
$pdf->SetFont("Arial", "", 10);
$pdf->Cell(40, 6,$quotationRow['pickup_location'],0,2,'R');
$pdf->setX(130);
$pdf->SetFont("Arial", "B", 10);
$pdf->Cell(30,6,'Destination:',0,0,'R');
$pdf->SetFont("Arial", "", 10);
$pdf->Cell(40, 6,$quotationRow['destination'],0,2,'R');
$pdf->setX(130);
$pdf->SetFont("Arial", "B", 10);
$pdf->Cell(30,6,'Dropoff Location:',0,0,'R');
$pdf->SetFont("Arial", "", 10);
$pdf->Cell(40, 6,$quotationRow['dropoff_location'],0,2,'R');
$pdf->setX(130);
$pdf->SetFont("Arial", "B", 10);
$pdf->Cell(30,6,'Estimated Mileage:',0,0,'R');
$pdf->SetFont("Arial", "", 10);
$pdf->Cell(40, 6,$quotationRow['round_trip_mileage']." Km",0,2,'R');

$pdf->Ln(2);
$y = $pdf->GetY();

//provide start x, start y, end x, end y 
$pdf->Line(10, $y, 200, $y);

//go down by 2 xs
$pdf->Ln(2);

$pdf->SetFont("Arial", "B", 12);
$pdf->Cell(60,6,'Bus Requested',0,1,'');
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

while($quotationItemRow = $quotationItemResult->fetch_assoc()){
    
    $pdf->Cell(40, 6,$quotationItemRow['category_name'], 1, 0, 'C', true);
    $pdf->Cell(40, 6,$quotationItemRow['quantity'], 1, 1, 'C', true);
}

$AfterBusDetailsy =$pdf->GetY();

//Amount Details
$pdf->SetXY(140, $BusDetaily);
$pdf->SetFont("Arial", "B", 12);
$pdf->Cell(60,6,'Total Amount',0,1,'R');
$pdf->SetX(140);
$pdf->SetFont("Arial", "", 10);
$pdf->Cell(0, 6,"LKR ".number_format($quotationRow['total_amount'],2),0,1,'R');

$pdf->SetY($AfterBusDetailsy);
$pdf->Ln(2);
$pdf->Line(10, $pdf->GetY(), 200, $pdf->GetY());
$pdf->Ln(2);

$pdf->SetFont("Arial", "B", 12);
$pdf->Cell(60,6,'Terms & Conditions',0,1,'');
$pdf->Ln(2);

//Terms and conditions
$pdf->SetFont("Arial", "", 11);
$pdf->MultiCell(190, 6,'1) This quotation is valid for 7 days from the date of issue.', 0, '', false);
$pdf->MultiCell(190, 6,'2) Buses are offered subject to availability at the time of booking confirmation.', 0, '', false);
$pdf->MultiCell(190, 6,'3) Any damages to the bus caused by passengers will be borne by the customer.', 0, '', false);

$pdf->Ln(10);
$pdf->SetFont("Arial", "B", 12);
$pdf->Cell(190,6,'Thank you for your business',0,1,'C');

$pdf->Ln(5);
$pdf->SetFont("Arial", "", 10);
$pdf->Cell(190,6,'Please contact us if you have any questions about this quotation.',0,1,'C');



// Output the PDF
$pdf->Output();

