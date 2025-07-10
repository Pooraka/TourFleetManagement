<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

class Mailer{
    
    private $mail;
    private $logoPath = "../images/resizedlogo.png";
    
    public function __construct() {
        
        $this->mail= new PHPMailer;
        $this->configureMailer();
    }
    
    private function configureMailer() {
        
        $this->mail->isSMTP();                     
        $this->mail->Host       = 'localhost';       
        $this->mail->SMTPAuth   = false;             
        $this->mail->Port       = 1025;
        $this->mail->setFrom('no-reply@st.lk', 'Skyline Tours');
    }
    
    public function sendOtpMail($email,$otp,$name){
        
        $this->mail->clearAddresses();
        $this->mail->clearAttachments();
        $this->mail->clearReplyTos();
        $this->mail->clearAllRecipients();
        $this->mail->clearCustomHeaders();
        
        $this->mail->addAddress($email,$name);
        $this->mail->isHTML(true);
        $this->mail->Subject = 'Your OTP For Ressetting Password';
        $logoCID ='Logo CID';
        $this->mail->addEmbeddedImage($this->logoPath, $logoCID);
        
        $htmlBody ='<!DOCTYPE html>
                    <html lang="en">
                        <head>
                            <title>Your OTP</title>
                        </head>
                        <body style="margin: 0; padding: 0; font-family: Arial, sans-serif; color: #333333; line-height: 1.6; background-color: #f4f4f4;">
                            <div style="max-width: 600px; margin: 10px auto; padding: 15px 25px; border: 1px solid #dddddd; background-color: #ffffff; border-top: 5px solid #007A7A;">
                                <div style="text-align: center; margin-bottom: 15px;">
                                    <img src="cid:'.$logoCID.'" alt="Skyline Tours Logo" style="max-width: 130px; height: auto; border:0;" />
                                </div>

                                <div style="padding: 0px;"> <p style="margin-bottom: 10px; font-size: 1em;">Hi '.$name.',</p>
                                </div>

                                <div style="padding: 0px;">
                                    <p style="margin-top: 10px;"><b>Your One Time Password is:</b></p>
                                    <p style="font-size: 2em; /* Increased size */
                                       font-weight: bold;
                                       text-align: center;
                                       background-color: #eef7f7; /* Lighter teal/grey background */
                                       color: #007A7A; /* Teal color for the OTP text */
                                       padding: 5px;
                                       margin-top: 2px;
                                       margin-bottom: 10px;
                                       letter-spacing: 4px; /* More spacing */
                                       border-radius: 5px; /* Slightly rounded corners */
                                       border: 1px dashed #007A7A; /* Dashed teal border */
                                       ">
                                        '.$otp.'
                                    </p>
                                    <p style="font-size: 1em; color: #555555;">This OTP is only valid for<b> 5 minutes</b></p>
                                </div>

                                <div style="margin-top: 2px; padding-top: 2px; border-top: 1px solid #eeeeee; text-align: center; font-size: 0.85em; color: #777777;">
                                    <p>Sent from Skyline Tours Fleet Management</p>
                                </div>
                            </div>
                        </body>
                    </html>';
        
        $this->mail->Body = $htmlBody;
         
        return $this->mail->send();
    }
    
    public function sendServiceDueBusList($userResult,$busResult){
        
        $this->mail->clearAddresses();
        $this->mail->clearAttachments();
        $this->mail->clearReplyTos();
        $this->mail->clearAllRecipients();
        $this->mail->clearCustomHeaders();
        
        $this->mail->isHTML(true);
        $this->mail->Subject = 'Service Due Buses';
        $logoCID ='Logo CID';
        $this->mail->addEmbeddedImage($this->logoPath, $logoCID);
        
        while($userRow=$userResult->fetch_assoc()){
            
            $name = $userRow['user_fname']." ".$userRow['user_lname'];
            $email = $userRow['user_email'];
            
            $this->mail->addAddress($email,$name);
        }
        
        $htmlBody ='<html>
                        <head>
                            <title>Service Reminder</title>
                        </head>
                        <body style="background-color: #f4f4f4;">
                            <div style="width:800px;margin: 20px auto;background-color: #ffffff;font-family: sans-serif;padding: 15px;border-top: 5px solid #007A7A;">
                                <div style="text-align: center; margin-bottom: 15px;">
                                    <img src="cid:'.$logoCID.'" alt="Skyline Tours Logo" style="max-width: 130px; height: auto; border:0;" />
                                </div>

                                <div style="background-color: #007A7A;color: #ffffff;padding: 15px; text-align: center; font-size: 18px;font-weight: bold;border-radius: 20px">
                                    Vehicle Service Reminder
                                </div>
                                <div style="padding: 25px;line-height: 1.6;">
                                    <p>Dear Team,</br>This is a reminder that the following vehicles are due for service based 
                                        on their mileage or time since last service:</p>  
                                    <table style="width: 100%; border-collapse: collapse;text-align: left">
                                        <thead>
                                            <tr style="background-color: #007A7A;color:#ffffff;text-align:center">
                                                <th style="border: 1px solid black;padding:5px">Vehicle No</th>
                                                <th style="border: 1px solid black;padding:5px">Last Serviced Date</th>
                                                <th style="border: 1px solid black;padding:5px">Last Serviced Mileage</th>
                                                <th style="border: 1px solid black;padding:5px">Current Mileage</th>
                                                <th style="border: 1px solid black;padding:5px">Current Mileage As At</th>
                                            </tr>
                                        </thead>
                                        <tbody>';
        
                                        while($busRow = $busResult->fetch_assoc()){
                                            
                                            $htmlBody.='<tr>
                                                        <td style="border: 1px solid black;padding:5px">'.$busRow['vehicle_no'].'</td>
                                                        <td style="border: 1px solid black;padding:5px">'.$busRow['last_service_date'].'</td>
                                                        <td style="border: 1px solid black;padding:5px">'.number_format($busRow['last_service_mileage_km']).' Km</td>
                                                        <td style="border: 1px solid black;padding:5px">'.number_format($busRow['current_mileage_km']).' Km</td>
                                                        <td style="border: 1px solid black;padding:5px">'.$busRow['current_mileage_as_at'].'</td>
                                                        </tr>';
                                        }

                
        $htmlBody.=                                '</tbody>
                                    </table>
                                    <p> Kindly initiate services for above vehicle using bus maintenance module. </br> Thank You.</p>
                                </div>
                                <div style="margin-top: 2px; padding-top: 2px; border-top: 1px solid #eeeeee; text-align: center; font-size: 0.85em; color: #777777;">
                                    <p>Sent from Skyline Tours Fleet Management</p>
                                </div>
                            </div>
                        </body>
                    </html>';
        
        $this->mail->Body = $htmlBody;
         
        return $this->mail->send();
    }
    
