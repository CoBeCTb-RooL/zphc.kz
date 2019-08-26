<?php 
$status = $MODEL['status'];
$isActive = $MODEL['isActive'];
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
	
	
	
	<div class="section isActive">
		<h1>Срок годности:</h1>
		<a class="item <?=!$MODEL['isActive'] ? 'active' : ''?>" href="#" onclick="OPTS.isActive='';  itemsList(); return false;  ">ВСЕ</a>
		<a class="item <?=$MODEL['isActive'] ? 'active' : ''?>" href="#" onclick="OPTS.isActive='1';  itemsList(); return false;  ">Текущие </a>
	</div>

	
	<!-- <div class="section id">
		<h1>ID товара:</h1>
		<input type="text" name="chosenId" id="id" value="<?=$chosenId?>" style="width: 40px;" />
		&nbsp;&nbsp;<input type="button" value="взять id" onclick="OPTS.chosenId=$('#id').val(); itemsList(); return false; " />&nbsp;<input type="button" value="&times;" onclick="OPTS.chosenId=''; itemsList(); return false; " />
	</div> -->
	
	
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
	<div style="padding: 10px 0 0 0px; font-size: 16px; ">Акций: <b><?=$MODEL['totalCount']?></b></div>
	<div style="margin: 0px 0 3px 0; font-size: 10px;  "><?=drawPages($totalCount, $MODEL['p'], $MODEL['elPP'], $onclick="OPTS.p=###; itemsList();", $class="pages");?></div>
	<table class="t">
		<tr>
			<th>#</th>
			
			<th><a href="#" onclick="OPTS.orderBy='status'; OPTS.desc='<?=$orderBy=='status' ? ($desc ? 0 : 1) : 0?>'; itemsList(); return false; "><nobr>Ст <?=$orderBy=='status' ? ($desc ? $downArrowSign : $upArrowSign) : '' ?></nobr></a> </th>
			
			<th><a href="#" onclick="OPTS.orderBy='id'; OPTS.desc='<?=$orderBy=='id' ? ($desc ? 0 : 1) : 0?>'; itemsList(); return false; "><nobr>id <?=$orderBy=='id' ? ($desc ? $downArrowSign : $upArrowSign) : '' ?></nobr></a> </th>
			
			
			<?php 
			foreach(Action::$fields as $f)
			{
				if(!$f->isShownInList) continue; ?>
				<th>
					<a href="#" onclick="OPTS.orderBy='<?=$f->htmlName?>'; OPTS.desc='<?=$orderBy==$f->htmlName ? ($desc ? 0 : 1) : 0?>'; itemsList(); return false; "><?=$f->label?> <?=$orderBy==$f->htmlName ? ($desc ? $downArrowSign : $upArrowSign) : '' ?></a>
				</th>
				
			<?php 	
			}?>
			<th>Товары</th>
			<th>ред.</th>
			<th>удалить</th>
		</tr>
		<?php 
		//vd(Action::$fields)?>
	<?php 
	foreach($items as $item)
	{
	//vd($item->url())?>
		<tr id="item-<?=$item->id?>" class="item-status-<?=$item->status->code?>">
			<td width="1" class="num"><?=$p*$elPP+(++$i)?>.</td>
			<td width="1"  class="item-status-switcher" style="text-align: center; ">
				<a href="#" id="item-status-switcher-<?=$item->id?>" onclick="itemSwitchStatus(<?=$item->id?>); return false; " ><?=$item->status->icon?></a>
			</td>
			<td width="1"><?=$item->id?></td>
			
			<?php 
			foreach(Action::$fields as $f)
			{
				if(!$f->isShownInList) continue; ?>
				<td class="<?=$f->isHighlighted ? 'marked' : '' ?>">
					<?=$f->listHTML($item->{$f->htmlName})?>
					<?php 
					# 	если ДАТА ДО
					if($f->htmlName == 'dateTill' && $item->dateTill < date('Y-m-d H:i:s') )
					{?>
						<div style="color: red">Просрочена</div>
					<?php 	
					}?>
					
					<?php
					# 	если НАИМЕНОВАНИЕ
					if($f->htmlName == 'name')
					{?>
						<div><a href="<?=$item->url()?>" target="_blank" style="font-weight: normal; font-size: 10px; ">смотреть на сайте</a></div>
					<?php 	
					}?>
				</td>
				
			<?php 	
			}?>
			<td>
			<?php 
			$i=0;
			foreach($item->relatedProducts as $p)
			{?>
				<div style="margin: 0 0 3px 0; "> <?=++$i?>. <a href="<?=$p->product->url()?>" target="_blank"><?=$p->product->name?></a></div>
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
	&nbsp;&nbsp;&nbsp;&nbsp;Акций нет. 
<?php 
}
?>




<p><button type="button" onclick="itemEdit(''); ">+ Добавить акцию</button>













<iframe name="frame7" style="display: none; "></iframe>


