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


//vd($arr);
?>
<!--крамбсы-->
<? Core::renderPartial(SHARED_VIEWS_DIR.'/crumbs.php', $MODEL['crumbs']);?>


<?ob_start()?>
<script type="text/javascript" src="/js/optCart.js?<?=Slonne::random()?>"></script>
<script type="text/javascript" src="/js/OptCartNotification.js?<?=Slonne::random()?>"></script>
<script>
    OptCart.Dict.products = <?=$productsDictJson?>;
    <?
            $optSteps = [];
            foreach (ProductSimple::$optPrices as $step=>$val)
                if($val)
                    $optSteps[] = $step;
    ?>
    OptCart.OptStep.steps = <?=json_encode($optSteps)?>;
    OptCart.State.load();

    OptCart.settings.orderSumForFreeDelivery = <?=$_CONFIG['SETTINGS']['order_sum_for_free_delivery'] ?>;
    OptCart.settings.deliveryCost = <?=$_CONFIG['SETTINGS']['delivery_cost'] ?>;

    OptCart.Dict.deliveryTypes = <?=json_encode(DeliveryType::$items)?>;
    OptCart.Dict.paymentTypes = <?=json_encode(PaymentType::$items)?>;


    $(".customer-info input[name=phone]").mask("+7 (999) 999-99-99");

    //  чиним долбаные селекты в долбаном айос
    // jQuery(function ($) {
        if (navigator.userAgent.match('iPad|iPhone|iPod') != -1  ) {
            $('.quans-wrapper select').addClass('iOSselect'); // provide a class for iOS select box
        }
    // });

</script>

