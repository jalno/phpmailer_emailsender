import * as $ from "jquery";
export class Senders{
	private static $container = $('.senderfields.sender-phpmailer');
	private static runSMTPListener(){
		$('select[name=phpmailer_smtp_enable]', Senders.$container).on('change', function(){
			let $val = parseInt($(this).val());
			$('input[name=phpmailer_smtp_hostname],input[name=phpmailer_smtp_port],input[name=phpmailer_smtp_username],input[name=phpmailer_smtp_password]', Senders.$container).each(function(){
				if($val == 1){
					$(this).parents('.form-group').show();
				}else{
					$(this).parents('.form-group').hide();
				}
			});
		});
		$('select[name=phpmailer_smtp_enable]', Senders.$container).trigger('change');
	}
	public static init(){
		Senders.runSMTPListener();
	}
	public static initIfNeeded(){
		if(Senders.$container.length){
			Senders.init();
		}
	}
}
