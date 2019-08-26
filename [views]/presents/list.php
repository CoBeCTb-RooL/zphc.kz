<?php
$list = $MODEL['list']; 
?>

<!--крамбсы-->
<? Core::renderPartial(SHARED_VIEWS_DIR.'/crumbs.php', $MODEL['crumbs']);?>
<!--//крамбсы-->



<div class="presents">

	<h1><img src="/img/present2.png" alt="" width="45" />&nbsp;Подарки!</h1>
	<p>
<?php 


if($list)
{?>
	<?php 
	foreach($list as $item)
	{?>
	<div class="item">
		<h2><?=$item->name?> <?=Admin::isAdmin() ? '<a href="/'.ADMIN_URL_SIGN.'/presents/?presentId='.$item->id.'" target="_blank" style="font-size: 12px; font-style: normal; font-weight: normal; ">[редактировать]</a>' : ''?></h2>
		<div class="text"><?=$item->text?></div>
		
		<div class="present">
			<? Core::renderPartial(SHARED_VIEWS_DIR.'/presentsList.php', $a = array('present'=>$item));?>
		</div>
		<hr />
	</div>
	<?php 	
	}?>
<?php 	
}
else
{?>
	На данный момент подарков нет.
<?php 	
}
?>
</div>



