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



<?ob_start()?>

<!-- development version, includes helpful console warnings -->
<!--<script src="https://cdn.jsdelivr.net/npm/vue/dist/vue.js"></script>-->
<script>

    OptCart.ProductsDict = <?=$productsDictJson?>;
    OptCart.OptStep.steps = <?=json_encode(array_keys(ProductSimple::$optPrices))?>;
    OptCart.State.load();


</script>
<script>
    var app = new Vue({
        el: '#cartModalTmpl',
        data: {
            message: 'Hello Vue!',
            cart: OptCart
        }
    })
</script>
<?$CONTENT->section('documentReadyJs', ob_get_clean())?>



<script>
    function highlightPriceStepCol(sum, off){
        $('.product-price').removeClass('highlighted')
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
                        <th style="width: 99px; "></th>
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

                            <td >
                                <div class="to-cart-btn-wrapper" >
                                    <input type="button" class="btn-small" value="В корзину" onclick="OptCart.Product.add(<?=$product->id?>)">
                                </div>
                                <div class="quans-wrapper" style="display: none; ">
                                    <div class="inner">
                                        <a href="#" class="btn btn-minus" onclick="OptCart.Product.add(<?=$product->id?>, -1); return false; ">-</a>
                                        <div class="quan">
                                            <select name="" onchange="OptCart.Product.setQuan(<?=$product->id?>, $(this).val())" class="quan-<?=$product->id?>">
                                                <?for($i=1; $i<=200; $i++):?>
                                                <option value="<?=$i?>"><?=$i?></option>
                                                <?endfor;?>
                                            </select>
                                        </div>
                                        <a href="#" class="btn " onclick="OptCart.Product.add(<?=$product->id?>); return false; ">+</a>
                                    </div>

                                    <a href="#" onclick="if(confirm('Убрать товар?')){OptCart.Product.setQuan(<?=$product->id?>, 0); } return false; ">&times; убрать</a>
                                </div>
                            </td>

                            <td class="product-price price-step-0 " onmouseover="highlightPriceStepCol(0); $(this).addClass('current')" onmouseout="highlightPriceStepCol(0, true); $(this).removeClass('current')"> <?=Currency::drawAllCurrenciesPrice($product->price)?></td>

                                <?foreach(ProductSimple::$optPrices as $sum=>$isShown):?>
                                    <?if(!$isShown)continue;?>
                            <td class="product-price price-step-<?=$sum?>" title="при заказе от <?=$sum?> $" onmouseover="highlightPriceStepCol(<?=$sum?>); $(this).addClass('current')" onmouseout="highlightPriceStepCol(<?=$sum?>, true); $(this).removeClass('current')">
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
                                            <div class="btn" onclick="/*productInfo(<?=$product->id?>); return false;*/ ">Смотреть цены</div>
                                        </div>

                                    </a>

                                <div class="clear"></div>

                            </div>





                            <div class=" prices">



                                <div class="price-row">
                                    <div class="product-price price-step-0"><?=Currency::drawAllCurrenciesPrice($product->price)?></div>
                                </div>
                                <?foreach(ProductSimple::$optPrices as $sum=>$isShown):?>
                                    <?if(!$isShown)continue;?>
                                    <div class="price-row">
                                        <div class=" product-price price-step-<?=$sum?>">
                                        <?=$product->optPricesArr[$sum] ? Currency::drawAllCurrenciesPrice($product->optPricesArr[$sum]) : ' -нет- '?>
                                        </div>
                                    <?if($product->optPricesArr[$sum]):?>
                                        <div class="hint">(при заказе &ge;<?=Currency::drawAllCurrenciesPrice($sum)?>)</div>
                                    <?endif;?>
                                    </div>
                                <?endforeach;?>


                                <div style="display: inline-block; text-align: center; border: 0px solid green; float: left; ">
                                    <div class="inner" style="margin: 10px 0 10px 20px;    border: 0px solid red; display: inline-block; ">
                                        <div class="to-cart-btn-wrapper" >
                                            <input type="button" class="btn-small" value="В корзину" onclick="OptCart.Product.add(<?=$product->id?>)">
                                        </div>
                                        <div class="quans-wrapper" style="display: none; ">
                                            <div class="inner">
                                                <a href="#" class="btn btn-minus" onclick="OptCart.Product.add(<?=$product->id?>, -1); return false; ">-</a>
                                                <div class="quan">
                                                    <select name="" onchange="OptCart.Product.setQuan(<?=$product->id?>, $(this).val())" class="quan-<?=$product->id?>">
                                                        <?for($i=1; $i<=200; $i++):?>
                                                            <option value="<?=$i?>"><?=$i?></option>
                                                        <?endfor;?>
                                                    </select>
                                                </div>
                                                <a href="#" class="btn " onclick="OptCart.Product.add(<?=$product->id?>); return false; ">+</a>
                                            </div>

                                            <a href="#" style="font-size: 12px; " onclick="if(confirm('Убрать товар?')){OptCart.Product.setQuan(<?=$product->id?>, 0); } return false; ">&times; убрать</a>
                                        </div>
                                    </div>
                                </div>
                                <div class="clear"></div>


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
                        <i class="fa fa-shopping-cart" aria-hidden="true" style="font-size: 1.2em; "></i>: <span class="val cart-quan">??</span>
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
                    <div style="margin: 4px 0 0 0; "><a href="#" onclick="OptCart.Modal.show(); " class="btn orange ">Перейти в корзину</a></div>
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

            <button onclick="OptCart.OptStep.setStep(0)">0$</button>
            <?foreach ($steps=OptPrice::steps() as $step):?>
            <button onclick="OptCart.OptStep.setStep(<?=$step?>)"><?=$step?>$</button>
            <?endforeach;?>
        </div>

    </div>
</div>
<!--//notifications-->





<!--cart TMPLs-->
<div id="cartTmpl" style="display: none; ">
    <div class="cart">
        <div id="products">
            <div class="products-wrapper">
                <div class="block1 cart-individuals1" >
                    <div class="items"></div>
                </div>
            </div>
        </div>
    </div>
</div>


<div id="optCartProductRowTmpl" style="display: none; ">
    <div class="item " id="cart-item-_ID_">
        <div class="kol title">
		<span class=" pic">
			<a href="/catalog/item/_URL_PIECE_" target="_blank" title="_NAME_"><img src="/include/resize.slonne.php?img=../upload/images/_PHOTO_&amp;width=500" alt="_NAME_"></a>
		</span>
            <a href="/catalog/item/_URL_PIECE_" target="_blank">_NAME_</a>
        </div>
        <div class="kol price" style="line-height: 100%; ">
            <span class="old-price">_PRICE_PRIME_SINGLE_</span>_PRICE_BR_IF_NECESSARY_
            <b>_PRICE_OPT_SINGLE_</b>
        </div>
        <div class="kol quan">
            <div style="display: inline-block;">_QUAN_SECTION_</div>

        </div>
        <div class="kol final-price" style="line-height: 100%; ">
            <span class="old-price">_PRICE_PRIME_TOTAL_</span>_PRICE_BR_IF_NECESSARY_
            <b>_PRICE_OPT_TOTAL_</b>
        </div>
        <div class="kol delete">
            <a href="#delete" title="Убрать товар" onclick="if(confirm('Убрать товар из корзины?')){alert('AA (это тоже вопрос)'); return false; ">
                <span class="word-delete"> <span style="font-size: 20px; font-weight: bold; line-height:50%; vertical-align: middle;  display: inline-block;   border: 0px solid red;   font-size: 30px;  ">×</span> <span style="display: inline-block; vertical-align: middle;">убрать из корзины</span></span>
                <span class="x">×</span>
            </a>
        </div>
    </div>
</div>


<div id="quansWrapperTmpl" style="display: none; ">
    <div class="quans-wrapper" >
        <div class="inner">
            <a href="#" class="btn btn-minus" onclick="OptCart.Product.add(_ID_, -1); OptCart.Modal.drawCart(); return false; ">-</a>
            <div class="quan11">
                <select name="" onchange="OptCart.Product.setQuan(_ID_, $(this).val()); OptCart.Modal.drawCart();" class="quan-_ID_" style="background: #fff; ">
                    <?for($i=1; $i<=200; $i++):?>
                        <option value="<?=$i?>"><?=$i?></option>
                    <?endfor;?>
                </select>
            </div>
            <a href="#" class="btn btn-plus" onclick="OptCart.Product.add(_ID_); OptCart.Modal.drawCart(); return false; ">+</a>
        </div>
<!--        <a href="#" style="font-size: 12px; " onclick="if(confirm('Убрать товар?')){OptCart.Product.setQuan(_ID_, 0); } return false; ">&times; убрать</a>-->
    </div>
</div>

<!--/cart TMPLs-->


