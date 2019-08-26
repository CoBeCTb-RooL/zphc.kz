<?php 
$item = $MODEL['item'];
$currentCatId = $_REQUEST['currentCat'];
?>


<?php if($MODEL['error']){ echo ''.$MODEL['error'].''; return; }?>


<h1><?=$item ? $item->name : 'Категория'?><span class="title-gray"> : <?=$item ? 'Редактирование' : 'Добавление'?></span></h1>

<form id="product-edit-form" enctype="multipart/form-data" method="post" action="/<?=ADMIN_URL_SIGN?>/catalogSimple/productEditSubmit" target="frame81" onsubmit="CatalogSimple.productEditSubmitStart(); " >
	<input type="hidden" name="id" value="<?=$item->id?>" />
	

<?php
foreach(ProductSimple::$fields as $f)
{?>
	<div class="field-wrapper">
		<span class="label" ><?=$f->label?><?=$f->isRequired ? '<span class="required">*</span>' : ''?>: </span>
		<span class="value" >
			<?=$f->editHTML($item->{$f->htmlName})?>
			<!-- <input type="text" name="name" value="<?=$item->name?>" /> -->
		</span>
		<div class="clear"></div>
	</div>
<?php 	
}
?>
	
	
	
	<div class="field-wrapper">
		<span class="label">Расположение: </span>
		<span class="value">
			<select name="catId" id="catId">
				<option value="0">-КОРЕНЬ-</option>
				<?=CategorySimple::drawTreeSelect(0, $selfId=null,  $idToBeSelected=$item->catId ? $item->catId : $currentCatId, $level=0)?>
			</select>
		</span>
		<div class="clear"></div>
	</div>
	
	
	
	
	<input type="submit" value="Сохранить" />
	<div class="loading" style="display: none; ">секунду...</div>
	
</form>



<iframe name="frame81" style="display: ; "></iframe>





