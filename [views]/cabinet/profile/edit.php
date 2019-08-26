<?php
$user=$MODEL['user']; 

?>




<script>
function checkProfileForm(isNew){
	
	//return true
	// 	зачистка эрроров формы
	$('#edit-form *').each(function(n,element){
		$(element).removeClass('field-error')
	});
	$('.cabinet-error').css('display', 'none')
	//$('#edit-form .info').html('')
	
	var problems=[]
	//var fillAllError = 'Пожалуйста, заполните необходимые поля!'
	
	//return true	
	
	// 	проверка на заполненность полей
	
	if($('#name').val() == '')
		problems.push({field: 'name', error: 'Укажите <b>имя</b>'})
	if($('#phone').val() == '')
		problems.push({field: 'phone', error: 'Введите Ваш <b>телефон</b>'})
	if($('#email').val() == '')
		problems.push({field: 'email', error: 'Укажите Ваш <b>e-mail</b>'})
	
	if(isNew)
	{
		var pass=$('#pass').val()
		var pass2=$('#pass2').val()
		if(pass == '')
			problems.push({field: 'pass', error: 'Вы не ввели <b>пароль</b>'})
		if(pass2 == '')
			problems.push({field: 'pass2', error: 'Вы не ввели <b>подтверждение пароля</b>'})
		if(pass != pass2 != '')
		{
			problems.push({field: 'pass', error: 'Пароли не совпадают!'})
			problems.push({field: 'pass2', error: ''})
		}
		
		if($('#captcha').val() == '')
			problems.push({field: 'captcha', error: 'Введите код с картинки!'})
		
	}
	
	if(problems.length > 0)
	{
		//alert(123)
		showCabinetErrors(problems)
		
	}
	else
	{	
		
		$('#edit-form .loading').slideDown('fast')
		return true
	}
	
	return false
}




function showCabinetErrors(errors)
{
	//alert(123)
	
	$('.cabinet-error').html(getErrorsString(errors)).slideDown('fast')
	//alert(123)
	$('#edit-form .loading').fadeOut('fast')
	//alert(123)
}





$(document).ready(function(){

	$("#edit-form input[name=phone]").mask("+7 (999) 999-99-99");
})

</script>






<!--крамбсы-->
<? Core::renderPartial(SHARED_VIEWS_DIR.'/crumbs.php', $MODEL['crumbs']);?>
<!--//крамбсы-->





<div class="cabinet edit" >	
<?php 
if($user)
	Core::renderPartial('cabinet/menu.php'); 
?>

	<h1><?=($user ? 'РЕДАКТИРОВАНИЕ ЛИЧНЫХ ДАННЫХ' : 'РЕГИСТРАЦИЯ')?></h1>
	
		
		<form  id="edit-form" method="post" action="/cabinet/profile/profileEditSubmit" target="frame6" onsubmit="return checkProfileForm(<?=$user?'false':'true'?>); ">
			<input type="hidden" name="id" value="<?=$user->id?>">
			
			
			<div class="r">
				<div class="lbl">ФИО<i class="req">*</i>:</div>
				<div class="val"><input type="text" name="name" id="name" placeholder="ФИО" value="<?=$user->name?>"></div>
			</div>
			
			<div class="r">
				<div class="lbl" >E-mail<i class="req">*</i>:</div>
				<div class="val"><input type="text" name="email" id="email" placeholder="E-mail" value="<?=$user->email?>"></div>
				<div class="hint">Этот e-mail будет Вашим логином на сайте. Все уведомления с сайта будут приходить именно на него. </div>
			</div>
			
			<div class="r">
				<div class="lbl">Телефон<i class="req">*</i>:</div>
				<div class="val"><input type="text" name="phone" id="phone" placeholder="Телефон" value="<?=$user->phone?>"></div>
			</div>
			
			
			<div class="r">
				<div class="lbl">Адрес:</div>
				<div class="val"><input type="text" name="address" id="address" placeholder="Адрес" value="<?=$user->address?>"></div>
			</div>
			
			<div class="r">
				<div class="lbl">Почтовый индекс:</div>
				<div class="val"><input type="text" name="index" id="index" placeholder="Почтовый индекс" value="<?=$user->index?>"></div>
			</div>
			
			
			<?php 
			if(!$user )
			{?>
			<hr />
			
			<div class="r">
				<div class="lbl">Пароль<i class="req">*</i>:</div>
				<div class="val"><input type="password" name="pass" id="pass" placeholder="Пароль" autocomplete="off"></div>
			</div>
			
			<div class="r">
				<div class="lbl">Ещё раз<i class="req">*</i>:</div>
				<div class="val"><input type="password" name="pass2" id="pass2" placeholder="Ещё раз.." autocomplete="off"></div>
			</div>
			
			
			<hr />
			
			
			<div class="r">
				<div class="lbl">Код<i class="req">*</i>:</div>
				<div class="val">
					<img src="/<?=INCLUDE_DIR?>/kcaptcha/?<?=session_name()?>=<?=session_id()?>" width="100" id="captcha-pic">
					<input type="text" name="captcha" id="captcha" size="3">
					<a href="javascript:void(0)" onclick="$('#captcha-pic').attr('src', '/<?=INCLUDE_DIR?>/kcaptcha/?'+(new Date()).getTime());" id="re-captcha">Не вижу код</a>
				</div>
			</div>
			
			
			<?php 	
			}?>
			
			
			
			
			
	<p>
	<input type="submit" value="<?=$user ? 'СОХРАНИТЬ' : 'ЗАРЕГИСТРИРОВАТЬСЯ'?>">
	<span class="loading" style="display: none ;">Секунду...</span>
	<div class="cabinet-error error" ></div>
	
		
	</form>
	
	
	
	<div class="success" style="display: none;">
	<? if(!$user)
	{?>
		<h2>Вы успешно зарегистрированы!</h2> 
		Остался ещё небольшой шаг! На указанный Вами ящик отправлено письмо с инструкциями по активации Вашего аккаунта. 
	<?php 
	}
	else
	{?>
	 	Ваши данные успешно изменены!
	<?php 
	}?>
	</div>
</div>



<iframe class="frame" name="frame6"  style="display: none; "></iframe>

