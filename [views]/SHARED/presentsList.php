<?php 
$item = $MODEL['item'];
$present = $MODEL['present'];
?>
<?php //vd($present->productsState)?>

	<div class="col">
	<h4>Купите:</h4>
	<?php 
	foreach($present->triggerProducts as $p)
	{?>
		<?php Core::renderPartial(SHARED_VIEWS_DIR.'/presentsItem.php', $a=array('relProd'=>$p));?>
	<?php 	
	}?>
	</div>
	<div class="col">
		<h4>И получите в подарок:</h4>
		<?php 
		foreach($present->presentProducts as $p)
		{?>
			<?php Core::renderPartial(SHARED_VIEWS_DIR.'/presentsItem.php', $a=array('relProd'=>$p));?>
		<?php 	
		}?>
	</div>



	<div class="overall1">
		<div class="lbl">Итого: <span class="val"><?=Currency::drawAllCurrenciesPrice($present->sum)?></span></div>
	</div>
	
	<?php 
	if($present->productsState->code == ProductsState::OK)
	{?>
	<input type="button" value="Положить товары в корзину!" onclick="addPresentProductsToCart(<?=$present->id?>)" />
	<div id="present-loading-<?=$present->id?>" style="display: none; ">секунду...</div>	
	<?php 	
	}
	else
	{?>
		<input type="button" value="<?=$present->productsState->name?>" disabled="disabled" />
	<?php 	
	}?>
