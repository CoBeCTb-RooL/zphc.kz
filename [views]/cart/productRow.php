<?php
//vd($MODEL);
$product = $MODEL['product'];
$discount = $MODEL['discount'];
$productQuan = $MODEL['productQuan'];


$product->initDiscountPrice($discount);
$oldProductPrice = $product->priceInCurrency;
$finalProductPrice = $product->discountPriceInCurrency ? $product->discountPriceInCurrency : $oldProductPrice;
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
	<div class="kol price" style="line-height: 100%; ">
		<?php 
		if($discount)
		{?>
		<span class="old-price"><?=Currency::formatPrice($oldProductPrice)?></span><br />
		<?php 
		}?>
		<b><?=Currency::formatPrice($finalProductPrice)?></b>
	</div>
	<div class="kol quan">
		<div class="val">
			<?php 
			$onclick = 'addQuan('.$product->id.', -1); return false;';
			if($productQuan == 1)
				$onclick = 'if(confirm(\'Убрать из корзины?\')){addQuan('.$product->id.', -1);} return false;';
			?>
			<a href="#" class="change-quan-btn" onclick="<?=$onclick?> " title="убрать">&ndash;</a>
			&times;<?=$productQuan?>
			<a href="#" class="change-quan-btn" onclick="addQuan(<?=$product->id?>, 1);return false; " title="добавить">+</a>
		</div>
	</div>
	<div class="kol final-price" style="line-height: 100%; ">
		<?php 
		if($discount)
		{?>
		<span class="old-price"><?=Currency::formatPrice($oldProductPrice*$productQuan)?></span><br/>
		<?php 
		}?>
		
		<b><?=Currency::formatPrice($finalProductPrice * $productQuan)?></b>
	</div>
	
	
	<div class="kol delete">
		<a href="#delete"  title="Убрать товар" onclick="if(confirm('Убрать товар из корзины?')){addQuan(<?=$product->id?>, -<?=$productQuan?>)}; return false; ">
			<span class="word-delete"> <span style="font-size: 20px; font-weight: bold; line-height:50%; vertical-align: middle;  display: inline-block;   border: 0px solid red;   font-size: 30px;  ">&times;</span> <span style="display: inline-block; vertical-align: middle;">убрать из корзины</span></span>
			<span class="x">&times;</span>
		</a>
	</div>
	
</div>