<?php
$arr = array();
foreach($MODEL['catalog'] as $cat)
{
	$products = array();
	foreach($cat->products as $product)
	{
		$products[$product->name][] = $product;
	}
	$cat->products = $products;
	$arr[] = $cat;
}
?>

<!--крамбсы-->
<? Core::renderPartial(SHARED_VIEWS_DIR.'/crumbs.php', $MODEL['crumbs']);?>
<!--//крамбсы-->



<style>
.tbl{text-align: center; width: 100%; }
.tbl tr{}
.tbl td, .tbl th{ border: 1px solid #000; /*padding: 5px 12px;*/ font-size: 12px;  text-align: center; padding: 7px 5px;  }
.tbl th{white-space: nowrap; font-weight: normal; }
.hdr{background: #0669b3; color: #fff; }
.product-name{text-align: center !important; /*width: 280px !important;*/ font-weight: bold;   }
.product-name a{font-size: 12px; }
.doze{/*width: 160px;*/ text-align: center !important; }
.doze a{text-decoration: none ; }
.tbl .price{white-space: nowrap; min-width: 80px; height: 33px !important; vertical-align: middle;  }
.tbl tr:hover{/*background: #eee;*/}
</style>


<div style="text-align: center; ">
<div style="margin: 0 auto; width: 1000px; ">

	<?=$MODEL['textBefore']->attrs['descr']?>

	<table  class="tbl" style="border-bottom: 0px solid red; ">
		<tr height="176">
			<td align="left" height="176" style="vertical-align: middle; border-bottom: 0px solid red; border-right: 0px solid red;"><img src="/img/logo.png" width="350" /></td>
			<td style="text-align: center ; border-bottom: 0px solid red; border-left: 0px solid red;">
				
				<?=$MODEL['textTableTop']->attrs['descr']?>
			</td>
		</tr>
	</table>

	<table class="tbl" width="100%">
		<tr class="hdr">
			<th>Наименование</th>
			<th>Фасовка</th>
			<th>Розница</th>
			<?php 
			foreach(ProductSimple::$optPrices as $sum=>$isShown)
			{
				if(!$isShown)continue;?>
			<th><?=Funx::numberFormat($sum)?> $</th>
			<?php 	
			}?>
		</tr>
	<?php 
	foreach($MODEL['catalog'] as $cat)
	{?>
		<tr class="hdr">
			<td colspan="<?=count(ProductSimple::$optPrices)+1?>"><?=$cat->name?></td>
		</tr>
		<?php 
		foreach($cat->products as $name=>$products)
		{
			$count = count($products);
			$i=0;
			//vd($count);
			?>
			
			<?php 
			foreach($products as $product)
			{
				/*if($product->id == 53)
					vd($product->optPricesArr);*/
				if(!$product->optPricesArr)
					continue;
				?>
			<tr>
				<?php 
				if($i==0)
				{?>
					<td class="product-name" rowspan="<?=$count?>">
						<!-- <a href="/<?=UPLOAD_IMAGES_REL_DIR?><?=$product->photo?>" class="highslide" onclick="return hs.expand(this)" style="vertical-align: middle; "><img src="<?=$product->photo ? Media::img($product->photo) : Funx::noPhotoSrc()?>&width=50" alt="<?=$product->name?>" /></a> -->
						
						<!-- <a href="<?=$product->url()?>" target="_blank" ><?=$product->name?></a> -->
						<?=$product->name?>
					</td>
				<?php 
				}?>
				
				<td class="doze"><a href="<?=$product->url()?>" target="_blank" ><?=OptPrice::shortenDozeStr($product->inPackage)?></a></td>
				
				<td class="price"><?=Currency::drawAllCurrenciesPrice($product->price)?></td>
				
				<?php 
				foreach(ProductSimple::$optPrices as $sum=>$isShown)
				{
					if(!$isShown)continue;?>
				<td class="price">
					<?=$product->optPricesArr[$sum] ? Currency::drawAllCurrenciesPrice($product->optPricesArr[$sum]) : ' -нет- '?>
				</td>
				<?php 	
				}?>
				
				
								
			</tr>
			<?php 
				$i++;
			}?>
			
		<?php 	
		}?>
	<?php 
	}?>
	</table>
	
	<?=$MODEL['textAfter']->attrs['descr']?>
	
</div>
</div>

