<?php
$arr = array();
foreach($MODEL['catalog'] as $cat)
{
	$products = array();
	foreach($cat->products as $product)
	{
	    if(!$product->optPricesArr['250'])
	        continue;
		$products[$product->name][] = $product;
	}
	$cat->products = $products;
	$arr[] = $cat;
}


//vd($arr);
?>
<!--крамбсы-->
<? Core::renderPartial(SHARED_VIEWS_DIR.'/crumbs.php', $MODEL['crumbs']);?>
<!--//крамбсы-->



<div style="text-align: center; ">
    <div style="margin: 0 auto; width: 1000px; ">

        <div class=" container1 opt">

            <div class="row limiter">
                <?=$MODEL['textBefore']->attrs['descr']?>
            </div>

            <div class="row top limiter" >
                <div class="col-md-6 logo-container" ><img src="/img/logo.png" /></div>
                <div class="col-md-6 info-container"><?=$MODEL['textTableTop']->attrs['descr']?></div>
            </div>


            <div class="row limiter tbl">
                <table class="price-tbl" style="width: 100%; ">
                    <tr class="header">
                        <th>Наименование</th>
                        <th>Розница</th>
                        <?foreach(ProductSimple::$optPrices as $sum=>$isShown):?>
                            <?if(!$isShown)continue;?>
                            <th><?=Funx::numberFormat($sum)?> $</th>
                        <?endforeach?>
                    </tr>
                    <?foreach($MODEL['catalog'] as $cat):?>
                        <tr class="header">
                            <td class="cat-name" colspan="<?=count(ProductSimple::$optPrices)+1?>"><?=$cat->name?></td>
                        </tr>
                        <?foreach($cat->products as $name=>$products):?>
                            <?foreach($products as $product):?>
                                <?if(!$product->optPricesArr) continue;?>
                                <tr>
                                        <td class="product" >
                                            <div class="name"><?=$product->name?></div>
                                            <div class="doze">
                                                <a href="<?=$product->url()?>" target="_blank" ><?=OptPrice::shortenDozeStr($product->inPackage)?></a>
                                            </div>
                                        </td>


                                    <td class="price"><?=Currency::drawAllCurrenciesPrice($product->price)?></td>

                                    <?foreach(ProductSimple::$optPrices as $sum=>$isShown):?>
                                        <?if(!$isShown)continue;?>
                                        <td class="price">
                                            <?=$product->optPricesArr[$sum] ? Currency::drawAllCurrenciesPrice($product->optPricesArr[$sum]) : ' -нет- '?>
                                        </td>
                                    <?endforeach;?>

                                </tr>
                                <?php
                                $i++;
                                ?>
                            <?endforeach;?>
                        <?endforeach;?>
                    <?endforeach;?>
                </table>
            </div>


        </div>

    </div>
</div>