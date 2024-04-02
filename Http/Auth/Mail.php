<?php

namespace App\Http\Auth;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
use App\Http\Request;

class Mail {
	private array $server;
	private string $smtp_host;
	private int $smtp_port;
	private string $smtp_username;
	private string $smtp_password;
	private string $smtp_from_email;

	public const string PASSWORD_RESET_EMAIL_SUBJECT = "Password Reset";

	public function __construct() {
		$this->server = Request::server();
		$this->smtp_host = $this->server['SMTP_HOST'];
		$this->smtp_port = $this->server['SMTP_PORT'];
		$this->smtp_username = $this->server['SMTP_USERNAME'];
		$this->smtp_password = $this->server['SMTP_PASSWORD'];
		$this->smtp_from_email = $this->server['SMTP_FROM_EMAIL'];
	}

	public function setup(bool $is_html = true) {
		$mail = new PHPMailer();
		$mail->IsSMTP();
		$mail->SMTPAuth = true;
		$mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
		$mail->Host = $this->smtp_host;
		$mail->Port = $this->smtp_port;
		$mail->Username = $this->smtp_username;
		$mail->Password = $this->smtp_password;
		$mail->setFrom($this->smtp_username);
		$mail->isHtml($is_html);
		return $mail;
	}

	public function sendMail(PHPMailer $mail, string $url) {
		$html = file_get_contents(BASE_PATH . "/views/users/password_recovery/mail.view.php");
		$html = str_replace("{{URL}}", $url, $html);
		$mail->Body = $html;
		try {
			$mail->send();
		} catch (Exception $e) {

		}
	}
}
