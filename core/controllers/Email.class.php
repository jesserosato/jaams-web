<?php
namespace JAAMS\Core\Controllers;

require_once('Base.class.php');
require_once(\JAAMS\VENDOR_ROOT.'/phpmailer/class.phpmailer.php');

class Email extends Base {
	protected $data			= array();
	protected $to			= array();
	protected $from			= \Application\SITE_ADMIN_EMAIL;
	protected $from_name	= \Application\SITE_ADMIN_EMAIL_FROM_NAME;
	protected $subject		= '';
	protected $message		= '';
	protected $alt_message	= '';
	protected $headers		= array();
	protected $is_html		= true;
	protected $smtp_info	= array(
		'host'		=> \Application\SMTP_HOST,
		'auth'		=> \Application\SMTP_AUTH,
		'username'	=> \Application\SMTP_USERNAME,
		'password'	=> \Application\SMTP_PASSWORD,
	);

	public function __construct( array $paths, array $smtp_info = array(), array $data = array() ) {
		parent::__construct($paths);
		$this->smtp_info	= array_merge($this->smtp_info, $smtp_info);
		$this->data			= $data;	
	}
	
	public function send() {
		$message = $this->get_message();
	
		$mail = new \PHPMailer();
		$mail->Host			= $this->smtp_info['host'];
		$mail->SMTPAuth		= $this->smtp_info['auth'];
		$mail->Username		= $this->smtp_info['username'];
		$mail->Password		= $this->smtp_info['password'];
		
		$mail->From			= $this->from;
		$mail->FromName		= $this->from_name;
		foreach ( $this->to as $to ) {
			$mail->AddAddress($to);
		}
		$mail->AltBody		= $this->alt_message;
		$mail->IsHtml($this->is_html);
		$mail->Subject		= $this->subject;
		$mail->Body			= $message;
		
		return $mail->Send();
	}
	
	public function get_message() {
		if ( $this->message ) {
			return $this->message;
		} else {
			$this->set_view();
			return $this->view;
		}
	}
	
	public function get_template_data() {
		return $this->data;
	}
}