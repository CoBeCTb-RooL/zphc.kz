<?php
//vd($_GLOBALS['currency']);
?>
<div class="r">
	<div class="lbl">Оплатить бонусами: </div>
	<div class="val"><input type="text" name="payByBonus" id="payByBonus" value="<?=Currency::formatPrice($CART->payedByBonusInCurrency, null, true)?>"  /><button onclick="payByBonus()" >Пересчитать</button></div>
	<div id="payByBonusInfo"></div>
</div>
<div class="r">
	<div class="lbl">Внесено: </div>
	<div class="val" ><span id="bonusSum"><?=$CART->payedByBonusInCurrency ?  Currency::drawAllCurrenciesPrice($CART->payedByBonus) : 0?></span> <!--<?=$_GLOBALS['currency']->sign?>-->  </div>
</div>