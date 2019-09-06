<?php
namespace packages\phpmailer_emailsender;

use packages\email\{Sender, Sent};
use packages\phpmailer_emailsender\PHPMailer;

class API extends Sender\handler {

	/**
	 * @var Sender
	 */
	private $sender;

	public function __construct(Sender $sender) {
		$this->sender = $sender;
	}

	public function send(Sent $email) {
		$mailer = new PHPMailer();
		if ($this->sender->param('phpmailer_smtp_enable')) {
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

		$inlines = [];

		if (preg_match_all("/cid:([\w,\-_\.]+)/", $email->html, $matches)) {
			foreach ($matches[1] as $inline) {
				if (!in_array($inline, $inlines)) {
					$inlines[] = $inline;
				}
			}
		}

		foreach ($email->attachments as $attachment) {
			if (in_array($attachment->name, $inlines)) {
				$mailer->addEmbeddedImage($attachment->file, $attachment->name);
			} else {
				$mailer->addAttachment($attachment->file, $attachment->name);
			}
		}

		$mailer->msgHTML($email->html);
		$mailer->AltBody = $email->text;
		return $mailer->send() ? Sent::sent : Sent::failed;
	}
}
