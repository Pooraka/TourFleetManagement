<?php
// Ensure this path is correct for your FPDF library installation
require('fpdf186/fpdf.php'); 

class ReportPDF extends FPDF {
    protected $reportTitle; 

    function setReportTitle($title) {
        $this->reportTitle = $title;
    }

    // Page header
    function Header() {

        // I'm using 10mm margin from left, 8mm from top, and 30mm width for the logo.
        $this->Image('../images/resizedlogo.png', 10, 8, 30);

        // Company Name
        $this->SetFont('Arial', 'B', 20); 
        $this->SetTextColor(0, 102, 102); 

        // Calculate center for company name relative to the logo's right edge
        // Or simply center it across the page width
        $this->Cell(0, 10, 'SKYLINE TOURS', 0, 1, 'C'); // Centered across the page, new line after

        // Company Address
        $this->SetFont('Arial', '', 9);
        $this->SetTextColor(100, 100, 100); // Lighter grey for address
        $this->Cell(0, 5, 'Address:No 603/5A, Ihalabiyanwila, Kadawatha.       Mobile:0778810839       Email:chanu@st.lk', 0, 1, 'C'); // Centered, new line

        $this->Ln(5); // Add some space after company details

        // Specific Report Title
        $this->SetFont('Arial', 'B', 16); // Font for the report title
        $this->SetTextColor(50, 50, 50); // Darker grey for report title
        // Ensure reportTitle is set before Header is called implicitly by AddPage
        $this->Cell(0, 10, $this->reportTitle, 0, 1, 'C'); // Centered report title

        // Line break after heading
        $this->Ln(10); // Space before table headers
    }

    // Page footer
    function Footer() {
        // Position at 1.5 cm from bottom
        $this->SetY(-15);
        // Arial italic 8
        $this->SetFont('Arial', 'I', 8);
        // Page number
        $this->Cell(0, 10, 'Page ' . $this->PageNo() . '/{nb}', 0, 0, 'C');
        // Computer generated note
        $this->Cell(0, 10, 'This is a computer generated report and does not require a signature', 0, 0, 'R'); // Right aligned
    }
}
