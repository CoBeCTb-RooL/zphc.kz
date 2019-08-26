<?php
$cat = $MODEL['cat'];
$list = $MODEL['list']; 
$i=0;
//vd($cat);




if($cat)
{
	$crumbs[] = '<a href="#" onclick="catsList(0)">КОРЕНЬ</a>';
	foreach($cat->elderCats as $c)
		$crumbs[] = '<a href="#" onclick="catsList('.$c->id.')">'.$c->name.'</a>';
}
?>


<?php if($MODEL['error']){ echo ''.$MODEL['error'].''; return; }?>


<style>
	.cat-status-active{}
	.cat-status-inactive{opacity: .4;  }
	
	
	.id{font-weight: bold !important; }
	.num{}
	.name a{font-weight: bold; font-size: 1.3em; }
	
	.statuses{}
	.statuses .item{ margin: 0 0 3px 0; }
	
	.unit-status-<?=Status::ACTIVE?>{}
	.unit-status-<?=Status::INACTIVE?>{opacity: .3; }
	.unit-item{padding: 0 0 1px 0;}
</style>





<div class="crumbs" style="margin: 0 0 15px 0; "> 
<?=join('&nbsp;/&nbsp;', $crumbs)?>
</div>

<h1>
<?php 
if($cat)
{?>
	<a href="#" onclick="catsList(<?=$cat->pid?>)" style="opacity: .4;  font-size: .8em; text-decoration: none;  "><i class="fa fa-backward"></i> назад</a> | 
<?php 
}?>

	<span onclick="cat(CHOSEN_CAT)" style="cursor: pointer; "><?=$cat ? $cat->name : 'КОРЕНЬ'?></span>
</h1>



<?php 
if(count($list))
{?>
<form id="list-form" action="/<?=ADMIN_URL_SIGN?>/catalogSimple/catsListSubmit" target="frame7" onsubmit="catsListSubmitStart()" >
	<table class="t">
		<tr>
			<!--<th>#</th>-->
			<th></th>
			<th>id</th>
			<th>Категория</th>
			
			<?php 
			foreach(CategorySimple::$fields as $f)
			{
				if(!$f->isShownInList || $f->htmlName=='name') continue; ?>
				<th><?=$f->label?></th>
				
			<?php 	
			}?>
			
			<th></th>
			
			<th>idx</th>
			
			<th>Товары</th>
			<th>Удалить</th>
			
		</tr>
	<?php 
	foreach($list as $key=>$cat)
	{
		$advCount=0;
		//vd($cat);
		?>
		<tr id="cat-<?=$cat->id?>" class="cat-status-<?=$cat->status ? $cat->status->code : ''?> " ondblclick="catEdit(<?=$cat->id?>)">
			<!--<td width="1" class="num"><?=++$i?>.</td>-->
			<td width="1"  class="cat-status-switcher">
				<a href="#" id="cat-status-switcher-<?=$cat->id?>" onclick="catSwitchStatus(<?=$cat->id?>); return false; " ><?=$cat->status->icon?></a>
			</td>
			<td width="1" class="id"><?=$cat->id?></td>
			<td class="name">
				<a href="#" onclick="catsList(<?=$cat->id?>);  "><?=$cat->name?></a>
				<div style="font-size: .8em; margin: 0 0 0 7px; ">подкат.: <b><?=$cat->subCatsCount?></b></div>
			</td>
			
			
			<?php 
			foreach(CategorySimple::$fields as $f)
			{
				if(!$f->isShownInList || $f->htmlName=='name') continue; ?>
				
				<td class="<?=$f->isHighlighted ? 'marked' : '' ?>"><?=$f->listHTML($cat->{$f->htmlName})?></td>
				
			<?php 	
			}?>
			
			
			<td><a href="#" onclick="catEdit(<?=$cat->id?>); return false; ">ред.</a></td>
			
			
			
			
			<td><input type="text" size="2" name="idx[<?=$cat->id?>]" value="<?=($key+1)*10?>" style="font-size: 10px; " /></td>
			
			<td><a href="#" onclick="CatalogSimple.ITEMS_OPTS.catId=<?=$cat->id?>; CatalogSimple.itemsList(); return false; ">Товаров: <b><?=$cat->itemsCount?></b></a></td>
			
			<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="#" onclick="if(confirm('Удалить категорию?')){catDelete(<?=$cat->id?>);} return false; " style="color: red; ">&times; удалить</a> </td>
		</tr>
	<?php 
	}?>
	</table>
	
	<input type="submit" value="сохранить изменения" style="display: block; margin: 12px 0 0 0 ;"  />
</form>
<?php 
}
else
{?>
	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Категорий нет. 
<?php 	
}?>

<button style="display: block; margin: 30px 0 0 0 ;" onclick="catEdit('', CHOSEN_CAT)" >+ категория</button>


<iframe name="frame7" style="display: none; "></iframe>