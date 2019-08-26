<?php 
$status = $MODEL['status'];
$items = $MODEL['items'];
$orderBy = $MODEL['orderBy'];
$desc = $MODEL['desc'];

$chosenId = $MODEL['chosenId'];
$totalCount = $MODEL['totalCount'];
$p = $MODEL['p'];
$elPP = $MODEL['elPP'];

$i=0;
//vd($cat);
//vd($list);
$upArrowSign = '▲';
$downArrowSign = '▼';

$statuses = array(
		Status::code(Status::ACTIVE),
		Status::code(Status::INACTIVE)
);
?>









<style>
	.item-status-<?=Status::ACTIVE?>{}
	.item-status-<?=Status::INACTIVE?>{opacity: .4;  }
	.item-status-<?=Status::MODERATION?>{}
	.item-status-<?=Status::DELETED?>{}
	
	.t .th a{white-space: nowrap; }
</style>





<div class="filters">
	
	<div class="section statuses">
		<h1>Статус:</h1>
		<a class="item <?=!$status ? 'active' : ''?>" href="#" onclick="OPTS.status='';  itemsList(); return false;  ">ВСЕ</a>
	<?php 
	foreach($statuses as $s)
	{
		//$s = Status::code($statusCode);
		?>
		<a class="item <?=$status->num == $s->num ? 'active' : ''?>" href="#" onclick="OPTS.status='<?=$s->num?>';  itemsList(); return false; "><?=$s->icon?> <?=$s->name?></a>
	<?php 
	}?>
	</div>
	
	
	
	
	
	
	<div class="clear"></div>
</div>



<div class="filter-vals" >
	<div>Статус: <b><?=$status ? $status->name : 'ВСЕ'?></b>  
		<!-- ID: <b><?=$chosenId ? $chosenId : 'не указан'?></b>, -->
	</div>
</div>





<p>

<?php
if(count($items))
{?>
	<div style="padding: 10px 0 0 0px; font-size: 16px; ">Подарочных связок: <b><?=$MODEL['totalCount']?></b></div>
	<div style="margin: 0px 0 3px 0; font-size: 10px;  "><?=drawPages($totalCount, $MODEL['p'], $MODEL['elPP'], $onclick="OPTS.p=###; itemsList();", $class="pages");?></div>
	<table class="t">
		<tr>
			<th>#</th>
			
			<th><a href="#" onclick="OPTS.orderBy='status'; OPTS.desc='<?=$orderBy=='status' ? ($desc ? 0 : 1) : 0?>'; itemsList(); return false; "><nobr>Ст <?=$orderBy=='status' ? ($desc ? $downArrowSign : $upArrowSign) : '' ?></nobr></a> </th>
			
			<th><a href="#" onclick="OPTS.orderBy='id'; OPTS.desc='<?=$orderBy=='id' ? ($desc ? 0 : 1) : 0?>'; itemsList(); return false; "><nobr>id <?=$orderBy=='id' ? ($desc ? $downArrowSign : $upArrowSign) : '' ?></nobr></a> </th>
			
			
			<?php 
			foreach(Present::$fields as $f)
			{
				if(!$f->isShownInList) continue; ?>
				<th>
					<a href="#" onclick="OPTS.orderBy='<?=$f->htmlName?>'; OPTS.desc='<?=$orderBy==$f->htmlName ? ($desc ? 0 : 1) : 0?>'; itemsList(); return false; "><?=$f->label?> <?=$orderBy==$f->htmlName ? ($desc ? $downArrowSign : $upArrowSign) : '' ?></a>
				</th>
				
			<?php 	
			}?>
			<th>Товары-триггеры</th>
			<th>Подарки</th>
			<th>ред.</th>
			<th>удалить</th>
		</tr>
		<?php 
		//vd(Discount::$fields)?>
	<?php 
	foreach($items as $item)
	{
	//vd($item)?>
		<tr id="item-<?=$item->id?>" class="item-status-<?=$item->status->code?>">
			<td width="1" class="num"><?=$p*$elPP+(++$i)?>.</td>
			<td width="1"  class="item-status-switcher" style="text-align: center; ">
				<a href="#" id="item-status-switcher-<?=$item->id?>" onclick="itemSwitchStatus(<?=$item->id?>); return false; " ><?=$item->status->icon?></a>
			</td>
			<td width="1"><?=$item->id?></td>
			
			<?php 
			foreach(Present::$fields as $f)
			{
				if(!$f->isShownInList) continue; ?>
				<td class="<?=$f->isHighlighted ? 'marked' : '' ?>">
					<?=$f->listHTML($item->{$f->htmlName})?>
					
				</td>
				
			<?php 	
			}?>
			<td>
			<?php 
			$i=0;
			foreach($item->triggerProducts as $p)
			{?>
				<div style="margin: 0 0 3px 0; "> <?=++$i?>. <a href="<?=$p->product->url()?>" target="_blank"><?=$p->product->name?></a> &times;<?=$p->param1?></div>
			<?php 	
			}?>
			</td>
			
			<td>
			<?php 
			$i=0;
			foreach($item->presentProducts as $p)
			{?>
				<div style="margin: 0 0 3px 0; "> <?=++$i?>. <a href="<?=$p->product->url()?>" target="_blank"><?=$p->product->name?></a> &times;<?=$p->param1?></div>
			<?php 	
			}?>
			</td>
			
			<td><a href="#" onclick="itemEdit(<?=$item->id?>); return false; ">ред. </a></td>
			<td><a href="#" onclick="if(confirm('Удалить товар?')){itemDelete(<?=$item->id?>);} return false; " style="color: red; ">&times; удалить</a></td>
		</tr>
	<?php 
	}?>
	</table>
	
	<div style="margin: 7px 0 30px 0; font-size: 10px; "><?=drawPages($totalCount, $MODEL['p'], $MODEL['elPP'], $onclick="OPTS.p=###; itemsList();", $class="pages");?></div>
<?php 
}
else
{?>
	&nbsp;&nbsp;&nbsp;&nbsp;Подарочных связок нет. 
<?php 
}
?>




<p><button type="button" onclick="itemEdit(''); ">+ Добавить подарочную связку</button>













<iframe name="frame7" style="display: none; "></iframe>


