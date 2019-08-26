<?php
$item = $MODEL['item'];
$crumbs = $MODEL['crumbs'];
$present = $MODEL['present'];
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
{
	$oldPrice = 0;
	$newPrice = $item->price;
		
	if($item->discountObject)
	{
		$oldPrice = $item->price;
		$newPrice = ProductSimple::calculateDiscountPrice($item->price, $item->discountObject->discount);
	}
	?>
	<div class="product">
		<!-- <h1><?=$item->name?></h1> -->
		
		
		<div class="top-wrapper">
			<div class="media">
				<a href="/<?=UPLOAD_IMAGES_REL_DIR?><?=$item->photo?>" class="highslide" onclick="return hs.expand(this)"><img class="main-pic" src="<?=$item->photo ? Media::img($item->photo) : Funx::noPhotoSrc()?>&width=500" alt="<?=$item->name?>" /></a>
				<div class="video"><?=$item->videoHTML?></div>	
			</div>
			<div class="short-info">
				<div class="rate rate-top">
					<span class="stars"></span>
					<span class="val"><?=$item->rate?> &nbsp;&nbsp;<i>(<?=$item->votesCount ? $item->votesCount.' '.Funx::okon($item->votesCount, array('голосов', 'голос', 'голоса')) : 'отзывов пока нет'?>)</i></span>
					<div class="clear"></div>
				</div>
				<div class="title"><?=$item->name?></div>
				<div class="anons"><?=$item->anons?></div>
				<?php 
				if($item->inPackage)
				{?>
				<div class="in-package">В упаковке: <b><?=$item->inPackage?></b></div>
				<?php 
				}?>
				<div class="price">
					<span class="old-price"><?=$oldPrice ? Currency::drawAllCurrenciesPrice($oldPrice) : ''?></span>
					<span class="new-price"><?=Currency::drawAllCurrenciesPrice($newPrice)?></span>
					<?php 
					if($item->discountObject)
					{?>
						<sup>-<?=$item->discountObject->discount?>%</sup>
					<?php 	
					}?>
				</div>
				
				
				
				
				<?
				if($item->stock)
				{?>
				
				<input type="button" value="Добавить в корзину" onclick="addProductToCart(<?=$item->id?>, $('#product-quan-<?=$item->id?>').val())" />
				<span class="product-quan">&times;<input type="text" id="product-quan-<?=$item->id?>" size="1" value="1" /></span>
				<span id="product-loading-<?=$item->id?>" style="display: none; ">секунду...</span>
				<?php 
				}
				else
				{?>
					<input type="button" disabled="disabled" value="Нет в наличии" />
				<?php 	
				}?>
				
				<?php 
				if(Admin::isAdmin())
				{?>
					<div><a href="/<?=ADMIN_URL_SIGN?>/catalogSimple/productsList/?itemId=<?=$item->id?>" target="_blank">редактировать в админке</a></div>
				<?php 	
				}?>
			</div>
		</div>
		<div class="clear"></div>
		
		<div class="info-wrapper">
			
			<span class="tab descr"><?=Funx::addDIVToH2($item->descr)?></span>
			
			
			
			
			<!-- подарки -->
			<?php 
			if($present)
			{?>
				<div class="present">
					<hr />
					<h1><img src="/img/present2.png" alt="" width="45" />&nbsp;ПОДАРОК!</h1>
					<? Core::renderPartial(SHARED_VIEWS_DIR.'/presentsList.php', $a = array('present'=>$present, 'item'=>$item));?>
					<!-- <hr /> -->
				</div>
			<?php 	
			}?>
			<!-- //подарки -->
			
			
			
			<!-- акции -->
			<?php 
			if($MODEL['actions'])
			{?>
			<hr />
			<h3>Этот товар участвует в акциях:</h3>
			<div class="product-in-actions">
				<?php 
				foreach($MODEL['actions'] as $action)
				{?>
				<div class="item">
					<a href="<?=$action->url()?>" target="_blank" class="img" title="<?=$action->title?>"><img  src="<?=Media::img($action->photo)?>&width=160&height=100" alt="<?=$action->name?>" /> &nbsp;&nbsp;<?=$action->name?></a>
				</div>
				<?php 	
				}?>
			</div>
			<?php 	
			}?>
			<!-- /акции -->
			
			
			<!-- рекомендуемые товары -->
			<hr />
			<h3>Рекомендуемые товары:</h3>
			<?php Core::renderPartial('catalog/productsListHorizontal.php', $arr=array('items'=>$MODEL['recommended']))?> 
			<!-- /рекомендуемые товары -->
			
			
			
			
			<!-- отзывы -->
			<div class="tab reviews" id="reviews">
			
			
				<!-- список отзывов -->
				<?=Core::renderView('/'.SHARED_VIEWS_DIR.'/reviews/reviewsList.php', $arr = array('list'=>$MODEL['reviews']))?>
				<!-- /список отзывов -->
			
			
				<!-- форма отзывов -->
				<?=Core::renderView('/'.SHARED_VIEWS_DIR.'/reviews/addReviewForm.php', $arr = array('objectTypeCode'=>Object::PRODUCT, 'objectId'=>$item->id))?>
				<!-- /форма отзывов -->
				
				
			</div>
			<!-- /отзывы -->
			
			
			
		</div>
		
		
		
		
		
		
		
	</div>
<?php 	
} 
else
{?>
	Раздел не найден.
<?php 	
}
?>
