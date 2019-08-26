<?php

//vd($CART);
?>






<div class="products-wrapper">
	
	
	
	
	<div class="head">
		<!--  <div class="kol pic"></div>-->
		<div class="kol title">Товар</div>
		<div class="kol price">Цена</div>
		<div class="kol quan">Кол-во</div>
		<div class="kol final-price">Итого</div>
		<div class="kol delete">Удалить</div>
	</div>
	
	
	<!-- курсы -->
	<?php
	foreach($CART->courses as $courseId=>$courseQuan)
	{
		$course = $CART->allCartCoursesInfo[$courseId];
		//vd($course);
		?>
		<div class="block cart-course">
		<div class="heading">
			<a href="<?=$course->url()?>" target="_blank"><?=$course->name?></a>
			<div class=" quan">
				<a href="#" class="change-quan-btn" onclick="addCourse(<?=$course->id?>, -1); return false; ">&ndash;</a>
				&times;<?=$courseQuan?>
				<a href="#" class="change-quan-btn" onclick="addCourse(<?=$course->id?>, 1);return false; ">+</a>
			</div>
		</div>
				
		<?php 
		foreach($course->relatedProducts as $p)
		{
			$productQuan = $p->param1 * $courseQuan; 
			$discount = $course->discount ? $course->discount : 0;
			//$discount = 0;

			$arr = array(
					'product'=>$p->product,
					'productQuan'=>$productQuan,
					'discount'=>$discount,
			);
			?>
			
			<?php Core::renderPartial('cart/productRow.php', $arr)?>
		
		<?php 	
		}?>
		</div>
	<?php 	
	}?>
	<!-- /курсы -->
	
	
	
	
	<!-- акции -->
	<?php
	//vd($CART->actions);
	foreach($CART->actions as $actionId=>$courseQuan)
	{
		$action = $CART->allCartActionsInfo[$actionId];
		//vd($course);
		?>
		<div class="block cart-action">
		<div class="heading">
			<b >АКЦИЯ: </b> <a href="<?=$action->url()?>" target="_blank"><?=$action->name?></a>
		</div>
				
		<?php 
		foreach($action->relatedProducts as $p)
		{
			$productQuan = $CART->actions[$action->id][$p->product->id]; 
			$discount = $action->discount ? $action->discount : 0;
			//$discount = 0;

			$arr = array(
					'product'=>$p->product,
					'productQuan'=>$productQuan,
					'discount'=>$discount,
			);
			?>
			
			<?php Core::renderPartial('cart/productRow.php', $arr)?>
		
		<?php 	
		}?>
		</div>
	<?php 	
	}?>
	<!-- /акции -->
	
	
	
	
	<!-- отдельные товары -->
	<?php 
	if($CART->individualProducts)
	{?>
	<div class="block cart-individuals">
		<?php 
		if($CART->courses || $CART->actions)
		{?>
		<div class="heading">
			Отдельные товары
		</div>
		<?php 
		}?>
		
		<?php 
		foreach($CART->individualProducts as $productId=>$quan)
		{
			$product = $CART->allCartProductsInfo[$productId];
			//vd($product);
			$productQuan = $quan;
			$arr = array(
					'product'=>$product,
					'productQuan'=>$productQuan,
					'discount'=>$product->discountObject->discount,
			);
			?>
							
			<?php Core::renderPartial('cart/productRow.php', $arr)?>
		
		<?php 	
		}?>
	</div>
	<?php 
	}?>
	<!-- /отдельные товары -->
	
	
	
	
	<!-- подарки-->
	<?php 
	if($CART->presents)
	{?>
	<div class="block cart-presents">
		<div class="heading">
			ПОДАРКИ <img src="/img/present2.png" alt="" width="35" />&nbsp;
		</div>
		
		<?php 
		foreach($CART->presents as $productId=>$quan)
		{
			$product = ProductSimple::get($productId);
			//vd($product);
			$productQuan = $quan;
			$arr = array(
					'product'=>$product,
					'productQuan'=>$productQuan,
					'discount'=>100,
			);
			?>
							
			<?php Core::renderPartial('cart/productRowPresent.php', $arr)?>
		
		<?php 	
		}?>
	</div>
	<?php 
	}?>
	<!-- /подарки-->
	
	
	
	
	<hr />
	<div class="overall">
		<div class="lbl">Итого товаров на сумму: </div>
		<?php 
		if($CART->sumInCurrency['discountSum'])
		{?>
			<div class="old-price"><?=Currency::formatPrice($CART->sumInCurrency['sum'])?></div>
		<?php 	
		}?>
		<b><?=Currency::formatPrice($CART->sumInCurrency['totalSum'])?></b>
	</div>
</div>


<?php 
//vd($CART);?>