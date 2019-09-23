<?php
$text = $MODEL['text']
?>



<script>
function sendMsg()
{
	var f = $('#contacts-form')
	f.find('*').removeClass('field-error')
	f.find('.info').css('display', 'none')
	
	var errors=[]
	
	var name = f.find('input[name=name]').val()
	var email = f.find('input[name=email]').val()
	var subject = f.find('input[name=subject]').val()
	var msg = f.find('[name=msg]').val()

	if(name == '')
		errors.push({'field': 'name', 'error':'Введите Ваше <b>имя</b>'})
	if(email == '')
		errors.push({'field': 'email', 'error':'Введите <b>e-mail</b>'})
	if(subject == '')
		errors.push({'field': 'subject', 'error':'Введите <b>тему сообщения</b>'})
	if(msg == '')
		errors.push({'field': 'msg', 'error':'Введите <b>Сообщение</b>'})

		//alert(errors.length)
	if(errors.length > 0 )
		showContactFormErrors(errors)
	else
	{
		$.ajax({
			url: '/contacts/sendMsg',
			data: 	 'name='+encodeURIComponent(name)+'&'
					+'email='+encodeURIComponent(email)+'&'
					+'subject='+encodeURIComponent(subject)+'&'
					+'msg='+encodeURIComponent(msg)+'&'
					,
			dataType: 'json',
			beforeSend: function(){$.fancybox.showLoading(); },
			success: function(data){
				if(data.errors)
				{
					showContactFormErrors(data.errors)
				}
				else
				{
					$('#contacts-form').slideUp()
					$('#contacts-form + .success1').slideDown()

					$('html, body').animate({scrollTop: $('body').offset().top}, 500);
				}	
			},
			error: function(e){alert('Возникла ошибка на сервере...Пожалуйста, попробуйте позднее.')},
			complete: function(){$.fancybox.hideLoading();}
		});
	}
}


function showContactFormErrors(errors)
{
	$('#contacts-form .info').html(getErrorsString(errors)).slideDown('fast')
}
</script>





<!--крамбсы-->
<? Core::renderPartial(SHARED_VIEWS_DIR.'/crumbs.php', $MODEL['crumbs']);?>
<!--//крамбсы-->


<div class="contacts">
	<h1>Контакты</h1>

	<div class="text"><?=$text->attrs['descr']?></div>
	
	
	<div class="info">
	
		<div class="kol">
			<div class="lbl">Сотрудничество:</div>
			<div class="val"><?=$_CONFIG['SETTINGS']['contactPhone']?></div>
		</div>
		<div class="kol">
			<div class="lbl">E-mail:</div>
			<div class="val"><a href="mailto:<?=$_CONFIG['SETTINGS']['contactEmail']?>"><?=$_CONFIG['SETTINGS']['contactEmail']?></a></div>
		</div>
		<div class="kol">
			<div class="lbl">WhatsApp:</div>
			<div class="val"><?=$_CONFIG['SETTINGS']['contactWhatsApp']?></div>
		</div>
		<div class="kol">
			<div class="lbl">Подписывайся:</div>
			<div class="val social">
				<!--<a href="<?=$_CONFIG['SETTINGS']['twitter']?>" class="soc-btn soc-twitter" title="Twitter" target="_blank"><i class="fa fa-twitter" aria-hidden="true"></i></a>
            	<a href="<?=$_CONFIG['SETTINGS']['facebook']?>" class="soc-btn soc-facebook" title="Facebook" target="_blank"><i class="fa fa-facebook" aria-hidden="true"></i></a>
            	<a href="<?=$_CONFIG['SETTINGS']['vk']?>" class="soc-btn soc-vk" title="ВКонтакте" target="_blank"><i class="fa fa-vk" aria-hidden="true"></i></a>
            	<a href="<?=$_CONFIG['SETTINGS']['instagram']?>" class="soc-btn soc-insta" title="Instagram" target="_blank"><i class="fa fa-instagram" aria-hidden="true"></i></a>-->
            	
            	<a href="<?=$_CONFIG['SETTINGS']['youtube']?>" class="soc-btn soc-youtube" title="YouTube" target="_blank"><img src="/img/soc-youtube.png" alt="" /></a>
            			<a href="<?=$_CONFIG['SETTINGS']['vk']?>" class="soc-btn soc-vk" title="ВКонтакте" target="_blank"><img src="/img/soc-vk.png" alt="" /></a>
            			<a href="<?=$_CONFIG['SETTINGS']['facebook']?>" class="soc-btn soc-facebook" title="Facebook" target="_blank"><img src="/img/soc-facebook.png" alt="" /></a>
            			<a href="<?=$_CONFIG['SETTINGS']['instagram']?>" class="soc-btn soc-insta" title="Instagram" target="_blank"><img src="/img/soc-insta.png" alt="" /></a>
            			<a href="<?=$_CONFIG['SETTINGS']['twitter']?>" class="soc-btn soc-twitter" title="Twitter" target="_blank"><img src="/img/soc-twitter.png" alt="" /></a>
                        <a href="http://t.me/<?=$_CONFIG['SETTINGS']['telegram']?>" class="soc-btn soc-telegram" title="Telegram" target="_blank"><img src="/img/soc-telegram.png" alt="" /></a>
			</div>
		</div>
		
		<div class="clear"></div>
	</div>
	
	
	<form action="" id="contacts-form" onsubmit="sendMsg(); return false; ">
		<div class="r">
			<div class="kol"><input type="text" name="name" placeholder="Ваше имя*:"/></div>
			<div class="kol"><input type="text" name="email" placeholder="Ваш e-mail*:"/></div>
			<div class="kol"><input type="text" name="subject" placeholder="По вопросу*:"/></div>
		</div>
		<textarea name="msg" placeholder="Напишите сообщение*:"></textarea>
		
		
		<input type="submit" name="" onclick="" value="Отправить" />
		<div class="loading" style="display: none; ">Секунду...</div>
		<div class="info error"></div>
	</form>
	
	
	
	<!-- УСПЕХ -->
	<div class="success1" style="display: none; ">
		<h1>Ваше сообщение успешно отправлено!</h1>
		<h2>Скоро мы Вам обязательно ответим.</h2>
	</div>
	<!-- /УСПЕХ -->
	
	
</div>














