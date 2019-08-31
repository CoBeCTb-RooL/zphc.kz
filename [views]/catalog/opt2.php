<?php
$arr = array();
$productsDict = [];
foreach($MODEL['catalog'] as $cat)
{
	$products = array();
	foreach($cat->products as $product)
	{
	    if(!$product->optPricesArr['250'])
	        continue;
		$products[$product->name][] = $product;

		#   формируем словарь товаров (для js)
        $productsDict[$product->id] = $product->json('micro');
	}
	$cat->products = $products;
	$arr[] = $cat;
}

$productsDictJson = json_encode($productsDict);
//vd($productsDict);
//vd($productsDictJson);


//vd($arr);
?>
<!--крамбсы-->
<? Core::renderPartial(SHARED_VIEWS_DIR.'/crumbs.php', $MODEL['crumbs']);?>
<!--//крамбсы-->



<!--<script type="text/javascript" src="/js/optCart.js"></script>-->
<!--<script type="text/javascript" src="/js/optCartNotification.js"></script>-->
<!--<script>-->
<!--    $(document).ready(function(){-->
<!--        OptCartNotification.showBookmark()-->
<!--        OptCartNotification.updateInfo(true)-->
<!--    })-->
<!--</script>-->

<?//vd ($productsDictJson)?>

<?//vd($CONTENT)?>
<?ob_start()?>
<script>
    // $(document).ready(function(){

    OptCart.ProductsDict = <?=$productsDictJson?>;
    OptCart.State.load();
    OptCart.Notificator.update()

        // OptCart.ids[9999] = 88
        // alert(vd(OptCart.ProductsDict, true))
    // })
</script>
<?$CONTENT->section('documentReadyJs', ob_get_clean())?>

<?//vd($CONTENT)?>


<script>
    function highlightPriceStepCol(sum, off){
        $('.price').removeClass('highlighted')
        if(!off)
            $('.price-step-'+sum).addClass('highlighted')
    }
</script>



<div class="opt" style="text-align: center; ">
    <div  class="limiter" style="margin: 0 auto; ">

        <div class=" ">

            <div class="row limiter">
                <?=$MODEL['textBefore']->attrs['descr']?>
            </div>

            <div class="row top " >
                <div class="col-md-6 logo-container" ><img src="/img/logo.png" /></div>
                <div class="col-md-6 info-container"><?=$MODEL['textTableTop']->attrs['descr']?></div>
            </div>


            <div class="row  ">
                <table class="price-tbl" style="width: 100%; ">
                    <tr class="header head">
                        <th>Наименование</th>
                        <th></th>
                        <th>Розница</th>
                        <?foreach(ProductSimple::$optPrices as $sum=>$isShown):?>
                            <?if(!$isShown)continue;?>
                            <th>
