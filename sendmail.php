<?php
use PHPMailer\PHPMailer\PHPMailer;

require_once 'phpmailer/Exception.php';
require_once 'phpmailer/PHPMailer.php';
require_once 'phpmailer/SMTP.php';

$mail = new PHPMailer(true);

$alert = '';
session_start();

$EMAIL=isset($_POST['email'])?$_POST['email']:"";

if(isset($_POST['CLOSE'])){

  #$email = $_POST['EMAIL'];


  try{
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'samarthweb02@gmail.com'; // Gmail address which you want to use as SMTP server
    $mail->Password = 'qsrs$hri98'; // Gmail address Password
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = '587';

    $mail->setFrom('samarthweb02@gmail.com'); // Gmail address which you used as SMTP server
    $mail->addAddress($EMAIL); // Email address where you want to receive emails (you can use any of your gmail address including the gmail address which you used as SMTP server)

    $mail->isHTML(true);
    $mail->Subject = 'Samarth Distributers Book List Download.';
    $mail->Body ="<h3>Books list </h3><br><br>All books are availavle in this Drive.<br>
    https://drive.google.com/drive/folders/12p7K-wlFnj22Pubd0EKKhB7Y1P_qDiX_?usp=sharing <br><br><br>Thank You & Regards<br>Smarth Distributers." ;

    $mail->send();
    $alert = '<div class="alert-success">
                 <span>Message Sent! Thank you for contacting us.</span>
                </div>';
				      header("Location: http://localhost/project/list.html");
  } catch (Exception $e){
    $alert = '<div class="alert-error">
                <span>'.$e->getMessage().'</span>
              </div>';
  }
}
?>
      