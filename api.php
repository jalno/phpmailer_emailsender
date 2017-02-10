<?php
namespace packages\phpmailer_emailsender;
use \packages\email\sent;
use \packages\email\sender;
use \packages\email\sender\handler;
use \packages\phpmailer_emailsender\PHPMailer;
class api extends handler{
	private $sender;
	public function __construct(sender $sender){
		$this->sender = $sender;
	}
	public function send(sent $email){
		$mailer = new PHPMailer();
		if($this->sender->param('phpmailer_smtp_enable')){
			$mailer->isSMTP();
			$mailer->Hostname = $this->sender->param('phpmailer_smtp_hostname');
			$mailer->Host = $this->sender->param('phpmailer_smtp_hostname');
			$mailer->Port = $this->sender->param('phpmailer_smtp_port');
			switch($mailer->Port){
				case(465):$mailer->SMTPSecure = 'ssl';break;
				case(587):$mailer->SMTPSecure = 'tls';break;
				default:$mailer->SMTPSecure = '';break;
			}
			$username = $this->sender->param('phpmailer_smtp_username');
			$password = $this->sender->param('phpmailer_smtp_password');
			if($username or $password){
				$mailer->SMTPAuth = true;
				$mailer->Username = $username;
				$mailer->Password = $password;
			}
		}
		$mailer->CharSet = 'utf-8';
		$mailer->setFrom($email->sender_address->address, $email->sender_address->name);
		$mailer->addAddress($email->receiver_address, $email->receiver_name);
		$mailer->Subject = $email->subject;
		$mailer->msgHTML($email->html);
		$mailer->AltBody = $email->text;
		foreach($email->attachments as $attachment){
			$mailer->addAttachment($attachment->file, $attachment->name);
		}
		return $mailer->send() ? sent::sent : sent::failed;
	}
}
