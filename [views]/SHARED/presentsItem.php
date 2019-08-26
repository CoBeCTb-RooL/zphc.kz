<?php 
$p = $MODEL['relProd'];

$oldPrice = 0;
$newPrice = $p->product->price;

if($p->product->discountObject)
{
	$oldPrice = $p->product->price;
	$newPrice = ProductSimple::calculateDiscountPrice($p->product->price, $p->product->discountObject->discount);
}

//vd($p);

?>
<div class="prod" >
	<div class="info">
	<?php 
	if($p->product->id == $item->id)
	{?>
		<b><img src="<?=$p->product->photo ? Media::img($p->product->photo) : Funx::noPhotoSrc()?>&width=50" alt="<?=$p->product->name?>" />Этот товар</b>
	<?php 	
	}
	else
	{?>
		<span class="title">
			<a href="<?=$p->product->url()?>" target="_blank"><img src="<?=$p->product->photo ? Media::img($p->product->photo) : Funx::noPhotoSrc()?>&width=50" alt="<?=$p->product->name?>" /><?=$p->product->name?></a>
		</span> 
	<?php 	
	}?>
		<span class="quan" >(&times;<?=$p->param1?>)</span>
		<?php
		$errorLbl = '';
		if(!$p->product->stock)
			$errorLbl = 'Нет в наличии';
		elseif($p->product->stock < ($p->param1 ? $p->param1 : 1))
			$errorLbl = 'Нет в нужном количестве';
		?>
		<span class="error-lbl"><?=$errorLbl?></span>
	</div>
	
	<div class="price">
	<?php 
	if($p->relationType->code == ProductRelationType::PRESENT_TRIGGER)
	{?>
		<!-- <span class="old-price"><?=$oldPrice ? Currency::drawAllCurrenciesPrice($oldPrice) : ''?></span> -->
		<span class="new-price"><?=Currency::drawAllCurrenciesPrice($newPrice)?></span>
		
		<?php 
		if($p->param1 > 1)
		{?>
		<span class="quan">&times;<?=$p->param1?></span>
		<span class="price-total"> = 
			<!--<span class="old-price"><?=$oldPrice ? Currency::drawAllCurrenciesPrice($oldPrice * $p->param1) : ''?></span> -->
			<span class="new-price"><?=Currency::drawAllCurrenciesPrice($newPrice * $p->param1)?></span>
		</span>
		<?php 
		}?>
		
		<?php 
		if($p->product->discountObject)
		{?>
			<!-- <sup>-<?=$p->product->discountObject->discount?>%</sup> -->
		<?php 	
		}?>
	<?php 
	}
	else
	{?>
		<span class="free">бесплатно!</span>
	<?php 	
	}?>
	</div>

</div>