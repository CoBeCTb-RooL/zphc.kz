<?php
$user = $MODEL['user'];

//vd($user);
?>


<!--крамбсы-->
<? Core::renderPartial(SHARED_VIEWS_DIR.'/crumbs.php', $MODEL['crumbs']);?>
<!--//крамбсы-->


<?php 
if($user)
{?>

<div class="cabinet index">
	<?php Core::renderPartial('cabinet/menu.php');?>


	<h2><i class="fa fa-user"></i> <?=$user->name?></h2>
	
	<div class="bonuses" >Бонус: <b><?=Currency::drawAllCurrenciesPrice($user->bonusAvailable)?></b></div>
	
	<div class="contacts">
		<i class="fa fa-phone-square"></i> <?=User::formatPhone($user->phone)?><br>
		<i class="fa fa-envelope"></i> <?=$user->email?> 
		
		<div style="margin: 17px 0 0 0; ">
			<a href="<?=Route::getByName(Route::CABINET_PROFILE_EDIT)->url()?>" class="btn sm">редактировать</a>
		</div>
	</div>
</div>

<?php 
}
else 
{?>
	Вы не авторизованы. 
<?php 
}?>