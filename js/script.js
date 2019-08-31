var Currency = {
	currentCode: null,


	set: function(code){
		this.current = Currency.items[code]
		alert(vd(this.current, true))
	},
}




function switchMenu(id)
{
	$('.left-primary-menu ul').slideUp();
	
	if($('#left-sub-'+id).css('display') == 'none')
		$('#left-sub-'+id).slideToggle('fast');
	else
		$('#left-sub-'+id).slideUp('fast');
	
	
	
	$('.top-primary-menu ul').slideUp();
	
	if($('#top-sub-'+id).css('display') == 'none')
		$('#top-sub-'+id).slideToggle('fast');
	else
		$('#top-sub-'+id).slideUp('fast');	
}




function switchCurrency(code)
{
	$.ajax({
		url: '/cart/switchCurrency',
		data: 'code='+encodeURIComponent(code)+'&globalCurrency='+code,
		dataType: 'json',
		beforeSend: function(){
			$('.currencies').removeClass('KZT').removeClass('USD').removeClass('RUR').addClass(code);
			//$.fancybox.showLoading(); 
			//$('.centered-wrapper').css('opacity', '.6');
			
			$('.currency.active').slideUp();
			$('.currency.currency-'+code+'').slideDown()
			
			$('.cur-btn').removeClass('active')
			$('.cur-btn-'+code).addClass('active')


			Currency.set(code)
		},
		success: function(data){
			if(!data.errors)
			{
				//alert(data.currency.code)
				/*$('.currency.active').slideUp();
				$('.currency.currency-'+data.currency.code+'').slideDown()
				
				$('.cur-btn').removeClass('active')
				$('.cur-btn-'+data.currency.code).addClass('active')*/
				
				
				//$('.currencies').removeClass('KZT').removeClass('USD').removeClass('RUR').addClass(code)
				
				$('#top-menu').slideUp('fast'); 
				
				refreshCartInfo()
				//if(document.location.pathname  == '/cart/')
				if (typeof drawCart !== "undefined")
					drawCart()
					
				$('#bonus-ajax').html(data.cartBonusHTML)
			}
			else
				alert(data.errors[0].error)
		},
		error: function(e){alert(e)},
		complete: function(){$.fancybox.hideLoading(); $('.centered-wrapper').css('opacity', '1'); }
	});
}



function addProductToCart(id, quan)
{
	$.ajax({
		url: '/cart/addProduct',
		data: 'id='+encodeURIComponent(id)+'&quan='+quan,
		dataType: 'json',
		beforeSend: function(){$('#product-loading-'+id).slideDown('fast')},
		success: function(data){
			if(!data.errors)
			{
				$('#myModal .modal-body').html(data.html)
				$('#myModal').modal('show');
				refreshCartInfo()
			}
			else
				showErrors(data.errors)
		},
		error: function(e){alert(e)},
		complete: function(){$('#product-loading-'+id).slideUp('fast')}
	});
}



function addCourseProductsToCart(id)
{
	$.ajax({
		url: '/cart/addCourseProducts',
		data: 'id='+encodeURIComponent(id),
		dataType: 'json',
		beforeSend: function(){$('#course-loading-'+id).slideDown('fast')},
		success: function(data){
			if(!data.errors)
			{
				$('#myModal .modal-body').html(data.html)
				$('#myModal').modal('show');
				refreshCartInfo()
			}
			else
				showErrors(data.errors)
		},
		error: function(e){alert(e)},
		complete: function(){$('#course-loading-'+id).slideUp('fast')}
	});
}








function addPresentProductsToCart(id)
{
	$.ajax({
		url: '/cart/addPresentProducts',
		data: 'id='+encodeURIComponent(id),
		dataType: 'json',
		beforeSend: function(){$('#present-loading-'+id).slideDown('fast')},
		success: function(data){
			if(!data.errors)
			{
				$('#myModal .modal-body').html(data.html)
				$('#myModal').modal('show');
				refreshCartInfo()
			}
			else
				showErrors(data.errors)
		},
		error: function(e){alert(e)},
		complete: function(){$('#present-loading-'+id).slideUp('fast')}
	});
}





function refreshCartInfo()
{
	$.ajax({
		url: '/cart/refreshCartInfo',
		data: '',
		dataType: 'json',
		beforeSend: function(){$('.top-cart-wrapper').css('opacity', 1)},
		success: function(data){
			if(!data.errors)
			{
				$('.top-cart-wrapper').html(data.htmlDesktop)
				$('.top-cart-mobile').html(data.htmlMobile)
				$('#cart-items-quan').html(data.totalQuan)
			}
			else
				alert(data.errors[0].error)
		},
		error: function(e){},
		complete: function(){$('.top-cart-wrapper').css('opacity', 1)}
	});
}