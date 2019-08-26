<?php 
$item = $MODEL['item'];
?>


<?php if($MODEL['error']){ echo ''.$MODEL['error'].''; return; }?>


<h1><?=$item ? $item->name : 'Скидка'?><span class="title-gray"> : <?=$item ? 'Редактирование' : 'Добавление'?></span></h1>

<form id="item-edit-form" enctype="multipart/form-data" method="post" action="/<?=ADMIN_URL_SIGN?>/discounts/itemEditSubmit" target="frame81" onsubmit="itemEditSubmitStart(); " >
	<input type="hidden" name="id" value="<?=$item->id?>" />
	

<?php
foreach(Discount::$fields as $f)
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
		<span class="label" >Привязанные товары: </span>
		<span class="value" >
			<div style="max-height: 300px; overflow-y: auto; width: 600px;  ">
			<?php 
			foreach($MODEL['catalog'] as $cat)
			{?>
				<div class="cat">
					<div class="title">
						<span style="font-size: 1.2em; font-weight: bold; font-style: italic;  display: inline-block; margin: 10px 0 0 0; "><?=$cat->name?></span>
						<div class="products">
				<?php
				$relatedProducts = array();
				foreach($MODEL['item']->relatedProducts as $p)
				{
					$relatedProducts[] = $p->productId;
					$quans[$p->productId] = $p->param1;
				}
				//vd($relatedProducts);
				foreach($cat->products as $product)
				{?>
							<div class="product" style="margin: 0 0 7px 40px; vertical-align: middle; ">
								<label>
									<input type="checkbox" name="products[<?=$product->id?>]"  style="vertical-align: middle; " <?=(in_array($product->id, $relatedProducts) ? ' checked="checked" ' : '')?> />
									<a href="/<?=UPLOAD_IMAGES_REL_DIR?><?=$product->photo?>" class="highslide" onclick="return hs.expand(this)" style="vertical-align: middle; "><img src="<?=$product->photo ? Media::img($product->photo) : Funx::noPhotoSrc()?>&width=50" alt="<?=$product->name?>" /></a> 
									<span class="product-title" style="display: inline-block; vertical-align: middle; "><?=$product->name?></span>
									 <!-- &times;<input type="text" name="quans[<?=$product->id?>]" value="<?=$quans[$product->id]?>"  style="width: 15px; "/> -->
								</label>
							</div>
				<?php 	
				}?>
						</div>
					</div>
				</div>
			<?php 	
			}?>
			</div>
		</span>
		<div class="clear"></div>
	</div>
	
	
	
	
	<input type="submit" value="Сохранить" />
	<div class="loading" style="display: none; ">секунду...</div>
	
</form>



<iframe name="frame81" style="display: none; "></iframe>





