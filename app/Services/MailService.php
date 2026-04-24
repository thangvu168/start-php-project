<?php

use PHPMailer\PHPMailer\PHPMailer;

class MailService
{
    public function send(string $to, string $subject, string $body): bool
    {
        try {
            $mailEnv = config('mail');

            $mail = new PHPMailer(true);
            $mail->isSMTP();
            // Ensure UTF-8
            $mail->CharSet = 'UTF-8';
            $mail->Encoding = 'base64';
            if (!empty($mailEnv['host'])) {
                $mail->Host = $mailEnv['host'];
                $mail->SMTPAuth = true;
                $mail->Username = $mailEnv['username'];
                $mail->Password = $mailEnv['password'];
                $mail->SMTPSecure = 'tls';
                $mail->Port = $mailEnv['port'];
            }
            $mail->setFrom($mailEnv['from'], $mailEnv['from_name']);
            $mail->addAddress($to);
            $mail->isHTML(true);
            $mail->Subject = $subject;
            $mail->Body = $body;
            return $mail->send();
        } catch (Exception $e) {
            error_log('[MAIL ERROR] ' . $e->getMessage());
            // fallthrough to PHP mail fallback
        }

        // Fallback to PHP mail()
        // Ensure headers declare UTF-8 and encode subject properly
        $headers = "MIME-Version: 1.0\r\n";
        $headers .= "Content-type: text/html; charset=UTF-8\r\n";
        $headers .= "Content-Transfer-Encoding: 8bit\r\n";
        $headers .= "From: " . $mailEnv['from_name'] . " <" . $mailEnv['from'] . ">\r\n";

        // Encode subject for UTF-8
        if (function_exists('mb_encode_mimeheader')) {
            $encodedSubject = mb_encode_mimeheader($subject, 'UTF-8', 'B', '\r\n');
        } else {
            $encodedSubject = '=?UTF-8?B?' . base64_encode($subject) . '?=';
        }

        return @mail($to, $encodedSubject, $body, $headers);
    }
}