<!--                                --><?//=Funx::numberFormat($sum)?><!-- $-->
                                &ge;<?=Currency::drawAllCurrenciesPrice($sum)?>
                            </th>
                        <?endforeach?>
                    </tr>
                    <?foreach($MODEL['catalog'] as $cat):?>
                        <tr class="header">
                            <td class="cat-name" colspan="<?=count(ProductSimple::$optPrices)+2?>"><?=$cat->name?></td>
                        </tr>
                        <?foreach($cat->products as $name=>$products):?>
                            <?foreach($products as $product):?>
                        <tr class="product-row product-row-<?=$product->id?>">
                            <td class="product" >
                                <a href="<?=$product->url()?>" target="_blank" >
                                <div class="name"><?=$product->name?></div>
                                <div class="doze">
                                    <?=OptPrice::shortenDozeStr($product->inPackage)?>
                                </div>
                                </a>
                            </td>

                            <td>
                                <div class="to-cart-btn-wrapper">
                                    <input type="button" class="btn-small" value="В корзину" onclick="OptCart.Product.add(<?=$product->id?>)">
                                </div>
                                <div class="quans-wrapper" style="display: none; ">
                                    <div class="inner">
                                        <a href="#" class="btn btn-minus" onclick="OptCart.Product.add(<?=$product->id?>, -1); return false; ">-</a>
                                        <div class="quan">
                                            <select name="" onchange="OptCart.Product.setQuan(<?=$product->id?>, $(this).val())">
                                                <?for($i=1; $i<=200; $i++):?>
                                                <option value="<?=$i?>"><?=$i?></option>
                                                <?endfor;?>
                                            </select>
                                        </div>
                                        <a href="#" class="btn btn-plus" onclick="OptCart.Product.add(<?=$product->id?>); return false; ">+</a>
                                    </div>
                                </div>
                            </td>

                            <td class="price price-step-0 base-price" onmouseover="highlightPriceStepCol(0); $(this).addClass('current')" onmouseout="highlightPriceStepCol(0, true); $(this).removeClass('current')"> <?=Currency::drawAllCurrenciesPrice($product->price)?></td>

                                <?foreach(ProductSimple::$optPrices as $sum=>$isShown):?>
                                    <?if(!$isShown)continue;?>
                            <td class="price price-step-<?=$sum?>" title="при заказе от <?=$sum?> $" onmouseover="highlightPriceStepCol(<?=$sum?>); $(this).addClass('current')" onmouseout="highlightPriceStepCol(<?=$sum?>, true); $(this).removeClass('current')">
                                <?=$product->optPricesArr[$sum] ? Currency::drawAllCurrenciesPrice($product->optPricesArr[$sum]) : ' -нет- '?>
                            </td>
                                <?endforeach;?>
                        </tr>
                            <?endforeach;?>
                        <?endforeach;?>
                    <?endforeach;?>
                </table>
            </div>





            <!--mobile-->
            <script>
                function productInfo(id)
                {
                    // alert(id)
                    $('.product-row-'+id+' .prices').slideToggle()
                }
            </script>

            <div class="row ">
                <div class="price-tbl-mobile">
                    <?foreach($MODEL['catalog'] as $cat):?>
                        <h1 class="cat-name" ><?=$cat->name?></h1>
                        <?foreach($cat->products as $name=>$products):?>
                            <?foreach($products as $product):?>
                                <?if(!$product->optPricesArr) continue;?>
                            <div class="product-row product-row-<?=$product->id?>" onclick="">
                                <div class=" product-info">
                                    <a href="<?=$product->url()?>" target="_blank" onclick="productInfo(<?=$product->id?>); return false; " >
                                        <div class="col img"><img src="<?=$product->photo ? Media::img($product->photo) : Funx::noPhotoSrc()?>&width=50" alt="" /></div>
                                        <div class="col info" style="text-align: left; ">

                                                <div class="name"><?=$product->name?></div>
                                                <div class="doze"><?=OptPrice::shortenDozeStr($product->inPackage)?></div>

                                        </div>
                                    </a>

                                <div class="clear"></div>
                                </div>
                                <div class=" prices">
                                <?foreach(ProductSimple::$optPrices as $sum=>$isShown):?>
                                    <?if(!$isShown)continue;?>
                                    <div class="price">
                                        <?=$product->optPricesArr[$sum] ? Currency::drawAllCurrenciesPrice($product->optPricesArr[$sum]) : ' -нет- '?>
                                    </div>
                                <?endforeach;?>
                                </div>

                            </div>
                            <?endforeach;?>


                        <?endforeach;?>

                    <?endforeach;?>
                </div>
            </div>


        </div>

    </div>
</div>



<!--notifications-->
<div class="notification" id="notification" style="display: ; ">
    <div class="inner">
        <div class="bookmark" style="display: none; ">
            <a class="expand-arrow" href="#" onclick="OptCartNotification.expand('open'); return false; ">
                <span class="not-loading">
                    <span class="arr open" style="display: none; ">&lt;</span>
                    <span class="arr close1" style="display: none; ">&gt;</span>
                    <span class="inf">
                        <i class="fa fa-shopping-cart" aria-hidden="true" style="font-size: 1.2em; "></i>: <span class="val cart-quan">5</span>
                    </span>
                </span>
                <span class="loading" style="display: none; "><img src="/img/preloader-white.gif" alt="" height="23"></span>
            </a>
        </div>

        <div class="main-block" style="display: none; ">
            <div class="inner">
                <div class="r cart-row">
                    <nobr>Товаров в корзине: <b class="cart-quan">??</b></nobr>
                    <br><nobr>на сумму: <b class="cart-sum">??</b></nobr>
                    <div style="margin: 4px 0 0 0; "><a href="{{\App\Models\Cart::isJs() ? route('cart2') : route('cart')}}" class="btn orange ">Перейти в корзину</a></div>
                </div>
            </div>
        </div>

        <div class="btns" style="display: none; ">
            <button onclick="OptCartNotification.quake()">quake</button><br>
            <button onclick="OptCartNotification.loading()">loa 1</button><br>
            <button onclick="OptCartNotification.loading(false)">loa 0</button><br>
            <button onclick="OptCartNotification.showBookmark()">showBookmark()</button><br>
            <button onclick="OptCartNotification.showBookmark(false)">showBookmark(false)</button><br>
            <button onclick="OptCartNotification.updateInfo()">updateInfo()</button><br>
        </div>

    </div>
</div>
<!--//notifications-->