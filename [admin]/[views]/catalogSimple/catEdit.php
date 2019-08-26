<?php 
$item = $MODEL['cat'];
$currentCatId = $_REQUEST['currentCat'];
?>


<?php if($MODEL['error']){ echo ''.$MODEL['error'].''; return; }?>


<h1><?=$item ? $item->name : 'Категория'?><span class="title-gray"> : <?=$item ? 'Редактирование' : 'Добавление'?></span></h1>

<form id="cat-edit-form" enctype="multipart/form-data" method="post" action="/<?=ADMIN_URL_SIGN?>/catalogSimple/catEditSubmit" target="frame7" onsubmit="catEditSubmitStart(); " >
	<input type="hidden" name="id" value="<?=$item->id?>" />
	
	
	
	<!-- 
	<div class="field-wrapper">
		<span class="label" >Название<span class="required">*</span>: </span>
		<span class="value" >
			<input type="text" name="name" value="<?=$item->name?>" />
		</span>
		<div class="clear"></div>
	</div>
	-->
	
	
	<?php
	foreach(CategorySimple::$fields as $f)
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
	
	
	
	<!-- 	
	<div class="field-wrapper">
		<span class="label">Расположение: </span>
		<span class="value">
			<select name="pid">
				<option value="0">-КОРЕНЬ-</option>
				<?=CategorySimple::drawTreeSelect(0, $self_id=$item->id,  $idToBeSelected=$item->pid ? $item->pid : $currentCatId, $level=0)?>
			</select>
		</span>
		<div class="clear"></div>
	</div>
	 -->
	
	
	
	<input type="submit" value="Сохранить" />
	<div class="loading" style="display: none; ">секунду...</div>
	
</form>



<iframe name="frame7" style="display: ; "></iframe>





