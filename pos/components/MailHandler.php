<?php
	// These must be at the top of your script, not inside a function
	// use PHPMailer\PHPMailer\PHPMailer;
	// use PHPMailer\PHPMailer\Exception;

	// require 'vendor/autoload.php';
	/*
	use PHPMailer\PHPMailer\PHPMailer;
	use PHPMailer\PHPMailer\Exception;

	require Config::getRootDirectory().'mailer/PHPMailer/src/Exception.php';
	require Config::getRootDirectory().'mailer/PHPMailer/src/PHPMailer.php';
	require Config::getRootDirectory().'mailer/PHPMailer/src/SMTP.php';

	class MailHandler {
		public $host = 'mail.pasarbumclass.com';
	    public $smtp_auth = true;
	    public $username = 'admin@pasarbumclass.com';
	    public $password = 'Pasarbum-@)!*';
	    public $smtp_secure = 'ssl';
	    public $port = 587; // 587 or 110
	    public $mail_type; // on-going or in-coming

	    public function __construct($mail_type) {
		    $this->mail_type = $mail_type;
		    $this->setPort();
		}

		public function init() {
			$mail = new PHPMailer(true);
			$mail->Host 	= $this->host;
		    $mail->SMTPAuth = $this->smtp_auth;
		    $mail->Username = $this->username;
		    $mail->Password = $this->password;
		    // $mail->SMTPSecure = $this->smtp_secure;
		    $mail->Port 	= $this->port;

		    if($this->mail_type == 'on-going') {
		    	$mail->isSMTP();
		    }

		    return $mail;
		}

		private function setPort() {
			if($this->mail_type == 'on-going') {
				$this->port = 587;
			} else {
				$this->port = 110;
			}
		}
	}
	*/