<?php
$item = $MODEL['item'];

$orderStatuses = OrderStatus::$items;
unset($orderStatuses[OrderStatus::DELETED]);
?>




<script>
function setOrderStatus(orderId, status)
{
	//alert(status)
	$.ajax({
		url: '/admin/orders/setStatus',
		data: 'id='+orderId+'&status='+status,
		dataType: 'json',
		beforeSend: function(){$.fancybox.showLoading()},
		success: function(data){
			//alert(data)
			$('.order-statuses *').removeClass('active')
			$('#order-status-'+data.orderStatus.code).addClass('active')
			
			if(status == '<?=OrderStatus::CANCELLED?>')
				$('.order-delete-btn').fadeIn('fast')
			else
				$('.order-delete-btn').fadeOut('fast')
			//alert('#order-status-'+data.orderStatus.code)
		},
		error: function(e){},
		complete: function(){ $.fancybox.hideLoading()}
	});
}


function saveManagerComment()
{
	var comment = $('#manager-comment').val()
	//alert(comment)
	$.ajax({
		url: '/admin/orders/setManagerComment',
		data: 'id=<?=$item->id?>&comment='+encodeURIComponent(comment),
		dataType: 'json',
		beforeSend: function(){$.fancybox.showLoading()},
		success: function(data){
			if(!data.errors)
			{
				notice('Сохранено!')
			}
			else
				alert(data.errors[0].error)
		},
		error: function(e){},
		complete: function(){ $.fancybox.hideLoading()}
	});
}


function orderDelete(id)
{
	var comment = $('#manager-comment').val()
	//alert(comment)
	$.ajax({
		url: '/admin/orders/itemDelete',
		data: 'id=<?=$item->id?>',
		dataType: 'json',
		beforeSend: function(){$.fancybox.showLoading()},
		success: function(data){
			if(!data.errors)
			{
				notice('Удалено.')
				location.href='/<?=ADMIN_URL_SIGN?>/orders'
			}
			else
				alert(data.errors[0].error)
		},
		error: function(e){},
		complete: function(){ $.fancybox.hideLoading()}
	});
}
</script>



<style>
.r{margin: 0 0 3px 0; }
.r .lbl{display: inline-block; text-align: right; padding: 0 3px 0 0;  min-width: 140px; font-weight: bold;  vertical-align: middle; font-size: .9em; }
.r .val{display: inline-block; vertical-align: middle;}

