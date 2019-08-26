<?php
$list = $MODEL['list']; 
?>

<!--крамбсы-->
<? Core::renderPartial(SHARED_VIEWS_DIR.'/crumbs.php', $MODEL['crumbs']);?>
<!--//крамбсы-->



<div class="articles">

	<h1>Статьи</h1>
	
<?php
foreach($list as $key=>$item)
{?>
	
	<a href="<?=$item->url()?>" class="item2">
		<img class="img" src="<?=Media::img($item->attrs['pic'])?>&width=190" alt="<?=$item->attrs['name']?>" />
		<img class="img-small-screen" src="<?=Media::img($item->attrs['pic'])?>&width=500" alt="<?=$item->attrs['name']?>" />
		<h2><?=$item->attrs['name']?></h2>
	</a>
	<!-- <div class="item">
		<a href="<?=$item->url()?>" class="img" title="<?=$item->attrs['name']?>"><img src="<?=Media::img($item->attrs['pic'])?>&width=190" alt="<?=$item->attrs['name']?>" /></a>
		<a href="<?=$item->url()?>" class="img-small-screen" title="<?=$item->attrs['name']?>"><img src="<?=Media::img($item->attrs['pic'])?>&width=500" alt="<?=$item->attrs['name']?>" /></a>
		<a href="<?=$item->url()?>" class="title" ><h2 ><?=$item->attrs['name']?></h2></a>
	</div>-->
<?php 	
} 
?>
</div>

<?=Funx::drawPages($MODEL['totalElements'], $MODEL['page'], $MODEL['elPP']);?>