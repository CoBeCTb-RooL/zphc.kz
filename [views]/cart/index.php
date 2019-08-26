<?php

//vd($CART);

?>




<script>
function drawCart()
{
	$.ajax({
		url: '/cart/drawCartAjax',
		data: '',
		dataType: 'json',
		beforeSend: function(){$.fancybox.showLoading(); $('.cart').css('opacity', '.6'); },
		success: function(data){
			$('#products').html(data.cartHTML)
			$('.order-info .inner').html(data.orderInfoHTML)
			refreshCartInfo()
			
			if(data.isCartEmpty)
			{
				$('.cart-inner').slideUp()
				$('.cart-empty').slideDown()
			}
			else
			{
				$('.cart-inner').slideDown()
				$('.cart-empty').slideUp()
			}
		},
		error: function(e){},
		complete: function(){$.fancybox.hideLoading(); $('.cart').css('opacity', '1');}
	});
} 





function addQuan(id, quan)
{
	$.ajax({
		url: '/cart/addQuan',
		data: 'id='+id+'&quan='+quan+'',
		dataType: 'json',
		beforeSend: function(){$.fancybox.showLoading(); $('.cart').css('opacity', '.6');},
		success: function(data){
			if(!data.errors)
				drawCart()
			else
				alert(data.errors[0].error)
		},
		error: function(e){},
		complete: function(){/*$.fancybox.hideLoading(); $('.cart').css('opacity', '1');*/}
	});
}



function addCourse(id, quan)
{
	$.ajax({
		url: '/cart/addCourseProducts',
		data: 'id='+id+'&quan='+quan+'',
		dataType: 'json',
		beforeSend: function(){$.fancybox.showLoading(); $('.cart').css('opacity', '.6');},
		success: function(data){
			if(!data.errors)
				drawCart()
			else
				alert(data.errors[0].error)
		},
		error: function(e){},
		complete: function(){$.fancybox.hideLoading(); $('.cart').css('opacity', '1');}
	});
}





function switchPaymentType(type)
{
	$.ajax({
		url: '/cart/switchPaymentType',
		data: 'type='+type,
		dataType: 'json',
		beforeSend: function(){$.fancybox.showLoading(); $('.cart').css('opacity', '.6');},
		success: function(data){
			if(!data.errors)
			{
				drawCart()
				$('.requisites .info b').html(data.paymentType.name)
				showRequisites(type)
			}
			else
				alert(data.errors[0].error)
		},
		error: function(e){},
		complete: function(){/*$.fancybox.hideLoading(); $('.cart').css('opacity', '1');*/}
	});
}



function showRequisites(type)
{
	//alert(type)
	$('.requisites .item').css('display', 'none')
	$('#'+type).css('display', 'block')
	
	if(type == '<?=PaymentType::CASH?>')
		$('.requisites .info').css('display', 'none')
	else
		$('.requisites .info').css('display', 'block')
	//alert('#'+type)
}



function switchDeliveryType(type)
{
	$.ajax({
		url: '/cart/switchDeliveryType',
		data: 'type='+type,
		dataType: 'json',
		beforeSend: function(){$.fancybox.showLoading(); $('.cart').css('opacity', '.6');},
		success: function(data){
			if(!data.errors)
			{
				drawCart()
			}
			else
				alert(data.errors[0].error)
		},
		error: function(e){},
		complete: function(){/*$.fancybox.hideLoading(); $('.cart').css('opacity', '1');*/}
	});
}





function showSuccess()
{
	//alert(123)
	$('.cart-inner').slideUp()
	$(' .success1').slideDown()
}





function sendOrder()
{
	$('.order-info .error').css('display', 'none')
	$('.customer-info *.field-error').removeClass('field-error')
	
	var name = $(".customer-info input[name=name]").val()
	var email = $(".customer-info input[name=email]").val()
	var phone = $(".customer-info input[name=phone]").val()
	var address = $(".customer-info input[name=address]").val()
	var index = $(".customer-info input[name=index]").val()
	var comment = $(".customer-info [name=comment]").val()
	var refererPhone = $(".customer-info [name=refererPhone]").val()
	
	
	var errors = []


	if(name == '')
		errors.push({'field': 'name', 'error':'Введите <b>ФИО</b>'})
	if(email == '')
		errors.push({'field': 'email', 'error':'Введите <b>e-mail</b>'})
	if(phone == '')
		errors.push({'field': 'phone', 'error':'Введите <b>телефон</b>'})
	if(address == '')
		errors.push({'field': 'address', 'error':'Введите <b>адрес доставки</b>'})
	if(index == '')
		errors.push({'field': 'index', 'error':'Введите <b>почтовый индекс</b>'})
	
		
	
	if(errors.length > 0 )
		showCartErrors(errors)
	else
	{
		//showSuccess()
		$('#send-order-btn').attr('disabled', 'disabled')
		$.ajax({
			url: '/cart/sendOrder',
			data:   'name='+encodeURIComponent(name)+'&'
					+'email='+encodeURIComponent(email)+'&'
					+'phone='+encodeURIComponent(phone)+'&'
					+'address='+encodeURIComponent(address)+'&'
					+'index='+encodeURIComponent(index)+'&'
					+'comment='+encodeURIComponent(comment)+'&'
					+'refererPhone='+encodeURIComponent(refererPhone)+'&'
					,
			dataType: 'json',
			beforeSend: function(){$.fancybox.showLoading(); $('.cart').css('opacity', '.6');},
			success: function(data){
				if(!data.errors)
				{
					showSuccess()
					refreshCartInfo()

					$('html, body').animate({scrollTop: $('body').offset().top}, 600);
				}
				else
				{
					showCartErrors(data.errors)
					//alert(data.errors[0].error)
				}
				$('#send-order-btn').removeAttr('disabled')
			},
			error: function(e){},
			complete: function(){$.fancybox.hideLoading(); $('.cart').css('opacity', '1');}
		});
	}
	
	
}



