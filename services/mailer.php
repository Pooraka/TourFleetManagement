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
}