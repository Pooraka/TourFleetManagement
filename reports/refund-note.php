<?php

require_once '../commons/ReportPDF.php';

if(!isset($_GET['invoice_id'])){?>
    <script>
        window.location="../errorpages/403.php";
    </script>
<?php }

 include_once '../model/finance_model.php';
 include_once '../model/customer_model.php';
 include_once '../model/user_model.php';
 include_once '../model/customer_invoice_model.php';

//Invoice Details
$invoiceId = base64_decode($_GET['invoice_id']);
$financeObj = new Finance();
$refundRecordResult = $financeObj->getRefundRecordOfACancelledInvoice($invoiceId);

if($refundRecordResult->num_rows==1){
    
    $refundRecordRow = $refundRecordResult->fetch_assoc();

    //Customer Info
    $customerId = $refundRecordRow["customer_id"];
    $customerObj = new Customer();
    $customerResult = $customerObj->getCustomer($customerId);
    $customerRow = $customerResult->fetch_assoc();

    $customerMobileResult = $customerObj->getCustomerContact($customerId,1);
    $customerMobileRow = $customerMobileResult->fetch_assoc();

    //Refunded User Info
    $userObj = new User();
    $userId = $refundRecordRow["received_by"];
    $userResult = $userObj->getUser($userId);
    $userRow = $userResult->fetch_assoc();

    $pdf = new ReportPDF();
    $pdf->AliasNbPages(); // Enable page numbers
    $pdf->setReportTitle('Refund Note');

    $pdf->AddPage("P", "A4"); // Add page after setting the title for Header() to pick it up

    //Get the current Y position to align both columns to the top
    $topY = $pdf->GetY();

    //Refund Note Number
    $pdf->SetFont("Arial", "B", 10);
    $pdf->Cell(30,6,'Refund Note #:',0,0,'');
    $pdf->SetFont("Arial", "", 10);
    $pdf->Cell(25, 6,$refundRecordRow["receipt_number"],0,1,'R');

    //Generated Date
    $pdf->SetFont("Arial", "B", 10);
    $pdf->Cell(30,6,'Generated On:',0,0,'');
    $pdf->SetFont("Arial", "", 10);
    $pdf->Cell(25, 6,date('Y-m-d'),0,1,'R');

    //Invoice Reference
    $pdf->SetFont("Arial", "B", 10);
    $pdf->Cell(30,6,'Invoice #:',0,0,'');
    $pdf->SetFont("Arial", "", 10);
    $pdf->Cell(25, 6,$refundRecordRow['invoice_number'],0,1,'R');

    //Refunded Date
    $pdf->SetFont("Arial", "B", 10);
    $pdf->Cell(30,6,'Refunded On:',0,0,'');
    $pdf->SetFont("Arial", "", 10);
    $pdf->Cell(25, 6,$refundRecordRow['payment_date'],0,1,'R');

    //Start with right column
    $pdf->SetXY(140,$topY);

    //Issued To
    $pdf->SetFont("Arial", "B", 10);
    $pdf->Cell(60,6,'Issued To:',0,2,'R');
    $pdf->SetFont("Arial", "", 10);
    $pdf->MultiCell(60, 6, $customerRow['customer_fname'].' '.$customerRow['customer_lname'], 0,'R','');
    $pdf->setX(140);
    $pdf->MultiCell(60, 6, $customerRow['customer_email'], 0,'R','');
    $pdf->setX(140);
    $pdf->MultiCell(60, 6,$customerMobileRow['contact_number'], 0,'R','');

    $pdf->Ln(2);
    $y = $pdf->GetY();

    //provide start x, start y, end x, end y 
    $pdf->Line(10, $y, 200, $y);

    //go down by 2 xs
    $pdf->Ln(2);

    //Transaction Details

    $invoiceAmount = (float)$refundRecordRow["invoice_amount"];
    $advanceAmount = (float)$refundRecordRow["advance_payment"];

    $cancellationFee = (float) ($refundRecordRow["tour_income_remarks"]==1)?$invoiceAmount*0.2:0.00;

    $reason = ($refundRecordRow["tour_income_remarks"]==1)?"Requested By The Customer":"Management Decision";

    $amountRefunded = $advanceAmount-$cancellationFee;

    $pdf->SetFont("Arial", "B", 12);
    $pdf->Cell(60,6,'Refund Details',0,1,'');
    $pdf->Ln(2);

    $y = $pdf->GetY();
    $pdf->SetFont("Arial", "B", 10);
    $pdf->Cell(15,6,'Reason:',0,0,'');
    $pdf->SetFont("Arial", "", 10);
    $pdf->Cell(50, 6,$reason,0,1,'L');

    $pdf->SetFont("Arial", "B", 10);
    $pdf->Cell(25,6,'Refunded By:',0,0,'');
    $pdf->SetFont("Arial", "", 10);
    $pdf->Cell(50, 6,$userRow["user_fname"]." ".$userRow["user_lname"],0,1,'L');

    //Right side
    $pdf->SetXY(120, $y);
    $pdf->SetFont("Arial", "B", 10);
    $pdf->Cell(40,6,'Invoice Amount:',0,0,'L');
    $pdf->SetFont("Arial", "", 10);
    $pdf->Cell(40, 6,"LKR ".number_format($invoiceAmount,2),0,2,'R');

    $pdf->Ln(5);

    $pdf->setX(120);
    $pdf->SetFont("Arial", "B", 10);
    $pdf->Cell(40,6,'Advance Amount:',0,0,'L');
    $pdf->SetFont("Arial", "", 10);
    $pdf->Cell(40, 6,"LKR ". number_format($advanceAmount,2),0,2,'R');

    $pdf->setX(120);
    $pdf->SetFont("Arial", "B", 10);
    $pdf->Cell(40,6,'Cancellation Fee:',0,0,'L');
    $pdf->SetFont("Arial", "", 10);
    $pdf->Cell(40, 6,"LKR ". number_format($cancellationFee,2),0,2,'R');

    $pdf->Line(120, $pdf->GetY(),200, $pdf->GetY());

    $pdf->setX(120);
    $pdf->SetFont("Arial", "B", 10);
    $pdf->Cell(40,6,'Refunded Amount:',0,0,'L');
    $pdf->SetFont("Arial", "", 10);
    $pdf->Cell(40, 6,"LKR ". number_format($amountRefunded,2),0,2,'R');

    $pdf->Ln(2);  
    $pdf->Line(120, $pdf->GetY(),200, $pdf->GetY());
    $pdf->Ln(1);
    $pdf->Line(120, $pdf->GetY(),200, $pdf->GetY());

    $pdf->Ln(10); 

    $pdf->SetX(10);

    $pdf->SetFont("Arial", "B", 10);

    if($refundRecordRow["tour_income_remarks"]==1){

        $pdf->MultiCell(190, 7,"This receipt confirms a refund has been processed as per your cancellation request.", 0,"C",false);
        $pdf->MultiCell(190, 7,"In accordance with our booking policy, a 20% cancellation fee has been applied to the total invoice amount.", 0,"C",false);
        $pdf->MultiCell(190, 7,"We appreciate your understanding.", 0,"C",false);

    }elseif ($refundRecordRow["tour_income_remarks"]==2) {

        $pdf->MultiCell(190, 7,"Please accept our sincere apologies as we were unable to fulfill this booking due to unforeseen circumstances.", 0,"C",false);
        $pdf->MultiCell(190, 7,"This receipt confirms that a full refund of all payments made has been processed.", 0,"C",false);
        $pdf->MultiCell(190, 7,"We value your business and hope to serve you in the future.", 0,"C",false);
    }
}
else{
    
    $customerInvoiceObj = new CustomerInvoice();
    
    $invoiceResult = $customerInvoiceObj->getInvoice($invoiceId);
    $invoiceRow = $invoiceResult->fetch_assoc();
    
    //Customer Info
    $customerId = $invoiceRow["customer_id"];
    $customerObj = new Customer();
    $customerResult = $customerObj->getCustomer($customerId);
    $customerRow = $customerResult->fetch_assoc();

    $customerMobileResult = $customerObj->getCustomerContact($customerId,1);
    $customerMobileRow = $customerMobileResult->fetch_assoc();
    
    $pdf = new ReportPDF();
    $pdf->AliasNbPages(); // Enable page numbers
    $pdf->setReportTitle('Refund Note');

    $pdf->AddPage("P", "A4"); // Add page after setting the title for Header() to pick it up

    //Get the current Y position to align both columns to the top
    $topY = $pdf->GetY();
    
    //Generated Date
    $pdf->SetFont("Arial", "B", 10);
    $pdf->Cell(30,6,'Generated On:',0,0,'');
    $pdf->SetFont("Arial", "", 10);
    $pdf->Cell(25, 6,date('Y-m-d'),0,1,'R');

    //Invoice Reference
    $pdf->SetFont("Arial", "B", 10);
    $pdf->Cell(30,6,'Invoice #:',0,0,'');
    $pdf->SetFont("Arial", "", 10);
    $pdf->Cell(25, 6,$invoiceRow['invoice_number'],0,1,'R');
    
    //Start with right column
    $pdf->SetXY(140,$topY);

    //Issued To
    $pdf->SetFont("Arial", "B", 10);
    $pdf->Cell(60,6,'Issued To:',0,2,'R');
    $pdf->SetFont("Arial", "", 10);
    $pdf->MultiCell(60, 6, $customerRow['customer_fname'].' '.$customerRow['customer_lname'], 0,'R','');
    $pdf->setX(140);
    $pdf->MultiCell(60, 6, $customerRow['customer_email'], 0,'R','');
    $pdf->setX(140);
    $pdf->MultiCell(60, 6,$customerMobileRow['contact_number'], 0,'R','');

    $pdf->Ln(2);
    $y = $pdf->GetY();

    //provide start x, start y, end x, end y 
    $pdf->Line(10, $y, 200, $y);

    //go down by 2 xs
    $pdf->Ln(2);
    
    //Transaction Details

    $invoiceAmount = (float)$invoiceRow["invoice_amount"];
    $advanceAmount = (float)$invoiceRow["advance_payment"];
    
    $cancellationFee = (float)$invoiceAmount*0.2;

    $reason = "Requested By The Customer";

    $amountRefunded = $advanceAmount-$cancellationFee;
    
    $pdf->SetFont("Arial", "B", 12);
    $pdf->Cell(60,6,'Refund Details',0,1,'');
    $pdf->Ln(2);

    $y = $pdf->GetY();
    $pdf->SetFont("Arial", "B", 10);
    $pdf->Cell(15,6,'Reason:',0,0,'');
    $pdf->SetFont("Arial", "", 10);
    $pdf->Cell(50, 6,$reason,0,1,'L');
    
    //Right side
    $pdf->SetXY(120, $y);
    $pdf->SetFont("Arial", "B", 10);
    $pdf->Cell(40,6,'Invoice Amount:',0,0,'L');
    $pdf->SetFont("Arial", "", 10);
    $pdf->Cell(40, 6,"LKR ".number_format($invoiceAmount,2),0,2,'R');

    $pdf->Ln(5);

    $pdf->setX(120);
    $pdf->SetFont("Arial", "B", 10);
    $pdf->Cell(40,6,'Advance Amount:',0,0,'L');
    $pdf->SetFont("Arial", "", 10);
    $pdf->Cell(40, 6,"LKR ". number_format($advanceAmount,2),0,2,'R');

    $pdf->setX(120);
    $pdf->SetFont("Arial", "B", 10);
    $pdf->Cell(40,6,'Cancellation Fee:',0,0,'L');
    $pdf->SetFont("Arial", "", 10);
    $pdf->Cell(40, 6,"LKR ". number_format($cancellationFee,2),0,2,'R');

    $pdf->Line(120, $pdf->GetY(),200, $pdf->GetY());

    $pdf->setX(120);
    $pdf->SetFont("Arial", "B", 10);
    $pdf->Cell(40,6,'Refunded Amount:',0,0,'L');
    $pdf->SetFont("Arial", "", 10);
    $pdf->Cell(40, 6,"LKR ". number_format($amountRefunded,2),0,2,'R');

    $pdf->Ln(2);  
    $pdf->Line(120, $pdf->GetY(),200, $pdf->GetY());
    $pdf->Ln(1);
    $pdf->Line(120, $pdf->GetY(),200, $pdf->GetY());

    $pdf->Ln(10); 

    $pdf->SetX(10);

    $pdf->SetFont("Arial", "B", 10);
    
    $pdf->MultiCell(190, 7,"This note confirms the booking cancellation has been processed as per your cancellation request.", 0,"C",false);
    $pdf->MultiCell(190, 7,"In accordance with our booking policy, a 20% cancellation fee has been applied to the total invoice amount.", 0,"C",false);
    $pdf->MultiCell(190, 7,"We appreciate your understanding.", 0,"C",false);
}

$pdf->output();

