<?php
 
//Import PHPMailer classes into the global namespace
//These must be at the top of your script, not inside a function
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
 
//required files
require 'phpmailer/src/Exception.php';
require 'phpmailer/src/PHPMailer.php';
require 'phpmailer/src/SMTP.php';
 
//Create an instance; passing true enables exceptions
if (isset($_POST["order"])) {
 
  $mail = new PHPMailer(true);
 
    //Server settings
    $mail->isSMTP();                              //Send using SMTP
    $mail->Host       = 'smtp.gmail.com';       //Set the SMTP server to send through
    $mail->SMTPAuth   = true;             //Enable SMTP authentication
    $mail->Username   = 'arpitkukadiya10@gmail.com';   //SMTP write your email
    $mail->Password   = 'crmscaebqyzqvist';      //SMTP password
    $mail->SMTPSecure = 'ssl';            //Enable implicit SSL encryption
    $mail->Port       = 465;                                    
 
    //Recipients
    $mail->setFrom( $_POST["email"], $_POST["name"]); // Sender Email and name
    $mail->addAddress($_POST["email"]);     //Add a recipient email  
    $mail->addReplyTo($_POST["email"], $_POST["name"]); // reply to sender email
 
    //Content
    $mail->isHTML(true);              //Set email format to HTML
    $mail->Subject = ('you placed order');   // email subject headings //$_POST["subject"]
   // $mail->Body    = $_POST["message"]; //email message
      
    // Success sent message alert
    $mail->send();
    echo
    " 
    <script> 
     alert('your order placed successfully');
     document.location.href = 'checkout.php';
    </script>
    ";
}