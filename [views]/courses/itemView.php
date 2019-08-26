<?php
$item = $MODEL['item'];
$crumbs = $MODEL['crumbs'];

?>

<!--крамбсы-->
<? Core::renderPartial(SHARED_VIEWS_DIR.'/crumbs.php', $crumbs);?>
<!--//крамбсы-->

<script>







$(function () {
	 // top
	  $(".rate-top .stars").rateYo({
	    rating: <?=$item->rate?>,
	    readOnly: true,
	    starWidth: "15px"
	  });

	
		
	});






	


</script>



<?php
if($item)
{?>
	<div class="course">
	
	
	
	
	
	
		<h1><?=$item->name?></h1>
		
		<div class="pic">
			<a href="/<?=UPLOAD_IMAGES_REL_DIR?><?=$item->photo?>" class="highslide" onclick="return hs.expand(this)"><img src="<?=$item->photo ? Media::img($item->photo) : Funx::noPhotoSrc()?>&width=500&height=530" alt="<?=$item->name?>" /></a>
		</div>
		<div class="short-info">
			<div class="rate rate-top">
				<span class="stars"></span>
				<span class="val"><?=$item->rate?> &nbsp;&nbsp;<i>(<?=$item->votesCount ? $item->votesCount.' '.Funx::okon($item->votesCount, array('голосов', 'голос', 'голоса')) : 'отзывов пока нет'?>)</i></span>
				<div class="clear"></div>
			</div>
			<div class="block"><b>Цель: </b><?=$item->goal?></div>
			<div class="block"><b>Состав: </b><?=$item->consist?></div>
			
			<div class="duration">
				<div class="dur kurs">
					<div class="num"><?=$item->courseDuration?></div>
					<div class="lbl"><?=Funx::okon($item->courseDuration, array('недель', 'неделя', 'недели'))?> <b>КУРС</b></div>
				</div>
				<div class="dur pkt">
					<div class="num"><?=$item->pktDuration?></div>
					<div class="lbl"><?=Funx::okon($item->pktDuration, array('недель', 'неделя', 'недели'))?> <b>ПКТ</b></div>
				</div>
			</div>
			
			<?php 
			if(Admin::isAdmin())
			{?>
				<div><a href="/<?=ADMIN_URL_SIGN?>/course?itemId=<?=$item->id?>" target="_blank">редактировать в админке</a></div>
			<?php 	
			}?>
			
		</div>
		<div class="price-wrapper">
			<div class="price"><!--<?=Currency::getPriceStr($item->sum['sum'])?>--><?=Currency::drawAllCurrenciesPrice($item->sum['sum'])?></div>
			<div class="discount-price"><!--<?=Currency::getPriceStr($item->sum['totalSum'])?>--><?=Currency::drawAllCurrenciesPrice($item->sum['totalSum'])?></div>
		</div>
	
		<div class="clear"></div>
		
		<div class="descr"><?=Funx::addDIVToH2($item->descr)?></div>
		
		
		
		
		
		
		<?php 
		if($item->courseTable)
		{?>
			<a href="" class="course-table-btn" onclick="$('.course-table').slideToggle(); return false; ">Посмотреть таблицу курса</a>
			<div class="course-table"  style="display: none; ">
				<?=$item->courseTable?>
			</div>
		<?php 	
		}?>
		
		
		
		
		
		<?php 
		if($item->excel)
		{?>
		<div class="excel">
			
			<div class="excel-btn">
				<a href="/<?=UPLOAD_IMAGES_REL_DIR.$item->excel?>">Скачать таблицу приёма</a>
			</div>
		</div>
		<?php 
		}?>
		
		
		
		<div class="sostav">
			<h2>Состав курса: </h2>
			<?php 
			//vd($item->relatedProducts);?>
			<div class="related-products">
			<?php
			//$totalSum = 0;
			foreach($item->relatedProducts as $p)
			{
				//$totalSum += $p->product->price*$p->param1;?>
				<div class="item">
					<div class="row1">
						<div class="title">
							<a href="<?=$p->product->url()?>" target="_blank"><?=$p->product->name?></a>
							<?php
							$errorLbl = '';
							if(!$p->product->stock)
								$errorLbl = 'Нет в наличии';
							elseif($p->product->stock < ($p->param1 ? $p->param1 : 1))
								$errorLbl = 'Нет в нужном количестве';
							?>
							<div class="error-lbl"><?=$errorLbl?></div>
						</div>
						<div class="price"><!--<?=Currency::formatPrice($p->product->priceInCurrency)?>--><?=Currency::drawAllCurrenciesPrice($p->product->price)?></div>
						<div class="quan"> &times;<?=$p->param1?></div>
						<div class="equals"> = </div>
						<div class="price-total"><b><!--<?=Currency::formatPrice($p->product->priceInCurrency*$p->param1)?>--><?=Currency::drawAllCurrenciesPrice($p->product->price * $p->param1)?></b></div>
					</div>
				</div>
			<?php 	
			}
			?>
			
			
			<?php 
			//vd($item->sum['discountSum'])?>
				<div class="overall">
					<div class="lbl">Всего: <b><!--<?=Currency::formatPrice($item->sumInCurrency['sum'])?>--><?=Currency::drawAllCurrenciesPrice($item->sum['sum'])?></b></div>
					
					<div class="discount">Скидка по сумме <?=$item->discount?>%: &nbsp;&nbsp;<b>-<!--<?=Currency::formatPrice($item->sumInCurrency['discountSum'])?>--><?=Currency::drawAllCurrenciesPrice($item->sum['discountSum'])?></b></div>
					<div class="final-price">ИТОГО: <b><!--<?=Currency::formatPrice($item->sumInCurrency['totalSum'])?>--><?=Currency::drawAllCurrenciesPrice($item->sum['totalSum'])?></b></div>
					
					<?php 
					if($item->productsState->code == ProductsState::OK)
					{?>
					<button onclick="addCourseProductsToCart(<?=$item->id?>)">В корзину</button>
					<div id="course-loading-<?=$item->id?>" style="display: none; ">секунду...</div>
					<?php 	
					}
					else
					{?>
					<button disabled="disabled"><?=$item->productsState->name?></button>	
					<?php 	
					}?>
					
					
				</div>
			</div>
		</div>
		
		
		
		<!-- отзывы -->
		<div class="tab reviews" id="reviews">
		
		
			<!-- список отзывов -->
			<?=Core::renderView('/'.SHARED_VIEWS_DIR.'/reviews/reviewsList.php', $arr = array('list'=>$MODEL['reviews']))?>
			<!-- /список отзывов -->
		
		
			<!-- форма отзывов -->
			<?=Core::renderView('/'.SHARED_VIEWS_DIR.'/reviews/addReviewForm.php', $arr = array('objectTypeCode'=>Object::COURSE, 'objectId'=>$item->id))?>
			<!-- /форма отзывов -->
			
			
		</div>
		<!-- /отзывы -->
		
	
		
	</div>
<?php 	
} 
else
{?>
	Раздел не найден.
<?php 	
}
?>
