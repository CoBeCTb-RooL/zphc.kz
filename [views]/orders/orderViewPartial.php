<?php
$item = $MODEL['item'];
?>



<!doctype html>
<html>
  <head>
    <style type="text/css">
      * {font-family: tahoma; font-size: 13px; }
		.r{margin: 0 0 3px 0; }
		.r .lbl{display: inline-block; text-align: right; padding: 0 3px 0 0;  min-width: 140px; font-weight: bold;  vertical-align: middle; font-size: .9em; }
		.r .val{display: inline-block; vertical-align: middle;}
		
		.t{border-collapse: collapse; }
		.t th, .t td{border: 1px solid #000; padding: 3px 4px;  }  
    </style>
  </head>
  

	<body>
		
	
<?php 
if($item)
{?>
	<h1>Заказ №<?=$item->id?></h1>
	<div class="date"><?=Funx::mkDate($item->dateCreated, 'numeric_with_time')?></div>
	<p></p>
	
	
	
	<div class="r">
		<div class="lbl" style="display: inline-block; text-align: right; font-weight: bold; padding: 0 4px 0 0; min-width: 140px; font-size: .9em; ">Валюта: </div>
		<div class="val" style="display: inline-block; "><?=$item->currency->code?> (<?=$item->currency->sign?>)</div>
	</div>
	<?php 
	if($item->currency->code!=Currency::USD)
	{?>
	<div class="r">
		<div class="lbl" style="display: inline-block; text-align: right; font-weight: bold; padding: 0 4px 0 0; min-width: 140px; font-size: .9em; ">Курс: </div>
		<div class="val" style="display: inline-block; ">1 $ = <?=$item->currency->coef?> <?=$item->currency->sign?></div>
	</div>
	<?php 	
	}?>
	
	
	
	
	<div class="customer-info" style="margin: 15px 0 30px 0; ">
		<div class="r" >
			<div class="lbl" style="display: inline-block; text-align: right; font-weight: bold; padding: 0 4px 0 0; min-width: 140px; font-size: .9em; ">Заказчик: </div>
			<div class="val" style="display: inline-block; ">
				<span style="font-weight: bold; font-size: 1.1em; "><?=$item->customerName?></span>
				&nbsp;&nbsp;(<a href="mailto:<?=$item->customerEmail?>"><?=$item->customerEmail?></a>)
			</div>
		</div>
		
		<div class="r">
			<div class="lbl" style="display: inline-block; text-align: right; font-weight: bold; padding: 0 4px 0 0; min-width: 140px; font-size: .9em; ">Телефон: </div>
			<div class="val" style="display: inline-block; "><?=User::formatPhone($item->customerPhone)?></div>
		</div>
		<div class="r">
			<div class="lbl" style="display: inline-block; text-align: right; font-weight: bold; padding: 0 4px 0 0; min-width: 140px; font-size: .9em; ">Адрес: </div>
			<div class="val" style="display: inline-block; "><?=$item->customerAddress?></div>
		</div>
		<div class="r">
			<div class="lbl" style="display: inline-block; text-align: right; font-weight: bold; padding: 0 4px 0 0; min-width: 140px; font-size: .9em; ">Почтовый индекс: </div>
			<div class="val" style="display: inline-block; "><?=$item->customerIndex?></div>
		</div>
		<div class="r">
			<div class="lbl" style="display: inline-block; text-align: right; font-weight: bold; padding: 0 4px 0 0; min-width: 140px; font-size: .9em; ">Комментарий: </div>
			<div class="val" style="display: inline-block; "><?=$item->customerComment ? $item->customerComment : '-нет-'?></div>
		</div>
		
		<div class="r">
			<div class="lbl" style="display: inline-block; text-align: right; font-weight: bold; padding: 0 4px 0 0; min-width: 140px; font-size: .9em; ">Телефон друга: </div>
			<div class="val" style="display: inline-block; ">
				<?=$item->refererPhone ? User::formatPhone($item->refererPhone) : '-нет-'?>
				<b>(<?=($item->referer->name ? $item->referer->name : 'телефон в базе не найден')?>)</b>
			</div>
		</div>
	
	</div>
	
	
	
	
	<h3>Товары:</h3>
	<table class="t" border="1" style="border-collapse: collapse; ">
	
	<!-- курсы -->
	<?php 
	$displayedCourseProductIds = array();
	foreach($item->orderItemsSorted[ProductRelationType::COURSE] as $courseId=>$orderItems)
	{
		$course = $item->courses[$courseId];
		$courseCount = $item->getCourseCount($courseId);
		?>
		<tr>
			<th colspan="10">КУРС: <a href="<?=$course->urlFull()?>" target="_blank" style="text-decoration: underline;"><?=$course->name?></a> <b style="font-size: 1.4em; color: #777; "><?=$courseCount > 1 ? '&times;'.$courseCount : ''?></b></th>
		</tr>
		<?php 
		foreach($orderItems as $oi)
		{
			if(in_array($oi->productId, $displayedCourseProductIds))
				continue; 
			?>
			
			<? Core::renderPartial('orders/orderViewProductRowPartial.php', $a = array('oi'=>$oi, 'dopQuanMultiplier'=>$courseCount ? $courseCount : 1))?>
			
		<?php 	
			$displayedCourseProductIds[] = $oi->productId;
		}?>
	<?php 	
	}?>
	<!-- курсы -->
	
	
	<!-- акции -->
	<?php 
	foreach($item->orderItemsSorted[ProductRelationType::ACTION] as $actionId=>$orderItems)
	{
		$action = $item->actions[$actionId];
		?>
		<tr>
			<th colspan="10"><span>АКЦИЯ: </span><a href="<?=$action->urlFull()?>" target="_blank" style="text-decoration: underline;"><?=$action->name?></a> <b style="font-size: 1.4em; color: #777; "></b></th>
		</tr>
		<?php 
		foreach($orderItems as $oi)
		{?>
			
			<? Core::renderPartial('orders/orderViewProductRowPartial.php', $a = array('oi'=>$oi, 'dopQuanMultiplier'=>1))?>
		
		<?php 
		}?>
	<?php 	
	}?>
	<!-- акции -->
	
	
	
	<!-- отдельные товары -->
	<?php 
	if($item->orderItemsSorted['individualProducts'])
	{?>
		<tr>
			<th colspan="10">Отдельные товары</th>
		</tr>
		<?php 
		foreach($item->orderItemsSorted['individualProducts'] as $oi)
		{?>
			<? Core::renderPartial('orders/orderViewProductRowPartial.php', $a = array('oi'=>$oi, 'dopQuanMultiplier'=>1), false, true)?>
		<?php 
		}?>
	<?php 
	}?>
	<!-- отдельные товары -->
	
	
	<!-- подарки -->
	<?php 
	if($item->orderItemsSorted['present'])
	{?>
		<tr>
			<th colspan="10">Подарки</th>
		</tr>
		<?php 
		
		foreach($item->orderItemsSorted['present'] as $oi)
		{
			//vd($oi->product->id)?>
			<? Core::renderPartial('orders/orderViewProductPresentRowPartial.php', $a = array('oi'=>$oi, 'dopQuanMultiplier'=>1))?>
		<?php 
		}?>
	<?php 
	}?>
	<!-- /подарки -->
	
	
	
	</table>
	
	
	<p></p>
	
	
	<div class="r" style="margin: 0 0 15px 0; ">
		<div class="lbl" style="display: inline-block; text-align: right; font-weight: bold; padding: 0 4px 0 0; min-width: 140px; font-size: .9em; ">Сумма заказа: </div>
		<div class="val" style="display: inline-block; " style=""><?=Currency::formatPrice($item->totalSumInCurrency, $item->currency)?> 
		<?php 
		if($item->currency->code!=Currency::USD)
		{?>
			<span style="color: #777; ">&nbsp;(<?=Currency::formatPrice($item->totalSum, null, true)?> $)</span>
		<?php 	
		}?>
		</div>
	</div>
	
	
	<?php 
	$deliveryPriceInCurrency = Currency::calculatePrice($item->deliveryPrice, $item->currency);?>
	<div class="r">
		<div class="lbl" style="display: inline-block; text-align: right; font-weight: bold; padding: 0 4px 0 0; min-width: 140px; font-size: .9em; " style="vertical-align: top; ">Доставка: </div>
		<div class="val" style="display: inline-block; ">
			<?=$item->deliveryType->name?>
			<div>
			<b><?= $item->deliveryPrice > 0  ? Currency::formatPrice($deliveryPriceInCurrency, $item->currency)  : 'Бесплатно'?></b>
			<?php 
			if($item->currency->code!=Currency::USD && $item->deliveryPrice > 0 )
			{?>
				<span style="color: #777; ">(<?=Currency::formatPrice($item->deliveryPrice, null , true)?> $)</span>
			<?php 	
			}?>
			</div>
		</div>
	</div>
	
	
	<div class="r">
		<div class="lbl" style="display: inline-block; text-align: right; font-weight: bold; padding: 0 4px 0 0; min-width: 140px; font-size: .9em; " >Оплачено бонусами: </div>
		<div class="val" style="display: inline-block; ">
			<?=Currency::getPriceStr($item->payedByBonus, $item->currency, true)?>
			<?php 
			if($item->currency->code!=Currency::USD)
			{?>
			<span style="color: #777; ">(<?=Currency::formatPrice($item->payedByBonus, $item->currency, true)?> $)</span>
			<?php 	
			}?>
		</div>
	</div>
	
	
	
	<div class="r" >
		<div class="lbl" style="display: inline-block; text-align: right; font-weight: bold; padding: 0 4px 0 0; min-width: 140px; font-size: .9em; ">ИТОГО: </div>
		<div class="val" style="display: inline-block; " style="font-size: 1.7em; "><?=Currency::formatPrice($item->totalSumInCurrency + $deliveryPriceInCurrency, $item->currency, true)?> <?=$item->currency->sign?>
		<?php 
		if($item->currency->code!=Currency::USD)
		{?>
			<span style="color: #777; font-size: 17px; ">&nbsp;(<?=Currency::formatPrice($item->totalSum + $item->deliveryPrice, $item->currency, true)?> $)</span>
		<?php 	
		}?>
		</div>
	</div>
	
	
	<div class="r">
		<div class="lbl" style="display: inline-block; text-align: right; font-weight: bold; padding: 0 4px 0 0; min-width: 140px; font-size: .9em; " >Способ оплаты: </div>
		<div class="val" style="display: inline-block; "><?=$item->paymentType->name?></div>
	</div>
	
	
	
<?php 	
}
else 
{?>
	Заказ не существует.
<?php 	
}?>

</body>

</html>