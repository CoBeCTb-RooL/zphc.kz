<?php
$list = $MODEL['list'];

//vd($comments);
?>

<h1><i class="fa fa-comments"></i> Отзывы</h1>


<?php if($MODEL['error']){ echo ''.$MODEL['error'].''; return; }?>


<?php 
if(count($list))
{?>
	<table class="t">
		<tr>
			<th>#</th>
			<th>Дата</th>
			<th>Комментарий</th>
			<th>Пользователь</th>
			<th>Тип</th>
			<th></th>
		</tr>
	<?php 
	//vd($CORE);
	foreach($list as $key=>$c)
	{
		$url = Route::getByName(Route::KARTOCHKA_TOVARA)->url($c->objectId.'?adminMode=1#review-'.$c->id);
		?>
		<tr>
			<td><?=$key+1?>.</td>
			<td><?=mb_strtolower(Funx::mkDate($c->dateCreated, 'with_time'), 'utf-8')?></td>
			<td style="font-weight: bold; "><?=$c->text?></td>
			<td>
			<?php 
			if($c->user)
			{?>
				<a href="/<?=ADMIN_URL_SIGN?>/user/?userId=<?=$c->user->id?>" target="_blank"><?=$c->user->fullName?></a>
			<?php 	
			}
			else
			{?>
				-нет-
			<?php 	
			}?>	
			</td>
			<td><?=$c->objectType->name?></td>
			<td><a href="<?=$url?>" target="_blank">смотреть</a></td>
			
		</tr>
	<?php 	
	}?>
	</table>
<?php 	
}
else
{?>
	Новых отзывов нет.
<?php 	
}?>


