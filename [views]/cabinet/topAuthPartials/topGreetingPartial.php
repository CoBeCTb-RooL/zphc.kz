<?php
$u = $MODEL; 
?>

<div class="top-greeting">
	Здравствуйте, <b><?=$u->name?></b>
	<div class="links">
		<a href="<?=Route::getByName(Route::CABINET)->url()?>"><i class="fa fa-user"></i> Личный кабинет</a>
		| <a href="#logout" class="logout" onclick="if(confirm('Вы хотите выйти?')){Cabinet.logout();} return false; ">Выйти</a>
	</div>
</div>