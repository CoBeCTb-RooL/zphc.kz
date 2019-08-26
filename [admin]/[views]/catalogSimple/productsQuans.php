<?php 
//vd($MODEL);
?>


<?php if($MODEL['error']){ echo ''.$MODEL['error'].''; return; }?>


<h1>Наличие и цена</h1>




<form action="" method="post">


	<table class="t">
	<?php 
	foreach($MODEL['catalog'] as $cat)
	{?>
		<tr>
			<th colspan="3"><?=$cat->name?></th>
		</tr>
		<?php 
		foreach($cat->products as $product)
		{?>
		<tr>
			<td><a href="/<?=UPLOAD_IMAGES_REL_DIR?><?=$product->photo?>" class="highslide" onclick="return hs.expand(this)" style="vertical-align: middle; "><img src="<?=$product->photo ? Media::img($product->photo) : Funx::noPhotoSrc()?>&width=50" alt="<?=$product->name?>" /></a><a href="<?=$product->url()?>" target="_blank" style="display: inline-block; vertical-align: middle; width: 200px; "><?=$product->name?></a></td>
			
			<td>Цена: <input type="text" name="price[<?=$product->id?>]" value="<?=$product->price?>"  style="width: 55px; "/> $</td>
			
			<td>Остаток: <input type="text" name="quan[<?=$product->id?>]" value="<?=$product->stock?>"  style="width: 25px; "/></td>
							
		</tr>
		<?php 	
		}?>
	<?php 
	}?>
	</table>


	<input type="submit" name="go_btn" value="Сохранить"  />

</form>