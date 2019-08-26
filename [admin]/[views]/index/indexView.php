<?php
$MODEL['questionsInModeration']=0;
?>


<style>
h1, h2, h3{margin: 5px 0; }
.section{font-size: 11px; margin: 20px; }
.inner{padding: 0 0 0 20px; }
</style>


<div class="index">
	Welcome to SLoNNE CMS! Fast, easy, no excess! 
	<p>

	
	
	
	
	<!-- отзывы -->
	<div class="section">
		<h3><i class="fa fa-comments"></i> Новые отзывы</h3>
		<div class="inner">
			<a href="/<?=ADMIN_URL_SIGN?>/review/" target="_blank">Новые отзывы: <?=$MODEL['reviewsCount'] ? '<span style="font-size: 19px; font-weight: bold; ">'.$MODEL['reviewsCount'].'</span>' : 'нет'?></a>
		</div>
	</div>
	
	
	
	<!-- заказы -->
	<div class="section">
		<h3><i class="fa fa-list-alt"></i> Новые заказы</h3>
		<div class="inner">
			<a href="/<?=ADMIN_URL_SIGN?>/orders/" target="_blank">Новые заказы: <?=$MODEL['newOrdersCount'] ? '<span style="font-size: 19px; font-weight: bold; ">'.$MODEL['newOrdersCount'].'</span>' : 'нет'?></a>
		</div>
	</div>
	
	

</div>



