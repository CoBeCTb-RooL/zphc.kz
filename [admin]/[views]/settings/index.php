<?php
//vd($MODEL);
//vd($_CONFIG['SETTINGS']);
?>




<script>
function getValuesFromYahoo()
{
	$.ajax({
		url: '<?=Currency::CURRENCY_SITE?>',
		data: '',
		type: "get",
		dataType: 'json',
		beforeSend: function(){$.fancybox.showLoading()},
		success: function(data){
			
			var tg = data.query.results.rate[0].Ask;
			var rub = data.query.results.rate[1].Ask

			tg = parseFloat(tg) + parseFloat(<?=$_CONFIG['SETTINGS']['exchange_increment_'.Currency::KZT]?>);
			rub = parseFloat(rub) + parseFloat(<?=$_CONFIG['SETTINGS']['exchange_increment_'.Currency::RUR]?>);
			//alert(tg)
			//alert(rub)
			//tg=0
			errors = []
			if(tg > 0 )
				$('#<?=Currency::KZT?>').val(tg)
			else
				errors.push({'field': "<?=Currency::KZT?>", "error": "Не удалось получить корректную валюту <b><?=Currency::KZT?></b>"})

			if(rub > 0 )
				$('#<?=Currency::RUR?>').val(rub)
			else
				errors.push({'field': "<?=Currency::RUR?>", "error": "Не удалось получить корректную валюту <b><?=Currency::RUR?></b>"})

			if(errors.length == 0)
				notice('Данные успешно получены. Нажмите <b>Сохранить</b>, чтобы они были зафиксированы.')
			else
				showErrors(errors)
				
		},
		error: function(e){alert('Возникла ошибка на сервере... Попробуйте позже.')},
		complete: function(){$.fancybox.hideLoading()}
	});
}
</script>




<h1><span class="fa fa-sliders"></span> Настройки сайта</h1>


<?php if($MODEL['error']){ echo ''.$MODEL['error'].''; return; }?>







