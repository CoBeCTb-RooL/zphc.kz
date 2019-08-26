<?php
$list = $MODEL['list']; 

?>

<!--крамбсы-->
<? Core::renderPartial(SHARED_VIEWS_DIR.'/crumbs.php', $MODEL['crumbs']);?>
<!--//крамбсы-->



<div class="courses">

	<h1>Курсы</h1>
	
<?php
foreach($list as $key=>$item)
{?>
	<div class="item" id="item-<?=$item->id?>">
		<a href="<?=$item->url()?>" class="img" title="<?=$item->title?>"><img  src="<?=Media::img($item->photo)?>&width=500&height=530" alt="<?=$item->name?>" /></a>
		<div class="info">
			<a href="<?=$item->url()?>" class="title"><?=$item->name?></a>
			<br><div class="rate">
				<span class="stars"></span>
				<span class="val"><?=$item->rate?> &nbsp;&nbsp;<i>(<?=$item->votesCount ? $item->votesCount.' '.Funx::okon($item->votesCount, array('голосов', 'голос', 'голоса')) : 'отзывов пока нет'?>)</i></span>
				<div class="clear"></div>
			</div>
			<div class="block"><b>Цель: </b><?=$item->goal?></div>
			<div class="block"><b>Состав: </b><?=$item->consist?></div>
		</div>
		<div class="right">
		<?php 
		if($item->sum['sum'])
		{?>
			<div class="price"><!--<?=Currency::formatPrice($item->sumInCurrency['sum'])?>--><?=Currency::drawAllCurrenciesPrice($item->sum['sum'])?></div>
			<div class="final-price"><!--<?=Currency::formatPrice($item->sumInCurrency['totalSum'])?>--><?=Currency::drawAllCurrenciesPrice($item->sum['totalSum'])?></div>
		<?php 
		}?>	
			<!-- <a href="<?=$item->url()?>" class="btn">Оформить курс</a>-->
			
			<?php 
			if($item->productsState->code == ProductsState::OK)
			{?>
			<button onclick="addCourseProductsToCart(<?=$item->id?>)">В корзину</button>
			<div id="course-loading-<?=$item->id?>" style="display: none; ">секунду...</div>
			<?php 	
			}
			else
			{?>
			<button disabled="disabled" style="font-size: 13px; width: 120px; "><?=$item->productsState->name?></button>	
			<?php 	
			}?>
			
		</div>
		<div class="clear"></div>
	</div>
	
	<hr />
	
	<script>
		$(document).ready(function(){
			$("#item-<?=$item->id?> .stars").rateYo({
			    rating: <?=$item->rate?>,
			    readOnly: true,
			    /*multiColor: {
			        "startColor": "#eeeeee", //RED
			        "endColor"  : "#FFAF18"  //GREEN
				},*/
			    starWidth: "15px"
			  });
		})
		</script>
<?php 	
} 
?>
</div>

<?=Funx::drawPages($MODEL['totalElements'], $MODEL['page'], $MODEL['elPP']);?>