function showCartErrors(errors)
{
	$('.order-info .error').html(getErrorsString(errors)).slideDown('fast')
}


function payByBonus()
{
	$('#payByBonus').removeClass('field-error')
	$('#payByBonusInfo').css('display', 'none')
	
	var sum=$('#payByBonus').val()
	$.ajax({
		url: '/cart/payByBonus',
		data: 'sum='+sum,
		dataType: 'json',
		beforeSend: function(){$.fancybox.showLoading(); $('.cart').css('opacity', '.6');},
		success: function(data){
			if(data.errors)
			{
				$('#payByBonusInfo').html(getErrorsString(data.errors)).slideDown('fast')
				$.fancybox.hideLoading(); $('.cart').css('opacity', '1');
			}
			else
			{
				//alert(data.sum)
				$('#payByBonusInfo').html(data.msg).slideDown('fast')
				
			}	
			
			$('#payByBonus').val(data.sum)
			$('#bonusSum').html(data.sumStr)
			drawCart()
		},
		error: function(e){alert('Возникла ошибка на сервере...Пожалуйста, попробуйте позднее.')},
		complete: function(){/*$.fancybox.hideLoading(); $('.cart').css('opacity', '1');*/}
	});
}





$(document).ready(function(){
	drawCart()
	//showSuccess()

	showRequisites('<?=$CART->paymentType->code?>')

	$(".customer-info input[name=phone]").mask("+7 (999) 999-99-99");
	$("#refererPhone").mask("+7 (999) 999-99-99");
})



</script>






<!--крамбсы-->
<? Core::renderPartial(SHARED_VIEWS_DIR.'/crumbs.php', $MODEL['crumbs']);?>
<!--//крамбсы-->



<div class="cart">

	<h1>Корзина</h1>
	
	<!-- <button onclick="drawCart()">qwe</button> -->
	
	
	<div class="cart-inner" style="display: none; ">
		
		<div id="products"></div>
		
		
		<!-- способы оплаты -->
		<div class="section payment-types">
			<h2>Способы оплаты</h2>
			<div class="inner">
			<?php
			foreach(PaymentType::$items as $type)
			{?>
				<label class="item <?=$type->code?>" ><input type="radio" name="paymentType" onclick="switchPaymentType('<?=$type->code?>')" <?=$type->code==$CART->paymentType->code ? ' checked="checked" ' : ''?> /><img src="<?=$type->icon?>" alt="" /><?=$type->name?></label>
			<?php 	
			}?>
			</div>
		</div>
		<!-- /способы оплаты -->
		
		
		
		
		<!-- способы доставки -->
		<div class="section even payment-types">
			<h2>Выберите вариант доставки</h2>
		<?php
		foreach(DeliveryType::$items as $type)
		{?>
			<label class="item <?=$type->code?>">
				<input type="radio" name="deliveryType"  onclick="switchDeliveryType('<?=$type->code?>')" <?=$type->code==$CART->deliveryType->code ? ' checked="checked" ' : ''?>  />
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
			
			<hr />
			
			<div class="r">
				<div class="lbl">Телефон друга:</div>
				<div class="input">
					<input type="text" name="refererPhone" id="refererPhone" />
				</div>
			</div>
			
		</div>
		<!-- /инфа о покупателе -->
		
		
		
		
		
		<!-- инфа о заказе -->
		<div class="section even order-info">
			<h2>Информация о заказе</h2>
			<div class="inner"></div>
			
			<?
			if($USER)
			{?>
			<div class="bonus-pay">
				<div class="r">
					<div class="lbl">Ваши бонусы: </div>
					<div class="val"><!--<?=Currency::getPriceStr($USER->bonusAvailable)?>--><?=Currency::drawAllCurrenciesPrice($USER->bonusAvailable)?></div>
				</div>
				<div id="bonus-ajax">
				<? Core::renderPartial('cart/bonusInfoPartial.php')?>
					
				</div>
			</div>
			<?php 
			}?>
			
			<button onclick="sendOrder()" id="send-order-btn">Отправить заказ</button>
			<span class="loading" style="display: none; ">Секунду...</span>
			<div class="error"></div>
		</div>
		<!-- /инфа о заказе -->
		
		
		
		
		<div class="clear"></div>
		
		
		
	</div>


	<div class="cart-empty" style="display: none; ">
		&nbsp;&nbsp;&nbsp;&nbsp;Ваша корзина пуста.
	</div>



	
	
	<!-- УСПЕХ -->
	<div class="success1" style="display: none; ">
		<h1>Ваш заказ успешно отправлен!</h1>
		<h2>На указанный e-mail отправлено письмо с подробностями заказа.</h2>
		
		<div class="requisites">
		<?php //vd($_CONFIG['SETTINGS'])?>
			
			<div class="info">
				Вы выбрали способ оплаты 
				<b><?=$CART->paymentType->name?></b>. 
				<br>Пожалуйста, используйте следующие реквизиты для оплаты Вашего заказа:
			</div>
			
			<? $type = PaymentType::code(PaymentType::YANDEX_MONEY)?>
			<div class="item" id="<?=$type->code?>">
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