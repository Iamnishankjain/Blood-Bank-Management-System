<?php
$name=$_POST['fullname'];
$number=$_POST['mobileno'];
$email=$_POST['emailid'];
$age=$_POST['age'];
$aadhar=$_POST['aadhar'];
$gender=$_POST['gender'];
$blood_group=$_POST['blood'];
$address=$_POST['address'];
$conn=mysqli_connect("localhost","root","","blood_donation") or die("Connection error");
$sql= "INSERT INTO donor_details(donor_name,donor_number,donor_mail,donor_age,donor_aadhar,donor_gender,donor_blood,donor_address) values('{$name}','{$number}','{$email}','{$age}','{$aadhar}','{$gender}','{$blood_group}','{$address}')";
$result=mysqli_query($conn,$sql) or die("query unsuccessful.");

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

if($result=TRUE)
{

//Load Composer's autoloader
require 'PHPMailer/Exception.php';
require 'PHPMailer/PHPMailer.php';
require 'PHPMailer/SMTP.php';

//Create an instance; passing `true` enables exceptions
$mail = new PHPMailer(true);

try {
    //Server settings
    $mail->isSMTP();                                            //Send using SMTP
    $mail->Host       = 'smtp.gmail.com';                     //Set the SMTP server to send through
    $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
    $mail->Username   = 'jnishank123@gmail.com';                     //SMTP username
    $mail->Password   = 'leon uxwr rgwz wtva';                               //SMTP password
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
    $mail->Port       = 465;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

    //Recipients
    $mail->setFrom('jnishank123@gmail.com', 'donate_blood');
    $mail->addAddress('jnishank123@gmail.com', 'BBMS');     //Add a recipient

    //Content
    $mail->isHTML(true);                                  //Set email format to HTML
    $mail->Subject = 'Submit of details';
    $mail->Body    = "Name - $name <br> mobile no. $number <br> email- $email <br> aadhar - $aadhar";

    $mail->send();
    echo 'Message has been sent';
} catch (Exception $e) {
    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
}
}
header("Location: http://localhost/BDMS/home.php");
mysqli_close($conn);
 ?>
