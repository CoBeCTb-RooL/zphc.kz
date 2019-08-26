<?php
$item = $MODEL['item'];
$crumbs = $MODEL['crumbs'];


$a = new DateTime();
$b = new DateTime($item->dateTill);
$diffInSeconds = $b->getTimestamp() - $a->getTimestamp();


$isActionOver = $item->dateTill < date('Y-m-d');
?>





<script>



function addActionProductsToCart(id)
{
	$.ajax({
		url: '/cart/addActionProducts',
		data: 'id='+encodeURIComponent(id),
		dataType: 'json',
		beforeSend: function(){$('.product-loading').slideDown('fast')},
		success: function(data){
			if(!data.errors)
			{
				$('#myModal .modal-body').html(data.html)
				$('#myModal').modal('show');
				refreshCartInfo()
			}
			else
				showErrors(data.errors)
		},
		error: function(e){alert(e)},
		complete: function(){$('.product-loading').slideUp('fast')}
	});
}






$(function () {
	 // top
	  $(".rate-top .stars").rateYo({
	    rating: <?=$item->rate?>,
	    readOnly: true,
	    starWidth: "15px"
	  });

	
		
	});









	

jQuery(function($){
	$('#clock').flipcountdown({
		size:"sm",
		speedFlip:20,
		beforeDateTime:'<?=$item->dateTill?>'
	});
})



</script>








<!--крамбсы-->
<? Core::renderPartial(SHARED_VIEWS_DIR.'/crumbs.php', $crumbs);?>
<!--//крамбсы-->


