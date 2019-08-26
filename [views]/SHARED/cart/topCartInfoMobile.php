<?php
$cart = $MODEL['cart'];
?>

<a href="/cart/" class="cart-mobile">
	<span class="lbl"><i class="fa fa-shopping-cart" aria-hidden="true"></i>  </span>
	<?php
	if($cart->ids)
	{?>
		<b class="quan"><?=$cart->totalQuan?> </b> <?=Funx::okon($cart->totalQuan, array('товаров', 'товар', 'товара'))?> на сумму <b class="sum"><?=Currency::formatPrice($cart->sumInCurrency['totalSum'])?></b>
	<?php 	
	}
	else
	{?>
		 Ваша корзина пока пуста
	<?php 	
	}?>
	
</a> 
