<?php 
//vd($MODEL);

//vd(ProductSimple::$optPrices);

?>


<?php if($MODEL['error']){ echo ''.$MODEL['error'].''; return; }?>


<h1>Оптовые цены</h1>




<form action="" method="post">


	<table class="t">
	<?php 
	foreach($MODEL['catalog'] as $cat)
	{?>
		<tr>
			<th colspan="<?=count(ProductSimple::$optPrices)+3?>"><?=$cat->name?></th>
		</tr>
		<?php 
		foreach($cat->products as $product)
		{
		//vd($product->optPricesArr)?>
		<tr>
			<td><br><input type="text" name="idxOpt[<?=$product->id?>]" value="<?=$product->idxOpt?>" size="3" style="text-align: center; "/></td>
			<td><a href="/<?=UPLOAD_IMAGES_REL_DIR?><?=$product->photo?>" class="highslide" onclick="return hs.expand(this)" style="vertical-align: middle; "><img src="<?=$product->photo ? Media::img($product->photo) : Funx::noPhotoSrc()?>&width=50" alt="<?=$product->name?>" /></a><a href="<?=$product->url()?>" target="_blank" style="display: inline-block; vertical-align: middle; width: 200px; font-size: 14px;  "><?=$product->name?></a></td>
			<td style="text-align: center; ">розница: <br /><b><?=$product->price?></b></td>
			<?php 
			foreach(ProductSimple::$optPrices as $sum=>$isShown)
			{?>
			<td style="padding: 10px 16px; ">
				<div style="font-size: 10px; color: #777; text-align: center;  "><?=Funx::numberFormat($sum)?>$</div>
				<input type="text" name="price[<?=$product->id?>][<?=$sum?>]" style="width: 40px; text-align: center;" value="<?=$product->optPricesArr[$sum] ? Currency::formatPrice($product->optPricesArr[$sum]) : ''?>"  />
			</td>
			<?php 	
			}?>
							
		</tr>
		<?php 	
		}?>
	<?php 
	}?>
	</table>


	<input type="submit" name="go_btn" value="Сохранить"  />

</form>