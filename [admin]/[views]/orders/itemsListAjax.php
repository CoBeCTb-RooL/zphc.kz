<?php 
$orderStatus = $MODEL['orderStatus'];
$items = $MODEL['items'];
$orderBy = $MODEL['orderBy'];
$desc = $MODEL['desc'];

$chosenId = $MODEL['chosenId'];
$totalCount = $MODEL['totalCount'];
$p = $MODEL['p'];
$elPP = $MODEL['elPP'];

$dateFrom = $MODEL['dateFrom'];
$dateTo = $MODEL['dateTo'];

$i=0;
//vd($cat);
//vd($list);
$upArrowSign = '▲';
$downArrowSign = '▼';

$orderStatuses = OrderStatus::$items;
unset($orderStatuses[OrderStatus::DELETED]);
?>





<script>
$(document).ready(function(){
	$("#customerPhone").mask("+7 (999) 999-99-99");
})

</script>



<style>
	.t .th a{white-space: nowrap; }
	.t tr{background: #fff ; }
	<?php 
	foreach(OrderStatus::$items as $s)
	{?>
	.order-status-<?=$s->code?>{background: <?=$s->background?> !important;}
	<?php 	
	}?>
	
	
</style>





<div class="filters">
	
	<div class="section statuses">
		<h1>Статус:</h1>
		<a class="item <?=!$orderStatus ? 'active' : ''?>" href="#" onclick="OPTS.orderStatus='';  itemsList(); return false;  ">ВСЕ</a>
	<?php 
	foreach($orderStatuses as $s)
	{?>
		<a class="item <?=$orderStatus->code== $s->code ? 'active' : ''?>" href="#" onclick="OPTS.orderStatus='<?=$s->code?>';  itemsList(); return false; "><?=$s->icon?> <?=$s->name?></a>
	<?php 
	}?>
	</div>
	
	
	

	
	<div class="section id">
		<h1>ID заказа:</h1>
		<input type="text" name="chosenId" id="id" value="<?=$chosenId?>" style="width: 40px;" />
		&nbsp;&nbsp;<input type="button" value="взять id" onclick="OPTS.chosenId=$('#id').val(); itemsList(); return false; " />&nbsp;<input type="button" value="&times;" onclick="OPTS.chosenId=''; itemsList(); return false; " />
	</div>
	
	
	
	<div class="section date">
		<h1>Дата:</h1>
		от <input type="text" name="dateFrom" id="dateFrom" value="<?=$dateFrom?>"  style="width:70px"> <img id="dateFrom-calendar-btn" src="/js/calendar/calendar.jpg" style="border:0px; vertical-align: middle; ">
					
					<script>
						Calendar.setup({
						    inputField     :    "dateFrom",      // id of the input field
						    ifFormat       :    "%Y-%m-%d",       // format of the input field
						    showsTime      :    false,            // will display a time selector
						    button         :    "dateFrom-calendar-btn",   // trigger for the calendar (button ID)
						    singleClick    :    true,           // double-click mode
						    step           :    1                // show all years in drop-down boxes (instead of every other year as default)
						});
				</script>
		&nbsp; до <input type="text" name="dateTo" id="dateTo" value="<?=$dateTo?>"  style="width:70px"> <img id="dateTo-calendar-btn" src="/js/calendar/calendar.jpg" style="border:0px; vertical-align: middle; ">
					
					<script>
						Calendar.setup({
						    inputField     :    "dateTo",      // id of the input field
						    ifFormat       :    "%Y-%m-%d",       // format of the input field
						    showsTime      :    false,            // will display a time selector
						    button         :    "dateTo-calendar-btn",   // trigger for the calendar (button ID)
						    singleClick    :    true,           // double-click mode
						    step           :    1                // show all years in drop-down boxes (instead of every other year as default)
						});
				</script>
				
				
			&nbsp;&nbsp;<input type="button" value="взять дату" onclick="OPTS.dateFrom=$('#dateFrom').val(); OPTS.dateTo=$('#dateTo').val();  itemsList(); return false;" />
			<input type="button" onclick="OPTS.dateFrom=''; OPTS.dateTo='';  itemsList(); return false;" value="&times;">
	</div>
	
	
	<div class="section statuses">
		<h1>Доставка:</h1>
		<a class="item <?=!$MODEL['deliveryType'] ? 'active' : ''?>" href="#" onclick="OPTS.deliveryType='';  itemsList(); return false;  ">ВСЕ</a>
	<?php 
	foreach(DeliveryType::$items as $s)
	{?>
		<a class="item <?=$MODEL['deliveryType']->code== $s->code ? 'active' : ''?>" href="#" onclick="OPTS.deliveryType='<?=$s->code?>';  itemsList(); return false; "> <?=$s->name?></a>
	<?php 
	}?>
	</div>
	
	
	
	<div class="section statuses">
		<h1>Оплата:</h1>
		<a class="item <?=!$MODEL['paymentType'] ? 'active' : ''?>" href="#" onclick="OPTS.paymentType='';  itemsList(); return false;  ">ВСЕ</a>
	<?php 
	foreach(PaymentType::$items as $s)
	{?>
		<a class="item <?=$MODEL['paymentType']->code== $s->code ? 'active' : ''?>" href="#" onclick="OPTS.paymentType='<?=$s->code?>';  itemsList(); return false; "><?=$s->name?></a>
	<?php 
	}?>
	</div>
	
	<div class="section id">
		<h1>ID заказчика:</h1>
		<input type="text" name="chosenUserId" id="chosenUserId" value="<?=$MODEL['chosenUserId']?>" style="width: 36px;" />
		&nbsp;&nbsp;<input type="button" value="взять" onclick="OPTS.chosenUserId=$('#chosenUserId').val(); itemsList(); return false; " />&nbsp;<input type="button" value="&times;" onclick="OPTS.chosenUserId=''; itemsList(); return false; " />
	</div>
	
	
	<div class="section id">
		<h1>Тел. заказчика:</h1>
		<input type="text" name="customerPhone" id="customerPhone" value="<?=$MODEL['customerPhone']?>" style="width: 112px;" />
		&nbsp;&nbsp;<input type="button" value="взять номер" onclick="OPTS.customerPhone=$('#customerPhone').val(); itemsList(); return false; " />&nbsp;<input type="button" value="&times;" onclick="OPTS.customerPhone=''; itemsList(); return false; " />
	</div>
	
	
	<div class="section id">
		<h1>E-mail заказчика:</h1>
		<input type="text" name="customerEmail" id="customerEmail" value="<?=$MODEL['customerEmail']?>" style="width: 112px;" />
		&nbsp;&nbsp;<input type="button" value="взять e-mail" onclick="OPTS.customerEmail=$('#customerEmail').val(); itemsList(); return false; " />&nbsp;<input type="button" value="&times;" onclick="OPTS.customerEmail=''; itemsList(); return false; " />
	</div>
	
	
	<div class="section statuses">
		<h1>Валюта:</h1>
		<a class="item <?=!$MODEL['currency'] ? 'active' : ''?>" href="#" onclick="OPTS.currency='';  itemsList(); return false;  ">ВСЕ</a>
	<?php 
	foreach(Currency::$items as $s)
	{?>
		<a class="item <?=$MODEL['currency']->code== $s->code ? 'active' : ''?>" href="#" onclick="OPTS.currency='<?=$s->code?>';  itemsList(); return false; "> <?=$s->code?></a>
	<?php 
	}?>
	</div>
	
	<div class="clear"></div>
</div>



<div class="filter-vals" >
	<div>Статус: <b><?=$orderStatus ? $orderStatus->name : 'ВСЕ'?></b>  
		<!-- ID: <b><?=$chosenId ? $chosenId : 'не указан'?></b>, -->
	</div>
</div>





<p>

<?php
if(count($items))
{?>

	<button onclick="charts()">Статистика</button>
	
	<div style="padding: 10px 0 0 0px; font-size: 16px; ">Заказов: <b><?=$MODEL['totalCount']?></b></div>
	<div style="margin: 0px 0 3px 0; font-size: 10px;  "><?=drawPages($totalCount, $MODEL['p'], $MODEL['elPP'], $onclick="OPTS.p=###; itemsList();", $class="pages");?></div>
	<table class="t orders">
		<tr>
			<th></th>
			<th><a href="#" onclick="OPTS.orderBy='id'; OPTS.desc='<?=$orderBy=='id' ? ($desc ? 0 : 1) : 0?>'; itemsList(); return false; "><nobr># <?=$orderBy=='id' ? ($desc ? $downArrowSign : $upArrowSign) : '' ?></nobr></a> </th>
			<th><a href="#" onclick="OPTS.orderBy='orderStatus'; OPTS.desc='<?=$orderBy=='orderStatus' ? ($desc ? 0 : 1) : 0?>'; itemsList(); return false; "><nobr>Статус <?=$orderBy=='orderStatus' ? ($desc ? $downArrowSign : $upArrowSign) : '' ?></nobr></a> </th>
			
			<th><a href="#" onclick="OPTS.orderBy='dateCreated'; OPTS.desc='<?=$orderBy=='dateCreated' ? ($desc ? 0 : 1) : 0?>'; itemsList(); return false; "><nobr>Дата создания <?=$orderBy=='dateCreated' ? ($desc ? $downArrowSign : $upArrowSign) : '' ?></nobr></a> </th>
			
			<th><a href="#" onclick="OPTS.orderBy='totalQuan'; OPTS.desc='<?=$orderBy=='totalQuan' ? ($desc ? 0 : 1) : 0?>'; itemsList(); return false; "><nobr>Товаров<?=$orderBy=='totalQuan' ? ($desc ? $downArrowSign : $upArrowSign) : '' ?></nobr></a> </th>
			
			<th><a href="#" onclick="OPTS.orderBy='currency'; OPTS.desc='<?=$orderBy=='currency' ? ($desc ? 0 : 1) : 0?>'; itemsList(); return false; "><nobr>Валюта<?=$orderBy=='currency' ? ($desc ? $downArrowSign : $upArrowSign) : '' ?></nobr></a> </th>
			
			<th><a href="#" onclick="OPTS.orderBy='totalSum'; OPTS.desc='<?=$orderBy=='totalSum' ? ($desc ? 0 : 1) : 0?>'; itemsList(); return false; "><nobr>Стоимость<?=$orderBy=='totalSum' ? ($desc ? $downArrowSign : $upArrowSign) : '' ?></nobr></a> </th>
			
			<th><a href="#" onclick="OPTS.orderBy='deliveryType'; OPTS.desc='<?=$orderBy=='deliveryType' ? ($desc ? 0 : 1) : 0?>'; itemsList(); return false; "><nobr>Доставка<?=$orderBy=='deliveryType' ? ($desc ? $downArrowSign : $upArrowSign) : '' ?></nobr></a> </th>
			<th><a href="#" onclick="OPTS.orderBy='paymentType'; OPTS.desc='<?=$orderBy=='paymentType' ? ($desc ? 0 : 1) : 0?>'; itemsList(); return false; "><nobr>Оплата<?=$orderBy=='paymentType' ? ($desc ? $downArrowSign : $upArrowSign) : '' ?></nobr></a> </th>
			
			<th>Заказчик</th>
			<th>Коммент менеджера</th>

            <th></th>
			
		</tr>
		
	<?php 
	foreach($items as $item)
	{
		//vd($item);
	//vd($item->url())?>
		<tr id="item-<?=$item->id?>" class="order-status-<?=$item->orderStatus->code?>">
			<td width="1" class="num"><?=$p*$elPP+(++$i)?>.</td>
			
			<td width="1" style="font-size: 1.1em; font-weight: bold; "><a href="<?=$item->adminUrl()?>" target="_blank">#<?=$item->id?></a></td>
			<td width="1"><?=$item->orderStatus->name?></td>
			
			<td><?=mb_strtolower(Funx::mkDate($item->dateCreated, 'numeric_with_time'), 'utf-8')?></td>
			
			<td align="center"><b><?=$item->totalQuan?></b></td>
			
			<td align="center" >
				<span style="font-size: 1.9em; "><?=$item->currency->sign?></span>
				<?php 
				if($item->currency->code!=Currency::USD)
				{?>
					<div style="font-size: .9em; color: #888; ">1$ = <?=$item->currencyCoef?> <?=$item->currency->sign?></div>
				<?php 	
				}?>
			</td>
			
			<td align="center">
				<b style="font-size:1.2em; "><?=Currency::formatPrice($item->totalSumInCurrency)?> <?=$item->currency->sign?></b>
				<?php 
				if($item->currency->code!=Currency::USD)
				{?>
					<div>(<?=Currency::formatPrice($item->totalSum)?> $)</div>
					<!-- <div style="font-size: .9em; color: #888; ">1$ = <?=$item->currencyCoef?> <?=$item->currency->sign?></div> -->
				<?php 	
				}?>
			</td>
			
			<td align="center">
				<?=$item->deliveryType->name?>
				
				<div><b><?=$item->deliveryPrice>0 ? Currency::formatPrice(Currency::calculatePrice($item->deliveryPrice , $item->currency)).$item->currency->sign: 'бесплатно'?></b></div>
				<?php 
				if($item->currency->code!=Currency::USD && $item->deliveryPrice>0)
				{?>
					<div>(<?=$item->deliveryPrice?> $)</div>
					<!-- <div style="font-size: .9em; color: #888; ">1$ = <?=$item->currencyCoef?> <?=$item->currency->sign?></div> -->
				<?php 	
				}?>
			</td>
			<td><img src="<?=$item->paymentType->icon?>" alt="" title="<?=$item->paymentType->name?>" /><!-- <?=$item->paymentType->name?> --></td>
			

			<td style="line-height: 170%; ">
				<div><b><?=$item->customerName?></b></div>
				<div><a href="mailto:<?=$item->customerEmail?>"><?=$item->customerEmail?></a></div>
				<div><?=User::formatPhone($item->customerPhone)?></div>
			</td>
			
			<td><?=$item->managerComment?></td>


            <td><a href="/admin/emailView?type=order&id=<?=$item->id?>" target="_blank">смотреть письмо с заказом</a></td>
			
		</tr>
	<?php 
	}?>
	</table>
	
	<div style="margin: 7px 0 30px 0; font-size: 10px; "><?=drawPages($totalCount, $MODEL['p'], $MODEL['elPP'], $onclick="OPTS.p=###; itemsList();", $class="pages");?></div>
<?php 
}
else
{?>
	&nbsp;&nbsp;&nbsp;&nbsp;Заказов нет. 
<?php 
}
?>












<iframe name="frame7" style="display: none; "></iframe>


