<?php

namespace App\Services;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class MailService
{
    private $mail;

    public function __construct()
    {
        $this->mail = new PHPMailer(true);
        $this->setupMailer();
    }

    private function setupMailer()
    {
        $this->mail->isSMTP();
        $this->mail->Host = 'smtp.gmail.com';
        $this->mail->SMTPAuth = true;
        $this->mail->Username = 'ect.uagrm@gmail.com';
        $this->mail->Password = 'zorenzjqzwuaxwdb';
        $this->mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        $this->mail->Port = 465;
        $this->mail->setFrom('ect.uagrm@gmail.com', 'Panadería Otto');
        $this->mail->CharSet = 'UTF-8';
    }

    public function sendEmail($to, $subject, $body, $attachments = [])
    {
        try {
            $this->mail->clearAddresses();
            $this->mail->addAddress($to);
            $this->mail->Subject = $subject;
            $this->mail->Body = $body;
            $this->mail->isHTML(true);

            foreach ($attachments as $file) {
                if (file_exists($file)) {
                    $this->mail->addAttachment($file);
                }
            }

            return $this->mail->send();
        } catch (Exception $e) {
            \Log::error('Mailer Error: ' . $this->mail->ErrorInfo);
            return false;
        }
    }
}