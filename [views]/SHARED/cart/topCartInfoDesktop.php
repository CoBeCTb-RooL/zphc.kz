<?php
$cart = $MODEL['cart'];
?>

<a href="/cart/" class="cart-desktop">
	<span class="lbl"><i class="fa fa-shopping-cart" aria-hidden="true"></i> Корзина:</span>
	<?php
	if($cart->ids )
	{?>
		<b class="quan"><?=$cart->totalQuan?> </b> <?=Funx::okon($cart->totalQuan, array('товаров', 'товар', 'товара'))?> на сумму <b class="sum"><?=Currency::formatPrice($cart->sumInCurrency['totalSum'])?></b>
	<?php 	
	}
	else
	{?>
		 пуста
	<?php 	
	}?>
	
</a> 



<!-- 
<a href="/cart/" class="cart-desktop2">
	<span class="lbl"><i class="fa fa-shopping-cart" aria-hidden="true"></i> Корзина:</span>
	<span class="info">
	<?php
	if($cart->ids )
	{?>
		<b class="quan"><?=$cart->totalQuan?> </b> <?=Funx::okon($cart->totalQuan, array('товаров', 'товар', 'товара'))?> на сумму <b class="sum"><?=Currency::formatPrice($cart->sumInCurrency['totalSum'])?></b>
	<?php 	
	}
	else
	{?>
		 пуста
	<?php 	
	}?>
	</span>
	<span class="clear"></span>
</a> -->