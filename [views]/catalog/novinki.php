<?php
$list = $MODEL['items'];
$cat = $MODEL['cat'];
?>

<!--крамбсы-->
<? Core::renderPartial(SHARED_VIEWS_DIR.'/crumbs.php', $MODEL['crumbs']);?>
<!--//крамбсы-->



<div class="catalog">

	<h1>Новинки</h1>
	
	<?php Core::renderPartial('catalog/productsListHorizontal.php', $arr=array('items'=>$list))?>
	
	
</div>