<script>
    // OptCart.Modal.show()
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
                        <th style="width: 100px; ">Наименование</th>
                        <th style="min-width: 104px;   "></th>
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
                            <td class="cat-name" colspan="<?=count(ProductSimple::$optPrices)+/*2*/5?>"><?=$cat->name?></td>
                        </tr>
                        <?foreach($cat->products as $name=>$products):?>
                            <?foreach($products as $product):?>
                        <tr class="product-row product-row-<?=$product->id?>">
                            <td class="product" >
                                <a href="<?=$product->url()?>" target="_blank" >
                                <div class="name"><?=$product->name?></div>
                                <div class="doze">
                                    <?=/*OptPrice::shortenDozeStr($product->inPackage)*/$product->inPackage?>
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
                                        <a href="#" class="btn btn-plus" onclick="OptCart.Product.add(<?=$product->id?>); return false; ">+</a>
                                    </div>

                                    <a href="#" style="margin-top: -5px;  display: block; " onclick="if(confirm('Убрать товар?')){OptCart.Product.setQuan(<?=$product->id?>, 0); } return false; ">&times; убрать</a>
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
            function productInfo(id){
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
                                        <div class="col img"><img src="<?=$product->photo ? Media::img($product->photo) : Funx::noPhotoSrc()?>&width=150" alt="" /></div>
                                        <div class="col info" style="text-align: left; ">
                                            <div class="name"><?=$product->name?></div>
                                            <div class="doze"><?=/*OptPrice::shortenDozeStr($product->inPackage)*/$product->inPackage?></div>
                                            <div class="btn" onclick="/*productInfo(<?=$product->id?>); return false;*/ ">Смотреть цены</div>
                                        </div>
                                    </a>
                                <div class="clear"></div>
                            </div>
                            <div class=" prices">

                                <table border="0">
                                    <tr class="price-row">
                                        <td class="product-price-wrapper">
                                            <div class="product-price price-step-0"><?=Currency::drawAllCurrenciesPrice($product->price)?></div>
                                        </td>
                                        <td class="hint">Розница</td>
                                    </tr>
                                    <?foreach(ProductSimple::$optPrices as $sum=>$isShown):?>
                                        <?if(!$isShown)continue;?>
                                    <tr class="price-row">
                                        <td class="product-price-wrapper">
                                            <div class="product-price price-step-<?=$sum?>"><?=$product->optPricesArr[$sum] ? Currency::drawAllCurrenciesPrice($product->optPricesArr[$sum]) : ' -нет- '?></div>
                                        </td>
                                        <?if($product->optPricesArr[$sum]):?>
                                        <td class="hint">
                                            Опт, при заказе &ge;<?=Currency::drawAllCurrenciesPrice($sum)?>
                                        </td>
                                        <?endif;?>
                                    </tr>
                                    <?endforeach;?>
                                </table>

                                <div style="display: inline-block;  float: left; ">
                                    <div class="inner" style="margin: 10px 0 10px 20px; text-align: center; display: inline-block; height: 47px; width: 102px;  ">
                                        <div class="to-cart-btn-wrapper" >
                                            <input type="button" class="btn-small" style="width: 95px; " value="В корзину" onclick="OptCart.Product.add(<?=$product->id?>)">
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
                                                <a href="#" class="btn btn-plus" onclick="OptCart.Product.add(<?=$product->id?>); return false; ">+</a>
                                            </div>

                                            <a href="#" style="font-size: 12px; display: block; text-align: center;  margin-top: -6px;  " onclick="if(confirm('Убрать товар?')){OptCart.Product.setQuan(<?=$product->id?>, 0); } return false; " >&times; убрать</a>
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





<!-- optCart Modal -->
<div id="optCartModal" class="modal fade" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header" style="text-align: center; ">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <img src="/img/logo.png" height="50" alt="" />
            </div>
            <div class="modal-body">
                <div class="cart">
                    <form action="" onsubmit="OptCart.sendOrder(); return false; ">
                        <div id="products">
                            <div class="products-wrapper">
                                <div class="block1 cart-individuals1" >
                                    <div class="items"></div>
                                </div>
                                <hr>
                                <div class="overall-wrapper" ></div>
                            </div>
                            <div class="form-wrapper">
                                <!-- способы оплаты -->
                                <div class="section payment-types">
                                    <h2>Способы оплаты</h2>
                                    <div class="inner">
                                        <?foreach(PaymentType::$items as $type):?>
                                            <label class="item <?=$type->code?>" ><input type="radio" name="paymentType" value="<?=$type->code?>" onclick="OptCart.switchPaymentType('<?=$type->code?>')"  /><img src="<?=$type->icon?>" alt="" /><?=$type->name?></label>
                                        <?endforeach;?>
                                    </div>
                                </div>
                                <!-- /способы оплаты -->

                                <!-- способы доставки -->
                                <div class="section even delivery-types">
                                    <h2>Выберите вариант доставки</h2>
                                    <?php
                                    foreach(DeliveryType::$items as $type)
                                    {?>
                                        <label class="item <?=$type->code?>">
                                            <input type="radio" name="deliveryType"  value="<?=$type->code?>" onclick="OptCart.switchDeliveryType('<?=$type->code?>')"  />
                                            <!-- <img src="<?=$type->icon?>" alt="" /> -->
                                            <span class="name">
                        <?=$type->name?><br/>
                    </span>
                                            <span class="info">(<?=$type->info?>)</span>
                                        </label>
                                        <?php
                                    }?>
                                </div>
                                <!-- /способы доставки -->

                                <div class="clear"></div>

                                <!-- инфа о покупателе -->
                                <div class="section customer-info">
                                    <h2>Информация о покупателе</h2>

                                    <div class="r">
                                        <div class="lbl">ФИО<span class="req">*</span>:</div>
                                        <div class="input"><input type="text" name="name" value="<?=$USER->name?>" /></div>
                                    </div>

                                    <div class="r">
                                        <div class="lbl">E-mail<span class="req">*</span>:</div>
                                        <div class="input"><input type="text" name="email" value="<?=$USER->email?>" /></div>
                                    </div>

                                    <div class="r">
                                        <div class="lbl">Телефон<span class="req">*</span>:</div>
                                        <div class="input"><input type="text" name="phone" value="<?=$USER->phone?>" /></div>
                                    </div>

                                    <div class="r">
                                        <div class="lbl">Адрес<span class="req">*</span>:</div>
                                        <div class="input"><input type="text" name="address" value="<?=$USER->address?>"/></div>
                                    </div>
                                    <div class="r">
                                        <div class="lbl">Почтовый индекс<span class="req">*</span>:</div>
                                        <div class="input"><input type="text" name="index" value="<?=$USER->index?>" /></div>
                                    </div>
                                    <div class="r">
                                        <div class="lbl">Примечание к заказу:</div>
                                        <div class="input"><textarea name="comment" ></textarea></div>
                                    </div>


                                </div>
                                <!-- /инфа о покупателе -->

                                <!-- инфа о заказе -->
                                <div class="section even order-info">
                                    <h2>Информация о заказе</h2>
                                    <div class="inner">

                                        <div class="order-summary">
                                            <div class="r">
                                                <div class="lbl">Товаров на сумму: </div>
                                                <div class="val price price-primal-for-all-products"></div>
                                            </div>

                                            <div class="r deliveryType" style="display: none; white-space: nowrap; vertical-align: top;    ">
                                                <div class="lbl" style="vertical-align: top; ">Доставка: </div>
                                                <div class="val" style="vertical-align: top; ">
                                                    <?=$CART->deliveryType->name?>
                                                    <div class="delivery-price">Стоимость: <b><?=$CART->deliveryCostInCurrency ? Currency::formatPrice($CART->deliveryCostInCurrency) : 'БЕСПЛАТНО'?></b></div>
                                                </div>
                                            </div>
                                            <div class="r paymentType" style="display: none; ">
                                                <div class="lbl">Оплата: </div>
                                                <div class="val"><img  src="<?=$CART->paymentType->icon?>" alt=""  width="58" /><?=$CART->paymentType->name?></div>
                                            </div>

                                            <div class="economy-info" ></div>

                                            <div class="r overall opt">
                                                <div class="lbl">Итого к оплате: </div>
                                                <div class="val cart-price-final"></div>

                                            </div>

                                        </div>


                                    </div>
                                    <button type="submit" id="send-order-btn">Отправить заказ</button>
                                    <span class="loading" style="display: none; ">Секунду...</span>
                                    <div class="error"></div>
                                </div>
                                <!-- /инфа о заказе -->

                                <div class="clear"></div>
                            </div>
                        </div>
                    </form>
                    <!-- УСПЕХ -->
                    <div class="success1" style="display: none;text-align: center;  ">
                        <h1>Ваш заказ успешно отправлен!</h1>
                        <h2>На указанный e-mail отправлено письмо с подробностями заказа.</h2>

                        <div >
                            Сумма заказа:  <b class="price-for-all-products"></b>
                        </div>
<!--                        <button onclick="OptCart.showSuccess(false)"><-</button>-->
                        <div class="requisites">

                            <div class="info">
                                Вы выбрали способ оплаты
                                <b class="payment-type-lbl"></b>.
                                <br>Пожалуйста, используйте следующие реквизиты для оплаты Вашего заказа:
                            </div>

                            <? $type = PaymentType::code(PaymentType::YANDEX_MONEY)?>
                            <div class="item " id="<?=$type->code?>">
                                <div class="pic"><img src="<?=$type->icon?>" alt="" /></div>
                                <div class="title"><?=$type->name?>: </div>
                                <div class="val"><?=$_CONFIG['SETTINGS'][$type->code]?></div>
                            </div>

                            <? $type = PaymentType::code(PaymentType::QIWI)?>
                            <div class="item" id="<?=$type->code?>">
                                <div class="pic"><img src="<?=$type->icon?>" alt="" /></div>
                                <div class="title"><?=$type->name?>: </div>
                                <div class="val"><?=$_CONFIG['SETTINGS'][$type->code]?></div>
                            </div>

                            <? $type = PaymentType::code(PaymentType::VISA)?>
                            <div class="item" id="<?=$type->code?>">
                                <div class="pic"><img src="<?=$type->icon?>" alt="" /></div>
                                <div class="title"><?=$type->name?>: </div>
                                <div class="val"><?=$_CONFIG['SETTINGS'][$type->code]?></div>
                            </div>

                            <? $type = PaymentType::code(PaymentType::WEB_MONEY)?>
                            <div class="item" id="<?=$type->code?>">
                                <div class="pic"><img src="<?=$type->icon?>" alt="" /></div>
                                <div class="title"><?=$type->name?>: </div>
                                <div class="val"><?=$_CONFIG['SETTINGS'][$type->code]?></div>
                            </div>

                        </div>
                    </div>
                    <!-- /УСПЕХ -->
                </div>
            </div>

        </div>

    </div>
</div>





<!--cart TMPLs-->

<div id="optCartProductRowTmpl" style="display: none; ">
    <div class="item " id="cart-item-_ID_">
        <div class="kol title">
		<span class=" pic">
			<a href="/catalog/item/_URL_PIECE_"  title="_NAME_" onclick="return false; " style="cursor: default; "><img src="/include/resize.slonne.php?img=../upload/images/_PHOTO_&amp;width=150" alt="_NAME_" ></a>
		</span>
            <a href="/catalog/item/_URL_PIECE_" onclick="return false; " style="cursor: default; ">_NAME_</a>
        </div>
        <div class="kol price" style="line-height: 100%; ">
            <span class="old-price pricePrimeSingle">_PRICE_PRIME_SINGLE_</span>
            <b class="priceOptSingle">_PRICE_OPT_SINGLE_</b>
        </div>

        <div class="kol quan" >
            <div style="display: inline-block; ">
                <div class="quans-wrapper" >
                    <div class="inner">
                        <a href="#" class="btn btn-minus" onclick="OptCart.Modal.ProductsTable.add(_ID_, -1);  return false; ">-</a>
                        <div class="quan11" style="font-size: .9em; ">
                            <select name="" onchange="OptCart.Modal.ProductsTable.setQuan(_ID_, $(this).val()); " class="quan-_ID_" >
                                <?for($i=1; $i<=200; $i++):?>
                                    <option value="<?=$i?>"><?=$i?></option>
                                <?endfor;?>
                            </select>
                        </div>
                        <a href="#" class="btn btn-plus" onclick="OptCart.Modal.ProductsTable.add(_ID_); return false; ">+</a>
                    </div>
                </div>
            </div>
        </div>


        <div class="kol final-price" style="line-height: 100%; ">
            <span class="old-price pricePrimeTotal">_PRICE_PRIME_TOTAL_</span>
            <b class="priceOptTotal">_PRICE_OPT_TOTAL_</b>
        </div>
        <div class="kol delete">
            <a href="#delete" title="Убрать товар" onclick="if(confirm('Убрать товар из корзины?')){OptCart.Product.setQuan(_ID_, 0); OptCart.Modal.drawCart();} return false; ">
                <span class="word-delete"> <span style="font-size: 20px; font-weight: bold; line-height:50%; vertical-align: middle;  display: inline-block;   border: 0px solid red;   font-size: 30px;  ">×</span> <span style="display: inline-block; vertical-align: middle;">убрать из корзины</span></span>
                <span class="x">×</span>
            </a>
        </div>
    </div>
</div>



<div id="overallTmpl" style="display: none; ">
    <div class="overall">
        <div class="lbl">Итого товаров на сумму: </div>
        <b>_SUM_</b>
    </div>
</div>



