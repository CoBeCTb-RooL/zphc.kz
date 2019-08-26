<?php 
$chosenCat = $MODEL['cat'];
$status = $MODEL['status'];
$items = $MODEL['items'];
$orderBy = $MODEL['orderBy'];
$desc = $MODEL['desc'];

$cats = $MODEL['cats'];
//vd($cats);

$chosenId = $MODEL['chosenId'];
$totalCount = $MODEL['totalCount'];
$p = $MODEL['p'];
$elPP = $MODEL['elPP'];
//vd($orderBy);
//vd($desc);
$i=0;
//vd($cat);
//vd($list);
$upArrowSign = '▲';
$downArrowSign = '▼';

if($chosenCat)
{
	$crumbs[] = '<a href="#cat:0" onclick="cat(0)">КОРЕНЬ</a>';
	foreach($chosenCat->elderCats as $c)
		$crumbs[] = '<a href="#cat:'.$c->id.'" onclick="cat('.$c->id.')">'.$c->name.'</a>';
}



$statuses = array(
		Status::code(Status::ACTIVE),
		Status::code(Status::INACTIVE)
);
?>






<script>
if(typeof CatalogSimple == 'undefined')
	document.write('\<script src="/<?=ADMIN_DIR?>/js/catalogSimple.js" type="text/javascript"></script\>')
</script>



<style>
	.product-status-<?=Status::ACTIVE?>{}
	.product-status-<?=Status::INACTIVE?>{opacity: .4;  }
	.product-status-<?=Status::MODERATION?>{}
	.product-status-<?=Status::DELETED?>{}
	
	.t .th a{white-space: nowrap; }
</style>







<?php 
if(!$MODEL['noTop'])
{?>
	<h1>
		<a href="#cat:<?=$chosenCat->pid?>" onclick="$('#cats').slideDown('fast'); $('#items').slideUp('fast')" style="opacity: .4;  font-size: .8em; text-decoration: none;  "><i class="fa fa-backward"></i> назад</a> | 
	<?php 
	if($chosenCat)
	{?>
		<span onclick="CatalogSimple.itemsList(<?=$chosenCat->id?>);" style="cursor: pointer; "><?=$chosenCat ? $chosenCat->name : 'КОРЕНЬ'?></span>
		<!--<button onclick="CatalogSimple.itemsList(<?=$chosenCat->id?>);" style="font-size: 10px; margin: 0 0 0 40px;"><i class="fa fa-refresh"></i> обновить</button>-->
	<?php 
	}?>
	</h1>
<?php 
}?>







<div class="filters">
	
	<div class="section statuses">
		<h1>Статус:</h1>
		<a class="item <?=!$status ? 'active' : ''?>" href="#" onclick="CatalogSimple.ITEMS_OPTS.status='';  CatalogSimple.itemsList(); return false;  ">ВСЕ</a>
	<?php 
	foreach($statuses as $s)
	{
		//$s = Status::code($statusCode);
		?>
		<a class="item <?=$status->num == $s->num ? 'active' : ''?>" href="#" onclick="CatalogSimple.ITEMS_OPTS.status='<?=$s->num?>';  CatalogSimple.itemsList(); return false; "><?=$s->icon?> <?=$s->name?></a>
	<?php 
	}?>
	</div>
	
	
	
	
	<div class="section cats">
		<h1>Категория:</h1>
		<select name="" id="" style="font-size: 11px; " onchange="CatalogSimple.ITEMS_OPTS.catId=$(this).val(); CatalogSimple.itemsList(); return false;">
			<option value="">-все-</option>
		<?php 
		foreach($cats as $cat)
		{?>
			<option style="font-weight: bold; " value="<?=$cat->id?>" <?=$chosenCat->id==$cat->id ? ' selected="selected" ' : ''?>><?=$cat->name?></option>
			<?php 
			foreach($cat->subCats as $subcat)
			{?>
			<option value="<?=$subcat->id?>" <?=$chosenCat->id==$subcat->id ? ' selected="selected" ' : ''?>>&nbsp;&nbsp;&nbsp;&nbsp;<?=$subcat->name?></option>
			<?php 
			}?>
		<?php 
		}?>
		</select>
	</div>
	
	
	
	
	<div class="section id">
		<h1>ID товара:</h1>
		<input type="text" name="chosenId" id="id" value="<?=$chosenId?>" style="width: 40px;" />
		&nbsp;&nbsp;<input type="button" value="взять id" onclick="CatalogSimple.ITEMS_OPTS.chosenId=$('#id').val(); CatalogSimple.itemsList(); return false; " />&nbsp;<input type="button" value="&times;" onclick="CatalogSimple.ITEMS_OPTS.chosenId=''; CatalogSimple.itemsList(); return false; " />
	</div>
	
	
	<div class="clear"></div>
</div>



<div class="filter-vals" >
	
	<div>Статус: <b><?=$status ? $status->name : 'ВСЕ'?></b>,  
	Категория: <b><?=$chosenCat ? $chosenCat->name : 'ВСЕ'?></b>, 
	ID: <b><?=$chosenId ? $chosenId : 'не указан'?></b>,