.order-statuses a{display: inline-block; margin: 0 4px; padding: 5px 7px;  }
.order-statuses a.active{background: #203042; color: #fff;  text-decoration: none; }

</style>


<?php 
if($item)
{?>
	<h1>Заказ #<?=$item->id?></h1>
	<div class="date"><?=Funx::mkDate($item->dateCreated, 'numeric_with_time')?></div>
	<p></p>
	
	<div class="r" style="margin: 15px 0; ">
		<div class="lbl">Статус: </div>
		<div class="val order-statuses">
			<?php 
			foreach($orderStatuses as $s)
			{?>
				<a class="item <?=$item->orderStatus->code== $s->code ? 'active' : ''?>" id="order-status-<?=$s->code?>" href="#" onclick="setOrderStatus(<?=$item->id?>, '<?=$s->code?>')"><?=$s->name?></a>
			<?php 
			}?>
			
			
			<a href="#" class="item order-delete-btn" onclick="if(confirm('Удалить заказ?')){orderDelete(<?=$item->id?>); return false; }" style="color: red; <?=$item->orderStatus->code != OrderStatus::CANCELLED ? 'display: none; ' : ''?>">УДАЛИТЬ</a>
		</div>
	</div>
	
	
	
	
	<div class="r">
		<div class="lbl">Валюта: </div>
		<div class="val"><?=$item->currency->code?> (<?=$item->currency->sign?>)</div>
	</div>
	<?php 
	if($item->currency->code!=Currency::USD)
	{?>
	<div class="r">
		<div class="lbl">Курс: </div>
		<div class="val">1 $ = <?=$item->currency->coef?> <?=$item->currency->sign?></div>
	</div>
	<?php 	
	}?>
	
	
	
	
	<div class="customer-info" style="margin: 15px 0 30px 0; ">
		<div class="r" >
			<div class="lbl">Заказчик: </div>
			<div class="val">
				<span style="font-weight: bold; font-size: 1.1em; ">
				<?php 
				if($item->userId)
				{?>
					<a href="/<?=ADMIN_URL_SIGN?>/user/?userId=<?=$item->userId?>" target="_blank"><?=$item->customerName?></a>
				<?php 	
				}
				else
				{?>
					<?=$item->customerName?>
				<?php 	
				}?>
				</span>
				&nbsp;&nbsp;(<a href="mailto:<?=$item->customerEmail?>"><?=$item->customerEmail?></a>)
				
			</div>
		</div>
		
		<div class="r">
			<div class="lbl">Телефон: </div>
			<div class="val"><?=User::formatPhone($item->customerPhone)?></div>
		</div>
		<div class="r">
			<div class="lbl">Адрес: </div>
			<div class="val"><?=$item->customerAddress?></div>
		</div>
		<div class="r">
			<div class="lbl">Почтовый индекс: </div>
			<div class="val"><?=$item->customerIndex?></div>
		</div>
		<div class="r">
			<div class="lbl">Комментарий: </div>
			<div class="val"><?=$item->customerComment ? $item->customerComment : '-нет-'?></div>
		</div>
		
		<div class="r">
			<div class="lbl">Телефон друга: </div>
			<div class="val">
				<?=$item->refererPhone ? User::formatPhone($item->refererPhone) : '-нет-'?>
				<?php 
				if($item->referer)
				{?>
					&nbsp;&nbsp;<b>(<a href="<?=$item->referer->adminUrl()?>" target="_blank" ><?=$item->referer->name?></a>)</b>
				<?php 	
				}
				elseif($item->refererPhone)
				{?>
					<b>(телефон в базе не найден)</b>
				<?php 	
				}?>
			</div>
		</div>
	
	</div>
	
	
	
	<div class="r">
		<div class="lbl" style="vertical-align: top; ">Комментарий <br>менеджера: </div>
		<div class="val">
			<textarea id="manager-comment" style="width: 480px; height: 100px; white-space:pre-wrap;"><?=$item->managerComment?></textarea>
			<button onclick="saveManagerComment()" style="display: block; ">Сохранить</button>
		</div>
	</div>
	
	
	
	<h3>Товары:</h3>
	<table class="t">
	
	<!-- курсы -->
	<?php 
	$displayedCourseProductIds = array();
	foreach($item->orderItemsSorted[ProductRelationType::COURSE] as $courseId=>$orderItems)
	{
		$course = $item->courses[$courseId];
		$courseCount = $item->getCourseCount($courseId);
		?>
		<tr>
			<th colspan="10">КУРС: <a href="<?=$course->url()?>" target="_blank" style="text-decoration: underline;"><?=$course->name?></a> <b style="font-size: 1.4em; color: #777; "><?=$courseCount > 1 ? '&times;'.$courseCount : ''?></b></th>
		</tr>
		<?php 
		foreach($orderItems as $oi)
		{
			if(in_array($oi->productId, $displayedCourseProductIds))
				continue; 
			?>
			
			<? Core::renderPartial('orders/productRowPartial.php', $a = array('oi'=>$oi, 'dopQuanMultiplier'=>$courseCount ? $courseCount : 1))?>
			
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
			<th colspan="10"><span>АКЦИЯ: </span><a href="<?=$action->url()?>" target="_blank" style="text-decoration: underline;"><?=$action->name?></a> <b style="font-size: 1.4em; color: #777; "></b></th>
		</tr>
		<?php 
		foreach($orderItems as $oi)
		{?>
			
			<? Core::renderPartial('orders/productRowPartial.php', $a = array('oi'=>$oi, 'dopQuanMultiplier'=>1))?>
		
		<?php 
		}?>
	<?php 	
	}?>
	<!-- акции -->
	
	
	<!-- отдельные товары -->
	<tr>
		<th colspan="10">Отдельные товары</th>
	</tr>
	<?php 
	foreach($item->orderItemsSorted['individualProducts'] as $oi)
	{?>
		<? Core::renderPartial('orders/productRowPartial.php', $a = array('oi'=>$oi, 'dopQuanMultiplier'=>1))?>
	<?php 
	}?>
	<!-- /отдельные товары -->
	
	
	
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
			<? Core::renderPartial('orders/productPresentRowPartial.php', $a = array('oi'=>$oi, 'dopQuanMultiplier'=>1))?>
		<?php 
		}?>
	<?php 
	}?>
	<!-- /подарки -->
	
	</table>
	
	
	<p></p>
	
	
	<div class="r" style="margin: 0 0 15px 0; ">
		<div class="lbl">Сумма заказа: </div>
		<div class="val" style=""><?=Currency::formatPrice($item->totalSumInCurrency)?> <?=$item->currency->sign?>
		<?php 
		if($item->currency->code!=Currency::USD)
		{?>
			<span style="color: #777; ">&nbsp;(<?=Currency::formatPrice($item->totalSum)?> $)</span>
		<?php 	
		}?>
		</div>
	</div>
	
	
	<?php 
	$deliveryPriceInCurrency = Currency::calculatePrice($item->deliveryPrice, $item->currency);?>
	<div class="r">
		<div class="lbl" style="vertical-align: top; ">Доставка: </div>
		<div class="val">
			<?=$item->deliveryType->name?>
			<div>
			<b><?= $item->deliveryPrice > 0  ? Currency::formatPrice($deliveryPriceInCurrency).''.$item->currency->sign  : 'Бесплатно'?></b>
			<?php 
			if($item->currency->code!=Currency::USD && $item->deliveryPrice > 0 )
			{?>
				<span style="color: #777; ">(<?=Currency::formatPrice($item->deliveryPrice)?> $)</span>
			<?php 	
			}?>
			</div>
		</div>
	</div>
	
	
	<div class="r">
		<div class="lbl" >Оплачено бонусами: </div>
		<div class="val">
			<?=Currency::getPriceStr($item->payedByBonus, $item->currency)?>
			<?php 
			if($item->currency->code!=Currency::USD)
			{?>
			<span style="color: #777; ">(<?=Currency::formatPrice($item->payedByBonus)?> $)</span>
			<?php 	
			}?>
		</div>
	</div>
	
	
	
	<div class="r" >
		<div class="lbl">ИТОГО: </div>
		<div class="val" style="font-size: 1.7em; "><?=Currency::formatPrice($item->totalSumInCurrency + $deliveryPriceInCurrency)?> <?=$item->currency->sign?>
		<?php 
		if($item->currency->code!=Currency::USD)
		{?>
			<span style="color: #777; font-size: 17px; ">&nbsp;(<?=Currency::formatPrice($item->totalSum + $item->deliveryPrice)?> $)</span>
		<?php 	
		}?>
		</div>
	</div>
	
	
	<div class="r">
		<div class="lbl" >Способ оплаты: </div>
		<div class="val"><img src="<?=$item->paymentType->icon?>" style="vertical-align: middle;" alt="" /> <?=$item->paymentType->name?></div>
	</div>
	
	
	
<?php 	
}
else 
{?>
	Заказ не существует.
<?php 	
}?>