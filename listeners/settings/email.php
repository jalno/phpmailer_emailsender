<?php
namespace packages\phpmailer_emailsender\listeners\settings;
use \packages\base;
use \packages\base\translator;
use \packages\base\event;
use \packages\base\frontend\theme;
use \packages\base\packages;
use \packages\base\inputValidation;
use \packages\email\events\senders;
use \packages\email\views\settings\senders as senderViews;
class email{
	public function senders_list(senders $senders){
		$sender = new senders\sender("phpmailer");
		$sender->setHandler('\\packages\\phpmailer_emailsender\\api');
		$sender->addInput(array(
			'name' => 'phpmailer_smtp_enable',
			'type' => 'bool',
			'empty' => true
		));
		$sender->addInput(array(
			'name' => 'phpmailer_smtp_hostname',
			'type' => 'string',
			'empty' => true,
			'optional' => true
		));
		$sender->addInput(array(
			'name' => 'phpmailer_smtp_port',
			'type' => 'number',
			'empty' => true,
			'optional' => true
		));
		$sender->addInput(array(
			'name' => 'phpmailer_smtp_username',
			'type' => 'string',
			'empty' => true,
			'optional' => true
		));
		$sender->addInput(array(
			'name' => 'phpmailer_smtp_password',
			'type' => 'string',
			'empty' => true,
			'optional' => true
		));
		$sender->addInput(array(
			'name' => 'phpmailer_smtp_secure',
			'type' => 'string',
			'empty' => true,
			'optional' => true,
			'values' => ['', 'tls', 'ssl'],
		));
		$sender->addField(array(
			'type' => 'select',
			'name' => 'phpmailer_smtp_enable',
			'label' => t('settings.email.senders.phpmailer.smtp.enable'),
			'options' => array(
				array(
					'value' => 1,
					'title' => t('settings.email.senders.phpmailer.smtp.enable.yes')
				),
				array(
					'value' => 0,
					'title' => t('settings.email.senders.phpmailer.smtp.enable.no')
				)
			)
		));
		$sender->addField(array(
			'name' => 'phpmailer_smtp_hostname',
			'label' => t('settings.email.senders.phpmailer.smtp.hostname'),
			'ltr' => true
		));
		$sender->addField(array(
			'type' => 'number',
			'name' => 'phpmailer_smtp_port',
			'label' => t('settings.email.senders.phpmailer.smtp.port'),
			'ltr' => true
		));
		$sender->addField(array(
			'name' => 'phpmailer_smtp_username',
			'label' => t('settings.email.senders.phpmailer.smtp.username'),
			'ltr' => true
		));
		$sender->addField(array(
			'type' => 'password',
			'name' => 'phpmailer_smtp_password',
			'label' => t('settings.email.senders.phpmailer.smtp.password'),
			'ltr' => true
		));
		$sender->addField(array(
			'type' => 'select',
			'name' => 'phpmailer_smtp_secure',
			'label' => t('settings.email.senders.phpmailer.smtp.secure'),
			'ltr' => true,
			'options' => array(
				array(
					'value' => '',
					'title' => t('settings.email.senders.phpmailer.smtp.secure.none')
				),
				array(
					'value' => 'ssl',
					'title' => t('settings.email.senders.phpmailer.smtp.secure.ssl')
				),
				array(
					'value' => 'tls',
					'title' => t('settings.email.senders.phpmailer.smtp.secure.tls')
				),
			)
		));
		$sender->setController(__CLASS__.'@validate');
		$senders->addSender($sender);
	}
	public function validate($inputs){
		if($inputs['phpmailer_smtp_enable']){
			foreach(array('hostname', 'port') as $input){
				if(!isset($inputs['phpmailer_smtp_'.$input]) or !$inputs['phpmailer_smtp_'.$input]){
					throw new inputValidation('phpmailer_smtp_'.$input);
				}
			}
			if($inputs['phpmailer_smtp_port'] < 1 and $inputs['phpmailer_smtp_port'] > 65535){
				throw new inputValidation('phpmailer_smtp_port');
			}
		}
	}
	public function senders_addAssets(event $event){
		$view = $event->getView();
		if($view instanceof senderViews\add or $view instanceof senderViews\edit){
			$view->addJSFile(packages::package('phpmailer_emailsender')->url('frontend/assets/js/pages/senders.js'));
		}
	}
}