<?php
if($item)
{?>
	<div class="action">
		<h1><?=$item->name?></h1>
		
		<div class="pic">
			<a href="/<?=UPLOAD_IMAGES_REL_DIR?><?=$item->photo?>" class="highslide" onclick="return hs.expand(this)"><img src="<?=$item->photo ? Media::img($item->photo) : Funx::noPhotoSrc()?>&width=500" alt="<?=$item->name?>" /></a>
		</div>
		<div class="short-info">
			<div class="rate rate-top">
				<span class="stars"></span>
				<span class="val"><?=$item->rate?> &nbsp;&nbsp;<i>(<?=$item->votesCount ? $item->votesCount.' '.Funx::okon($item->votesCount, array('голосов', 'голос', 'голоса')) : 'отзывов пока нет'?>)</i></span>
				<div class="clear"></div>
			</div>
			
			<div class="price-wrapper">
				<span class="lbl">Цена за все товары акции: </span>
				<span class="price">
					<!--<?=Currency::formatPrice($item->sumInCurrency['sum'])?>-->
					<?=Currency::drawAllCurrenciesPrice($item->sum['sum'])?>
				</span>
				<span class="discount-price">
					<!--<?=Currency::formatPrice($item->sumInCurrency['totalSum'])?>-->
					<?=Currency::drawAllCurrenciesPrice($item->sum['totalSum'])?>
				</span>
				
				
				<br>
				<div class="countdown-wrapper" >
					<div class="lbl">До конца акции осталось: </div>
					<br />
					<div class="countdown">
						<!-- <div id="clock" ></div>-->
						
						<div class="countdown-wrapper-relative">
							<div class="countdown-wrapper-inner">
								<div id="flipclock" class="scale-4" style=" "></div>
							</div>
						</div>
			
						<div class="time-lbl days1">Дней</div>
						<div class="time-lbl hours1">Часов</div>
						<div class="time-lbl minutes1">Минут</div>
						<div class="time-lbl secs1">Секунд</div>
					</div>
					<div class="clear"></div>
					<?php 
					if($isActionOver)
					{?>
						<div style="color: red; font-weight: bold; ">Акция завершена.</div>
					<?php 	
					}?>
					
					<script>
					var clock;

					$(document).ready(function() {
						var clock;

						//FlipClock.Lang.Custom = { days:'11Days', hours:'11Hours', minutes:'Minutes', seconds:'Seconds' };
						
						clock = $('#flipclock').FlipClock({
					        clockFace: 'DailyCounter',
					        autoStart: false,
					        defaultLanguage : 'russian',
					        callbacks: {
					        	stop: function() {
					        		$('.message').html('The clock has stopped!')
					        	}
					        }
					    });
							    
					    clock.setTime(<?=$diffInSeconds?>);
					    clock.setCountdown(true);
					    clock.start();

					});
					</script>
				</div>
				
				
			</div>
			
			
			<a href="#products" onclick="$('html, body').animate({scrollTop: $('#products').offset().top}, 600);" class="btn">Смотреть товары акции</a>
			
		</div>
		
	
		<div class="clear"></div>
		
		
		
		
		<div class="descr"><?=Funx::addDIVToH2($item->descr)?></div>
		
		
		<div class="sostav">
			<h2 id="products">Товары акции: </h2>
			<?php 
			//vd($item->relatedProducts);?>
			<div class=" related-products-action">
			<?php
			$totalSum = 0;
			foreach($item->relatedProducts as $p)
			{
				$totalSum += $p->product->price*($p->param1 ? $p->param1 : 1);?>
				
				
				
				<div class="item">
					<div class="pic">
						<a href="/<?=UPLOAD_IMAGES_REL_DIR?><?=$p->product->photo?>" class="highslide" onclick="return hs.expand(this)"><img src="<?=$p->product->photo ? Media::img($p->product->photo) : Funx::noPhotoSrc()?>&width=200" alt="<?=$p->product->name?>" /></a>
					</div>
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
					<div class="price-wrapper">
						<span class="price">
							<!--<?=Currency::getPriceStr($p->product->price)?>-->
							<?=Currency::drawAllCurrenciesPrice($p->product->price)?>
						</span>
						<span class="price-final">
							<!--<?=Currency::getPriceStr(ProductSimple::calculateDiscountPrice($p->product->price , $item->discount))?>-->
							<?=Currency::drawAllCurrenciesPrice(ProductSimple::calculateDiscountPrice($p->product->price , $item->discount))?>
						</span>
					</div>
					<div class="clear"></div>
				</div>
			<?php 	
			}
			?>
			
			
			<?php 
			//vd($item->sum['discountSum'])?>
				
			</div>
			<div class="overall">
				<div class="lbl">Всего: <b><!--<?=Currency::getPriceStr($item->sum['sum'])?>--><?=Currency::drawAllCurrenciesPrice($item->sum['sum'])?></b></div>
				
				<div class="discount">Скидка по сумме <?=$item->discount?>%: &nbsp;&nbsp;<b>-<!--<?=Currency::getPriceStr($item->sum['discountSum'])?>--><?=Currency::drawAllCurrenciesPrice($item->sum['discountSum'])?></b></div>
				<div class="final-price">ИТОГО: <b><!--<?=Currency::getPriceStr($item->sum['totalSum'])?>--><?=Currency::drawAllCurrenciesPrice($item->sum['totalSum'])?></b></div>
				
				<?php 
				if(!$isActionOver)
				{?>
				
					<?php 
					if($item->productsState->code == ProductsState::OK)
					{?>
					<button onclick="addActionProductsToCart(<?=$item->id?>)">Положить товары в корзину</button>
					<div class="product-loading" style="display: none; ">секунду...</div>
					<?php 	
					}
					else
					{?>
					<button disabled="disabled"><?=$item->productsState->name?></button>	
					<?php 	
					}?>
				
				
				<?php 
				}
				else
				{?>
				<div style="color: red; font-weight: bold; ">Акция завершена.</div>
				<?php 	
				}?>
			</div>
		</div>
		
		
		
		<!-- отзывы -->
		<div class="tab reviews" id="reviews">
		
		
			<!-- список отзывов -->
			<?=Core::renderView('/'.SHARED_VIEWS_DIR.'/reviews/reviewsList.php', $arr = array('list'=>$MODEL['reviews']))?>
			<!-- /список отзывов -->
		
		
			<!-- форма отзывов -->
			<?=Core::renderView('/'.SHARED_VIEWS_DIR.'/reviews/addReviewForm.php', $arr = array('objectTypeCode'=>Object::ACTION, 'objectId'=>$item->id))?>
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
