$(function(){
	var $container = $('.senderfields.sender-phpmailer');
	$('select[name=phpmailer_smtp_enable]', $container).on('change', function(){
		var $val = parseInt($(this).val());
		$('input[name=phpmailer_smtp_hostname],input[name=phpmailer_smtp_port],input[name=phpmailer_smtp_username],input[name=phpmailer_smtp_password]', $container).each(function(){
			if($val == 1){
				$(this).parents('.form-group').show();
			}else{
				$(this).parents('.form-group').hide();
			}
		});
	});
	$('select[name=phpmailer_smtp_enable]', $container).trigger('change');
})
