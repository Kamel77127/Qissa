<?php

namespace App\Core\PHPMailer;


use App\Core\PHPMailer\src\PHPMailer;

class Mailer
{

     static private string $SMTP_HOST = '';
     static private string $SMTP_PORT = '';
     static private string $SMTP_USERNAME = '';
     static private string $SMTP_PASSWORD = '';
     static private string $SMTP_FROM = '';
     static private string $SMTP_NAME = '';



    public function __construct(array $config)
    {
        self::$SMTP_HOST = $config['host'];
        self::$SMTP_PORT = $config['port'];
        self::$SMTP_USERNAME = $config['username'];
        self::$SMTP_PASSWORD = $config['password'];
        self::$SMTP_FROM = $config['from'];
        self::$SMTP_NAME = $config['name'];
    }


    public function sendMail($to , $toName , $subj , $msg)
    {

        $mail = new PHPMailer(true);
        //Server settings
        // $mail->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
        $mail->isSMTP();                                            //Send using SMTP
        $mail->Host       = self::$SMTP_HOST;                     //Set the SMTP server to send through
        $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
        $mail->Username   = self::$SMTP_USERNAME;                     //SMTP username
        $mail->Password   = self::$SMTP_PASSWORD;                               //SMTP password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
        $mail->Port       = self::$SMTP_PORT;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

        //Recipients
        $mail->setFrom(self::$SMTP_FROM, self::$SMTP_NAME);
        $mail->addAddress($to, $toName);     //Add a recipient



        //Content
        $mail->isHTML(true);                                  //Set email format to HTML
        $mail->Subject = $subj;
        $mail->Body    = $msg;


        if ($mail->send())
        {
            return true;
        }else {
            return false;
        }



    }
}