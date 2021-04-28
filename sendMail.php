<?php
use PHPMailer\PHPMailer\PHPMailer;

if(isset($_POST['name']) && isset($_POST['email'])){
    $name = $_POST['name'];
    $email = $_POST['email'];
    $subject = $_POST['subject'];
    $body = $_POST['body'];

    require_once "PHPMailer/PHPMailer.php";
    require_once "PHPMailer/SMTP.php";
    require_once "PHPMailer/Exception.php";

    $mail = new PHPMailer();

    //smtp settings
    $mail->isSMTP();
    $mail->Host = "smtp.gmail.com";
    $mail->SMTPAuth = true;
    $mail->Username = "oncodeproject@gmail.com";
    $mail->Password = 'QwertyBUX%toR<%>';
    $mail->Port = 465;
    $mail->SMTPSecure = "ssl";

    //email settings
    $myEmail = "oncodeproject@gmail.com";
    $mail->isHTML(true);
    $mail->setFrom($myEmail);              // sender's address
    $mail->addAddress($email);                  // receiver's address
    $mail->Subject = ($subject);
    $mail->Body = $body;

    if($mail->send()){
        $status = "success";
        $response = "Email is sent!";
    }
    else
    {
        $status = "failed";
        $response = "Something is wrong: <br>" . $mail->ErrorInfo;
    }

    exit(json_encode(array("status" => $status, "response" => $response)));
}

?>
      