<form class="settings" method="post" action="">
	<fieldset>
		<legend>Заголовок сайта</legend>
		
		<div class="row">
			<span class="label">Название сайта в заголовке:</span>
			<span class="input"><input type="text" name="title_postfix" size="40" value="<?=addslashes($_CONFIG['SETTINGS']['title_postfix'])?>"></span>
			<div class="hint">Будет всегда добавляться после указанного в контроллере заголовка.</div>
		</div>
		
		<div class="row">
			<span class="label">Разделитель частей заголовка:</span>
			<span class="input"><input type="text" name="title_separator" size="2" value="<?=addslashes($_CONFIG['SETTINGS']['title_separator'])?>"></span>
			<div class="hint">Символ, разделяющий части заголовка (например "Новости - abc.kz")</div>
		</div>
	</fieldset>
	
	
	
	
	<fieldset>
		<legend>Мета-теги</legend>
		
		<div class="row">
			<span class="label">Description:</span>
			<span class="input"><textarea name="description"><?=$_CONFIG['SETTINGS']['description']?></textarea></span>
		</div>
		
		<div class="row">
			<span class="label">Keywords:</span>
			<span class="input"><textarea name="keywords"><?=$_CONFIG['SETTINGS']['keywords']?></textarea></span>
		</div>
	</fieldset>
	
	
	
	
	<fieldset>
		<legend>Курсы валют</legend>
		
		<div class="row">
			<span class="label">1 USD = </span>
			<span class="input"><input name="<?=Currency::KZT?>" id="<?=Currency::KZT?>" value="<?=$_CONFIG['SETTINGS'][Currency::KZT]?>" size="6"> <?=Currency::KZT?></span>
		</div>
		
		<div class="row">
			<span class="label">1 USD = </span>
			<span class="input"><input name="<?=Currency::RUR?>" id="<?=Currency::RUR?>" value="<?=$_CONFIG['SETTINGS'][Currency::RUR]?>" size="6"> <?=Currency::RUR?></span>
		</div>
		
		<button type="button" onclick="getValuesFromYahoo()">Получить свежие значения с Yahoo</button>
		
		<p></p>
		<hr />
		<h4>Прирост к курсу</h4>
		<div class="row">
			<span class="label"></span>
			<span class="input">+ <input name="exchange_increment_<?=Currency::KZT?>"  value="<?=$_CONFIG['SETTINGS']['exchange_increment_'.Currency::KZT]?>" size="3"> тг за 1 $</span>
		</div>
		<div class="row">
			<span class="label"></span>
			<span class="input">+ <input name="exchange_increment_<?=Currency::RUR?>"  value="<?=$_CONFIG['SETTINGS']['exchange_increment_'.Currency::RUR]?>" size="3"> руб. за 1 $</span>
		</div>
		
	</fieldset>
	
	
	<fieldset>
		<legend>Денежные реквизиты</legend>
		
		<div class="row">
			<span class="label">Яндекс.Деньги: </span>
			<span class="input"><input name="yandex_money" value="<?=$_CONFIG['SETTINGS']['yandex_money']?>" ></span>
		</div>
		
		<div class="row">
			<span class="label">Qiwi: </span>
			<span class="input"><input name="qiwi" value="<?=$_CONFIG['SETTINGS']['qiwi']?>" ></span>
		</div>
		
		<div class="row">
			<span class="label">Web-money: </span>
			<span class="input"><input name="web_money" value="<?=$_CONFIG['SETTINGS']['web_money']?>" ></span>
		</div>
		
		<div class="row">
			<span class="label">VISA / Master Card: </span>
			<span class="input"><input name="visa" value="<?=$_CONFIG['SETTINGS']['visa']?>" ></span>
		</div>
		
	</fieldset>
	
	
	
	
	<fieldset>
		<legend>Стоимость доставки</legend>
		
		<div class="row">
			<span class="label">Стоимость доставки</span>
			<span class="input"><input name="delivery_cost" value="<?=$_CONFIG['SETTINGS']['delivery_cost']?>" size="4"> $</span>
		</div>
		
		Доставка <b>БЕСПЛАТНА</b>, если сумма заказа >= <input name="order_sum_for_free_delivery" value="<?=$_CONFIG['SETTINGS']['order_sum_for_free_delivery']?>" size="4"> $
		
	</fieldset>
	
	
	<fieldset>
		<legend>Поощрение реферера</legend>
		<div class="row">
			<span class="label">Бонус за указанный в заказе номер: </span>
			<span class="input"><input name="refererPrize" value="<?=$_CONFIG['SETTINGS']['refererPrize']?>" size="4"> $</span>
		</div>
		
	</fieldset>
	
	
	
	
	
	<fieldset>
		<legend>Социалки</legend>
		
		<div>Каждая ссылка должна начинаться с <b>http://</b></div>
		
		<div class="row">
			<span class="label"><i aria-hidden="true" class="fa fa-twitter" style="font-size: 2.1em; "></i>:  </span>
			<span class="input" style="width: 300px; padding: 6px 0 0 0; "><input name="twitter" value="<?=$_CONFIG['SETTINGS']['twitter']?>"  style="width: 100%; "/></span>
		</div>
		
		<div class="row">
			<span class="label"><i aria-hidden="true" class="fa fa-facebook" style="font-size: 2.1em; "></i>:  </span>
			<span class="input" style="width: 300px; padding: 6px 0 0 0; "><input name="facebook" value="<?=$_CONFIG['SETTINGS']['facebook']?>"  style="width: 100%; "/></span>
		</div>
		
		<div class="row">
			<span class="label"><i aria-hidden="true" class="fa fa-vk" style="font-size: 2.1em; "></i>:  </span>
			<span class="input" style="width: 300px; padding: 6px 0 0 0; "><input name="vk" value="<?=$_CONFIG['SETTINGS']['vk']?>"  style="width: 100%; "/></span>
		</div>
		
		<div class="row">
			<span class="label"><i aria-hidden="true" class="fa fa-instagram" style="font-size: 2.1em; "></i>:  </span>
			<span class="input" style="width: 300px; padding: 6px 0 0 0; "><input name="instagram" value="<?=$_CONFIG['SETTINGS']['instagram']?>"  style="width: 100%; "/></span>
		</div>
		
		<div class="row">
			<span class="label"><i aria-hidden="true" class="fa fa-youtube" style="font-size: 2.1em; "></i>:  </span>
			<span class="input" style="width: 300px; padding: 6px 0 0 0; "><input name="youtube" value="<?=$_CONFIG['SETTINGS']['youtube']?>"  style="width: 100%; "/></span>
		</div>
		
	</fieldset>
	
	
	
	<fieldset>
		<legend>Контакты - информация</legend>
		
		<div class="row">
			<span class="label">Телефон:</span>
			<span class="input"><input type="text" name="contactPhone" size="40" value="<?=addslashes($_CONFIG['SETTINGS']['contactPhone'])?>"></span>
		</div>
		<div class="row">
			<span class="label">E-mail:</span>
			<span class="input"><input type="text" name="contactEmail" size="40" value="<?=addslashes($_CONFIG['SETTINGS']['contactEmail'])?>"></span>
		</div>
		<div class="row">
			<span class="label">WhatsApp:</span>
			<span class="input"><input type="text" name="contactWhatsApp" size="40" value="<?=addslashes($_CONFIG['SETTINGS']['contactWhatsApp'])?>"></span>
		</div>
		
	</fieldset>
	
	
	
	
	
	<input type="submit" name="go_btn" value="Сохранить">
	
	
	
</form>