</div>





<p>

<?php
if(count($items))
{?>
	<form action="/<?=ADMIN_URL_SIGN?>/catalogSimple/productsListSubmit" target="frame7">
		<div style="padding: 10px 0 0 0px; font-size: 16px; ">Товаров: <b><?=$MODEL['totalCount']?></b></div>
		<div style="margin: 0px 0 3px 0; font-size: 10px;  "><?=drawPages($totalCount, $MODEL['p'], $MODEL['elPP'], $onclick="CatalogSimple.ITEMS_OPTS.p=###; CatalogSimple.itemsList();", $class="pages");?></div>
		<table class="t">
			<tr>
				<th>#</th>
				
				<th><a href="#" onclick="CatalogSimple.ITEMS_OPTS.orderBy='status'; CatalogSimple.ITEMS_OPTS.desc='<?=$orderBy=='status' ? ($desc ? 0 : 1) : 0?>'; CatalogSimple.itemsList(); return false; "><nobr>Ст <?=$orderBy=='status' ? ($desc ? $downArrowSign : $upArrowSign) : '' ?></nobr></a> </th>
				
				<th><a href="#" onclick="CatalogSimple.ITEMS_OPTS.orderBy='id'; CatalogSimple.ITEMS_OPTS.desc='<?=$orderBy=='id' ? ($desc ? 0 : 1) : 0?>'; CatalogSimple.itemsList(); return false; "><nobr>id <?=$orderBy=='id' ? ($desc ? $downArrowSign : $upArrowSign) : '' ?></nobr></a> </th>
				
				
				<?php 
				foreach(ProductSimple::$fields as $f)
				{
					if(!$f->isShownInList) continue; ?>
					<th>
						<a href="#" onclick="CatalogSimple.ITEMS_OPTS.orderBy='<?=$f->htmlName?>'; CatalogSimple.ITEMS_OPTS.desc='<?=$orderBy==$f->htmlName ? ($desc ? 0 : 1) : 0?>'; CatalogSimple.itemsList(); return false; "><?=$f->label?> <?=$orderBy==$f->htmlName ? ($desc ? $downArrowSign : $upArrowSign) : '' ?></a>
					</th>
					
				<?php 	
				}?>
				<th>ред.</th>
				<th><a href="#" onclick="CatalogSimple.ITEMS_OPTS.orderBy='idx'; CatalogSimple.ITEMS_OPTS.desc='<?=$orderBy=='idx' ? ($desc ? 0 : 1) : 0?>'; CatalogSimple.itemsList(); return false; "><nobr>Порядок <?=$orderBy=='idx' ? ($desc ? $downArrowSign : $upArrowSign) : '' ?></nobr></a></th>
				<th>удалить</th>
			</tr>
			<?php 
			//vd(ProductSimple::$fields)?>
		<?php 
		foreach($items as $item)
		{
		//vd($item->url())?>
			<tr id="product-<?=$item->id?>" class="product-status-<?=$item->status->code?>">
				<td width="1" class="num"><?=$p*$elPP+(++$i)?>.</td>
				<td width="1"  class="product-status-switcher" style="text-align: center; ">
					<a href="#" id="product-status-switcher-<?=$item->id?>" onclick="CatalogSimple.productSwitchStatus(<?=$item->id?>); return false; " ><?=$item->status->icon?></a>
				</td>
				<td width="1"><?=$item->id?></td>
				
				<?php 
				foreach(ProductSimple::$fields as $f)
				{
					if(!$f->isShownInList) continue; ?>
					<td class="<?=$f->isHighlighted ? 'marked' : '' ?>"><?=$f->listHTML($item->{$f->htmlName})?></td>
					
				<?php 	
				}?>
				<td><a href="#" onclick="CatalogSimple.productEdit(<?=$item->id?>); return false; ">ред. </a></td>
				<td><input type="text" name="idx[<?=$item->id?>]" size="2" value="<?=$item->idx?>" /></td>
				<td><a href="#" onclick="if(confirm('Удалить товар?')){CatalogSimple.productDelete(<?=$item->id?>);} return false; " style="color: red; ">&times; удалить</a></td>
			</tr>
		<?php 
		}?>
		</table>
		
		<div style="margin: 7px 0 30px 0; font-size: 10px; "><?=drawPages($totalCount, $MODEL['p'], $MODEL['elPP'], $onclick="CatalogSimple.ITEMS_OPTS.p=###; CatalogSimple.itemsList();", $class="pages");?></div>
		
		<input type="submit" value="Сохранить порядок продуктов" />
	</form>
<?php 
}
else
{?>
	&nbsp;&nbsp;&nbsp;&nbsp;Товары не найдены. 
<?php 
}
?>



<p/>
<p/>
<button type="button" onclick="CatalogSimple.productEdit('', CatalogSimple.ITEMS_OPTS.catId); ">+ Добавить товар</button>













<iframe name="frame7" style="display: none; "></iframe>


