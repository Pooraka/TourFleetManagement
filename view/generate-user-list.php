<?php

include_once '../model/user_model.php';
$userObj = new User();
$userResult = $userObj->getAllUsers();

//include the library
include '../commons/fpdf186/fpdf.php';

$fpdf = new FPDF("P");

//Document Title
$fpdf->SetTitle("User Report");

$dateTime=date(" H:i:s, Y-m-d");

//Add Page with header
$fpdf->AddPage("P", "A4");
$fpdf->SetFont("Arial","B","20");
$fpdf->Image("../images/logo.png", 10, 7, 27, 27);
$fpdf->Cell(110,20,"Skyline Tours",0,0,"C");
$fpdf->SetFont("Arial","","10");
$fpdf->Cell(100,20,"No 603/5A, Ihalabiyanwila, Kadawatha.",0,1,"C");



//Page Title
$fpdf->SetFont("Arial","U","18");
$fpdf->Cell(0,10,"User List",0,1,"C");


$fpdf->SetFont("Arial","","11");
$fpdf->Cell(0,20,"The system user list as of $dateTime are as below,",0,1,"L");

//Table header
$fpdf->SetFont("Arial","B","11");
$fpdf->Cell(50,10,"Name",1,0,"C");
$fpdf->Cell(50,10,"User role",1,0,"C");
$fpdf->Cell(50,10,"Email",1,0,"C");
$fpdf->Cell(30,10,"Status",1,1,"C");


$fpdf->SetFont("Arial","","11");
while($userRow=$userResult->fetch_assoc()){
    
    $status = ($userRow['user_status']=='1')?"Active":"Deactive";
    
    $fpdf->Cell(50,10,$userRow['user_fname']." ".$userRow['user_lname'],1,0,"C");
    $fpdf->SetFontSize("10");
    $fpdf->Cell(50,10,$userRow['role_name'],1,0,"C");
    $fpdf->Cell(50,10,$userRow['user_email'],1,0,"C");
    $fpdf->SetFontSize("11");
    $fpdf->Cell(30,10,$status,1,1,"C");
    
}

$fpdf->SetFontSize("10");

$fpdf->Cell(0,15,"This is a computer generated report and does not require a signature",0,0,"C");













$fpdf->Output();

