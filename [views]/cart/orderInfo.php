<?php

//vd($CART);
//vd($CART->sumInCurrency);
//vd($CART->paymentType);
//vd($CART->deliveryType);
?>


<div class="order-summary">
	<div class="r">
		<div class="lbl">Товаров на сумму: </div>
		<div class="val price"><?=Currency::formatPrice($CART->sumInCurrency['sum'])?></div>
	</div>
	<div class="r">
		<div class="lbl">Скидка: </div>
		<div class="val discount"><?=$CART->sumInCurrency['discountSum'] ? '-'.Currency::formatPrice($CART->sumInCurrency['discountSum']) : 'нет'?></div>
	</div>
	<div class="r delivery">
		<div class="lbl">Доставка: </div>
		<div class="val">
			<?=$CART->deliveryType->name?>
			<div class="delivery-price">Стоимость: <b><?=$CART->deliveryCostInCurrency ? Currency::formatPrice($CART->deliveryCostInCurrency) : 'БЕСПЛАТНО'?></b></div>
		</div>
	</div>
	<div class="r">
		<div class="lbl">Оплата: </div>
		<div class="val"><img  src="<?=$CART->paymentType->icon?>" alt=""  width="58" /> <?=$CART->paymentType->name?></div>
	</div>
	
	<div class="r overall">
		<div class="lbl">Итого: </div>
		<div class="val"><?=Currency::formatPrice($CART->sumInCurrency['totalSum']+$CART->deliveryCostInCurrency-$CART->payedByBonusInCurrency)?></div>
	</div>
	
	
</div>