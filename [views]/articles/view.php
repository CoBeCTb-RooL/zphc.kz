<?php
$item = $MODEL['item'];
$crumbs = $MODEL['crumbs'];


?>

<!--крамбсы-->
<? Core::renderPartial(SHARED_VIEWS_DIR.'/crumbs.php', $crumbs);?>
<!--//крамбсы-->



<?php
if($item)
{?>
	<div class="article">
		<h1><?=$item->attrs['name']?></h1>
		
		<img class="img" src="<?=Media::img($item->attrs['pic'])?>&width=250" alt="<?=$item->attrs['name']?>" />
		<img class="img-small-screen" src="<?=Media::img($item->attrs['pic'])?>&width=500" alt="<?=$item->attrs['name']?>" />
		
		<span class="text"><?=$item->attrs['text']?></span>
	</div>
<?php 	
} 
else
{?>
	Раздел не найден.
<?php 	
}
?>