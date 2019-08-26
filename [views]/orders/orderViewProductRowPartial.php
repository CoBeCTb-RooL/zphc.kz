<?php
$oi = $MODEL['oi'];
$dopQuanMultiplier = $MODEL['dopQuanMultiplier'];
//vd($dopQuanMultiplier);

//vd($oi);
$productPricePurchasedInCurrency = Currency::calculatePrice($oi->productPricePurchased, $oi->currency);
//vd($oi->productPricePurchased);

?>



<tr>
	<td><a href="<?=$oi->product->urlFull()?>" target="_blank" style="font-size: 1.2em; "><?=$oi->productName?></a></td>
	
	<td align="center">
		<?=Currency::formatPrice($productPricePurchasedInCurrency)?> 
		<?php 
		if($oi->currency->code!=Currency::USD)
		{?>
			<div style="color: #777; ">(<?=$oi->productPricePurchased?> $)</div>
		<?php 	
		}?>
	</td>
	
	<td>&times;<?=$oi->quan?> <?=$dopQuanMultiplier > 1 ? '<span style="color: #777;">(ещё &times;'.$dopQuanMultiplier.')</span>' : ''?></td>
	
	<td align="center">= 
		<?=Currency::formatPrice($productPricePurchasedInCurrency*$oi->quan*$dopQuanMultiplier)?> 
		<?php 
		if($oi->currency->code!=Currency::USD)
		{?>
			<div style="color: #777; ">(<?=$oi->productPricePurchased*$oi->quan*$dopQuanMultiplier?> $)</div>
		<?php 	
		}?>
	</td>
	
</tr>