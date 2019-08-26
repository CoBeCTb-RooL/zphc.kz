<script>
var	OPTS = {}
		
		
function itemsList()
{
	
	//alert('itemsList!! '+OPTS.catId)
	$.ajax({
		url: '/admin/course/itemsListAjax',
		data: OPTS,
		beforeSend: function(){$.fancybox.showLoading()},
		success: function(data){
			$('#items').html(data)
		},
		error: function(e){},
		complete: function(){$("#items").css("opacity", "1"); $.fancybox.hideLoading()}
	});
}
		
		
		
function itemSwitchStatus(id)
{
	$.ajax({
		url: '/admin/course/itemSwitchStatus',
		data: 'id='+id,
		dataType: 'json',
		beforeSend: function(){$.fancybox.showLoading()},
		success: function(data){
			//alert(data.errors)
			if(!data.errors)
			{
				$('#item-status-switcher-'+id).html(data.status.icon)
				$('#item-'+id).removeAttr('class').addClass('item-status-'+data.status.code)
				notice('Сохранено')
			}
			else
				showErrors(data.errors)
		},
		error: function(e){alert('Возникла ошибка на сервере... Попробуйте позже.')},
		complete: function(){$.fancybox.hideLoading()}
	});
}




function itemEdit(id)
{
	$.fancybox.showLoading()
	
	$.ajax({
		url: '/admin/course/itemEdit',
		data: 'id='+id,
		beforeSend: function(){$.fancybox.showLoading()},
		success: function(data){
			$('#float').html(data)
			$.fancybox('#float');
		},
		error: function(e){alert('Возникла ошибка на сервере... Попробуйте позже.')},
		complete: function(){$.fancybox.hideLoading()}
	});
}




function itemEditSubmitStart()
{ 
	//alert(123)
	$.fancybox.showLoading()
}
function itemEditSubmitComplete(data)
{
	$.fancybox.hideLoading()
	if(!data.errors)
	{
		itemsList()
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
}



function itemDelete(id)
{
	$.ajax({
		url: '/admin/course/itemDelete',
		data: 'id='+id,
		beforeSend: function(){$.fancybox.showLoading()},
		success: function(data){
			if(!data.errors)
			{
				$('#item-'+id).fadeOut()
				notice('Товар удалён.')
			}
			else
				showErrors(data.errors)
		},
		error: function(e){alert('Возникла ошибка на сервере... Попробуйте позже.')},
		complete: function(){$.fancybox.hideLoading()}
	});
}




function itemsListSubmitComplete(data)
{
	if(!data.errors)
	{
		itemsList();
		notice('Сохранено')
	}
	else
		showErrors(data.errors)
}




	




	$(document).ready(function(){
		itemsList()

		<?php 
		if($_REQUEST['itemId'])
		{?>
		itemEdit(<?=$_REQUEST['itemId']?>);
		<?php 	
		}?>
	});


	
</script>



<h1>КУРСЫ</h1>

<div id="items" style="display: ; "> Загрузка....</div>




<!--форма редактирования-->
<div id="float" class="view " style="min-width: 1000px; max-width: 1200px;" >!!</div>
<div id="float2" class="view " style="" ></div>
	
	