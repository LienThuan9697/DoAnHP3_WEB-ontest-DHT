<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require 'vendor/phpmailer/phpmailer/src/Exception.php';
require 'vendor/phpmailer/phpmailer/src/PHPMailer.php';
require 'vendor/phpmailer/phpmailer/src/SMTP.php';

class MailAuth extends DB
{
    protected $mail;

    public function __construct()
    {
        parent::__construct();
        $this->mail = new PHPMailer(true);

        $this->mail->CharSet = 'UTF-8';
        $this->mail->SMTPDebug = SMTP::DEBUG_OFF; // Có thể bật DEBUG_SERVER để debug chi tiết
        $this->mail->isSMTP();
        $this->mail->Host = 'smtp.gmail.com';
        $this->mail->SMTPAuth = true;
        $this->mail->Username = 'ontestdht@gmail.com';
        $this->mail->Password = str_replace(' ', '', 'peuhdht ehtcoodlp'); // App Password
        $this->mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $this->mail->Port = 587;
        $this->mail->setFrom('ontestdht@gmail.com', 'DHT OnTest');
    }

    public function sendOpt($email, $opt)
    {
        try {
            $this->mail->clearAddresses();
            $this->mail->addAddress($email);
            $this->mail->isHTML(true);

            $this->mail->Subject = mb_encode_mimeheader('Mã xác thực OTP của bạn', 'UTF-8');

            $this->mail->Body = '
                <div style="font-family: Arial, sans-serif; max-width: 500px; margin: auto; padding: 20px; border: 1px solid #ddd; border-radius: 8px;">
                    <div style="text-align: center; margin-bottom: 20px;">
                        <h2 style="color: #115e59; margin: 0;">DHT OnTest</h2>
                        <p style="color: #555; font-size: 14px; margin-top: 4px;">Xác thực tài khoản</p>
                    </div>
                    <p style="font-size: 15px; color: #333;">Xin chào,</p>
                    <p style="font-size: 15px; color: #333;">Bạn vừa yêu cầu mã xác thực OTP. Vui lòng sử dụng mã bên dưới trong vòng <strong style="color: #b91c1c;">5 phút</strong> :</p>
                    <div style="text-align: center; margin: 25px 0;">
                        <span style="display: inline-block; background-color: #00466a; color: #fff; font-size: 24px; padding: 12px 24px; border-radius: 6px; letter-spacing: 4px;">' . $opt . '</span>
                    </div>
                    <p style="font-size: 14px; color: #555;">Nếu bạn không yêu cầu điều này, vui lòng bỏ qua email.</p>
                    <hr style="border: none; border-top: 1px solid #eee; margin: 30px 0;" />
                    <div style="text-align: center; font-size: 12px; color: #999;">
                        <p>OnTest DHT - Nền tảng thi trực tuyến</p>
                        <p>Vui lòng không trả lời email này.</p>
                    </div>
                </div>
            ';

            $this->mail->AltBody = 'Mã OTP của bạn: ' . $opt;

            $this->mail->send();
            error_log("Email sent successfully to: $email with OTP: $opt"); // Ghi log để debug
            return true;
        } catch (Exception $e) {
            error_log("Failed to send email to: $email. Error: {$this->mail->ErrorInfo}");
            return false;
        }
    }
}
