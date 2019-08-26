<?php

 #	УБРАТЬ ОТСЮДА! во вьюхи 
if($USER)
{?>
	<div class="menu">
		<a class="<?=(!$CORE->params[0] ? 'active' : '')?>" href="<?=Route::getByName(Route::CABINET)->url()?>"><i class="fa fa-user"></i> Кабинет</a> 
		<!-- <a class="<?=($CORE->params[0] == 'advs' ? 'active' : '')?>" href="<?=Route::getByName(Route::CABINET_MY_ADVS)->url()?>">Мои объявления</a> --> 
		<a class="<?=($CORE->params[0] == 'profile' && $CORE->params[1] == 'edit' ? 'active' : '')?>" href="<?=Route::getByName(Route::CABINET_PROFILE_EDIT)->url()?>">Редактировать</a>
		<a class="<?=($CORE->params[0] == 'profile' && $CORE->params[1] == 'password_change' ? 'active' : '')?>" href="<?=Route::getByName(Route::CABINET_PROFILE_CHANGE_PASSWORD)->url() ?>">Сменить пароль</a> 
		
		
		<a href="javascript:void(0)" onclick="if(confirm('Вы хотите выйти?')){Cabinet.logout()}; return false; " class="logout"><i class="fa fa-sign-out"></i> Выйти</a>
		
		<div class="clear"></div>
	</div>
<?php 	
} 
?>