    public function sendSparePartListBelowReOrderLevel($userResult,$sparePartResult){
        
        $this->mail->clearAddresses();
        $this->mail->clearAttachments();
        $this->mail->clearReplyTos();
        $this->mail->clearAllRecipients();
        $this->mail->clearCustomHeaders();
        
        $this->mail->isHTML(true);
        $this->mail->Subject = 'Spare Part List Below Re-Order Level';
        $logoCID ='Logo CID';
        $this->mail->addEmbeddedImage($this->logoPath, $logoCID);
        
        while($userRow=$userResult->fetch_assoc()){
            
            $name = $userRow['user_fname']." ".$userRow['user_lname'];
            $email = $userRow['user_email'];
            
            $this->mail->addAddress($email,$name);
        }
        
        $htmlBody ='<html>
                        <head>
                            <title>Service Reminder</title>
                        </head>
                        <body style="background-color: #f4f4f4;">
                            <div style="width:800px;margin: 20px auto;background-color: #ffffff;font-family: sans-serif;padding: 15px;border-top: 5px solid #007A7A;">
                                <div style="text-align: center; margin-bottom: 15px;">
                                    <img src="cid:'.$logoCID.'" alt="Skyline Tours Logo" style="max-width: 130px; height: auto; border:0;" />
                                </div>

                                <div style="background-color: #007A7A;color: #ffffff;padding: 15px; text-align: center; font-size: 18px;font-weight: bold;border-radius: 20px">
                                    Reminder To Put Tenders For Spare Parts
                                </div>
                                <div style="padding: 25px;line-height: 1.6;">
                                    <p>Dear Team,</br>This is a reminder that the following spare parts are below or at the re-order level:</p>  
                                    <table style="width: 100%; border-collapse: collapse;text-align: left">
                                        <thead>
                                            <tr style="background-color: #007A7A;color:#ffffff;text-align:center">
                                                <th style="border: 1px solid black;padding:5px">Spare Part Number</th>
                                                <th style="border: 1px solid black;padding:5px">Spare Part Name </th>
                                                <th style="border: 1px solid black;padding:5px">Description</th>
                                                <th style="border: 1px solid black;padding:5px">Quantity On Hand</th>
                                                <th style="border: 1px solid black;padding:5px">Re-order level</th>
                                            </tr>
                                        </thead>
                                        <tbody>';
        
                                        while($sparePartRow = $sparePartResult->fetch_assoc()){
                                            
                                            $htmlBody.='<tr>
                                                        <td style="border: 1px solid black;padding:5px">'.$sparePartRow['part_number'].'</td>
                                                        <td style="border: 1px solid black;padding:5px">'.$sparePartRow['part_name'].'</td>
                                                        <td style="border: 1px solid black;padding:5px">'.$sparePartRow['description'].'</td>
                                                        <td style="border: 1px solid black;padding:5px;text-align:center">'.number_format($sparePartRow['quantity_on_hand']).'</td>
                                                        <td style="border: 1px solid black;padding:5px;text-align:center">'.number_format($sparePartRow['reorder_level']).'</td>
                                                        </tr>';
                                        }

                
        $htmlBody.=                                '</tbody>
                                    </table>
                                    <p> Kindly call for tenders for the above spare parts. </br> Thank You.</p>
                                </div>
                                <div style="margin-top: 2px; padding-top: 2px; border-top: 1px solid #eeeeee; text-align: center; font-size: 0.85em; color: #777777;">
                                    <p>Sent from Skyline Tours Fleet Management</p>
                                </div>
                            </div>
                        </body>
                    </html>';
        
        $this->mail->Body = $htmlBody;
         
        return $this->mail->send();
    }
}