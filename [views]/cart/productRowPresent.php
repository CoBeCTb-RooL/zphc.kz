<?php
//vd($MODEL);
$product = $MODEL['product'];
$discount = $MODEL['discount'];
$productQuan = $MODEL['productQuan'];


$oldProductPrice = $product->priceInCurrency;
$finalProductPrice = $product->priceInCurrency;
//vd($product);
//vd($discount);
//vd($product);
?>




<div class="item">
		
	
	<div class="kol title">
		<span class=" pic">
			<a href="<?=$product->url()?>" target="_blank" title="<?=$product->name?>"><img src="<?=$product->photo ? Media::img($product->photo) : Funx::noPhotoSrc()?>&width=500" alt="<?=$product->name?>" /></a>
		</span>
		<a href="<?=$product->url()?>" target="_blank"><?=$product->name?></a>
	</div>
	<div class="kol price">
		<b><?=Currency::formatPrice($finalProductPrice)?></b>
	</div>
	<div class="kol quan">
		<div class="val">&times;<?=$productQuan?></div>
	</div>
	<div class="kol final-price">
		<?php 
		if($discount)
		{?>
		<span class="old-price"><?=Currency::formatPrice($oldProductPrice*$productQuan)?></span>
		<?php 
		}?>
		
		<b>бесплатно!</b>
	</div>
	
	
	<div class="kol delete">
		
	</div>
	
</div>