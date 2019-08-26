var CatalogSimple = {
		ITEMS_OPTS: {},
		
		
		itemsList: function()
		{
			//alert('itemsList!! '+ITEMS_OPTS.catId)
			$.ajax({
				url: '/admin/catalogSimple/productsListAjax',
				data: this.ITEMS_OPTS,
				beforeSend: function(){$.fancybox.showLoading()},
				success: function(data){
					$('#items').html(data)
				},
				error: function(e){},
				complete: function(){
					$("#items").css("opacity", "1");
					
					$('#cats').slideUp('fast'); 
					$('#items').slideDown('fast')
					$.fancybox.hideLoading()
				}
			});
		},
		
		
		
		productSwitchStatus: function(id)
		{
			$.ajax({
				url: '/admin/catalogSimple/productSwitchStatus',
				data: 'id='+id,
				dataType: 'json',
				beforeSend: function(){$.fancybox.showLoading()},
				success: function(data){
					//alert(data.errors)
					if(!data.errors)
					{
						$('#product-status-switcher-'+id).html(data.status.icon)
						$('#product-'+id).removeAttr('class').addClass('product-status-'+data.status.code)
						notice('Сохранено')
					}
					else
						showErrors(data.errors)
				},
				error: function(e){alert('Возникла ошибка на сервере... Попробуйте позже.')},
				complete: function(){$.fancybox.hideLoading()}
			});
		}, 
		
		
		
		
		productEdit: function(id, currentCat)
		{
			$.fancybox.showLoading()
			
			$.ajax({
				url: '/admin/catalogSimple/productEdit',
				data: 'id='+id+'&currentCat='+currentCat,
				beforeSend: function(){$.fancybox.showLoading()},
				success: function(data){
					$('#float').html(data)
					$.fancybox('#float');
				},
				error: function(e){alert('Возникла ошибка на сервере... Попробуйте позже.')},
				complete: function(){$.fancybox.hideLoading()}
			});
		},
		
		
		
		
		productEditSubmitStart: function()
		{ 
			//alert(123)
			$.fancybox.showLoading()
		}, 
		productEditSubmitComplete: function(data)
		{
			$.fancybox.hideLoading()
			if(!data.errors)
			{
				this.itemsList()
				$.fancybox.close()
				notice('Сохранено')
			}
			else
			{
				showErrors(data.errors)
				//$().scrollTo(0);
				//alert('#'+data.errors[0].field)
				document.getElementById(data.errors[0].field).scrollIntoView()
				 /*$('html, body').animate({
				        scrollTop: $("#"+data.errors[0].field).offset().top
				    }, 2000);*/
			}
		},
		
		
		
		productDelete: function(id)
		{
			$.ajax({
				url: '/admin/catalogSimple/productDelete',
				data: 'id='+id,
				beforeSend: function(){$.fancybox.showLoading()},
				success: function(data){
					if(!data.errors)
					{
						$('#product-'+id).fadeOut()
						notice('Товар удалён.')
					}
					else
						showErrors(data.errors)
				},
				error: function(e){alert('Возникла ошибка на сервере... Попробуйте позже.')},
				complete: function(){$.fancybox.hideLoading()}
			});
		},
		
		
		productsListSubmitComplete: function(data)
		{
			if(!data.errors)
			{
				CatalogSimple.itemsList();
				notice('Сохранено')
			}
			else
				showErrors(data.errors)
		}
		
		
}

