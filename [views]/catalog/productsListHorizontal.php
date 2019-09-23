<?php
$items = $MODEL['items'];
?>

<?php 
//vd($items)?>


<div class="products-list-horizontal">
<?if(count($items)):?>
		<div class="items">
		<?foreach($items as $item)
		{
			$oldPrice = 0;
			$newPrice = $item->price;
			
			if($item->discountObject)
			{
				$oldPrice = $item->price;
				$newPrice = ProductSimple::calculateDiscountPrice($item->price, $item->discountObject->discount);
			}
			?>
			<div class="item" id="item-<?=$item->id?>">
				<a href="<?=$item->url()?>" title="<?=$item->name?>" class="img-wrapper">
					<img src="<?=$item->photo ? Media::img($item->photo) : Funx::noPhotoSrc()?>&height=300" alt="" />
					<!-- <img src="/upload/images/<?=$item->photo?>" alt="" />-->
				</a>
				<div class="title-wrapper">
					<a href="<?=$item->url()?>" class="title" ><?=$item->name?></a>
				</div>
				<div class="rate">
					<span class="stars"></span>
					<span class="val"><?=$item->rate?> &nbsp;&nbsp;<i><!-- (<?=$item->votesCount ? $item->votesCount.' '.Funx::okon($item->votesCount, array('голосов', 'голос', 'голоса')) : 'отзывов пока нет'?>)</i> --></span>
					<div class="clear"></div>
				</div>
				
				<span class="price">
					<span class="old-price"><?=$oldPrice ? Currency::drawAllCurrenciesPrice($oldPrice) : ''?></span>
					<span class="new-price"><?=Currency::drawAllCurrenciesPrice($newPrice)?></span>
					<?php 
					if($item->discountObject)
					{?>
						<sup>-<?=$item->discountObject->discount?>%</sup>
					<?php 	
					}?>
				</span>	
				
				
				<div class="btn-wrapper">
				<?if($item->stock):?>
                    <input type="button" value="Купить" onclick="addProductToCart(<?=$item->id?>, $('#product-quan-<?=$item->id?>').val())" />
                    <span class="product-quan">
                        &times;
                        <span class="product-quan-wrapper">
                            <a href="#" class="quan-btn minus" onclick="addQuan($('#product-quan-<?=$item->id?>'), -1); return false;">-</a>
                            <input type="text" id="product-quan-<?=$item->id?>" size="1" value="1" />
                            <a href="#" class="quan-btn plus" onclick="addQuan($('#product-quan-<?=$item->id?>')); return false;">+</a>
                        </span>
                    </span>
                    <span id="product-loading-<?=$item->id?>" style="display: none; ">секунду...</span>
				<?else:?>
					<input type="button" disabled="disabled" value="Нет в наличии" />
				<?endif;?>
				</div>
				
				<?php 
				/*if(!$item->stock)
				{?>
					<div class="no-stock">Нет в наличии</div>
				<?php 	
				}*/?>

				<?if($item->isNew):?>
					<div class="novinka">Новинка</div>
				<?endif;?>
				
				<?if($item->discountObject):?>
					<div class="skidka"><b>%</b></div>
				<?endif;?>
				
			</div>
			
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
		}?>
			<div class="clear"></div>
		</div>
	<?else:?>
		&nbsp;&nbsp;&nbsp;&nbsp;Товаров нет. 
	<?endif;?>
</div>