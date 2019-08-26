
<script src="/<?=ADMIN_DIR?>/js/catalogSimple.js" type="text/javascript"></script>

<script>
	var CHOSEN_CAT = 0

		
	function catsList(id)
	{
		if(typeof id == 'undefined')
			id=0
			
		CHOSEN_CAT = id
		
		$.ajax({
			url: '/<?=ADMIN_URL_SIGN?>/catalogSimple/catsList',
			data: 'catId='+id,
			beforeSend: function(){$.fancybox.showLoading()},
			success: function(data){
				$('#cats').html(data)
			},
			error: function(e){},
			complete: function(){
				$("#cats").css("opacity", "1");
				
				$('#cats').slideDown('fast'); 
				$('#items').slideUp('fast')
				$.fancybox.hideLoading()
			}
		});
	} 



	function catEdit(id, currentCat)
	{
		$.fancybox.showLoading()
		
		$.ajax({
			url: '/<?=ADMIN_URL_SIGN?>/catalogSimple/catEdit',
			data: 'catId='+id+'&currentCat='+currentCat,
			beforeSend: function(){$.fancybox.showLoading()},
			success: function(data){
				$('#float').html(data)
				$.fancybox('#float');
			},
			error: function(e){alert('Возникла ошибка на сервере... Попробуйте позже.')},
			complete: function(){$.fancybox.hideLoading()}
		});
	}


	function catEditSubmitStart()
	{ 
		$.fancybox.showLoading()
	}
	function catEditSubmitComplete(data)
	{
		$.fancybox.hideLoading()
		if(!data.errors)
		{
			catsList(CHOSEN_CAT)
			$.fancybox.close()
			notice('Сохранено')
		}
		else
			showErrors(data.errors)
	}


	function catsListSubmitStart()
	{ 
		$.fancybox.showLoading()
		$('#cats').css('opacity', .4)
	}
	function catsListSubmitComplete(data)
	{
		//alert(CHOSEN_CAT)
		$.fancybox.hideLoading()
		$('#cats').css('opacity', 1)
		if(!data.errors)
		{
			catsList(CHOSEN_CAT)
			$.fancybox.close()
			notice('Сохранено')
		}
		else
			showErrors(data.errors)
	}

	

	function catSwitchStatus(id)
	{
		$.ajax({
			url: '/<?=ADMIN_URL_SIGN?>/catalogSimple/catSwitchStatus',
			data: 'catId='+id,
			dataType: 'json',
			beforeSend: function(){$.fancybox.showLoading()},
			success: function(data){
				//alert(data.errors)
				if(!data.errors)
				{
					$('#cat-status-switcher-'+id).html(data.status.icon)
					$('#cat-'+id).removeAttr('class').addClass('cat-status-'+data.status.code)
					notice('Сохранено')
				}
				else
					showErrors(data.errors)
			},
			error: function(e){alert('Возникла ошибка на сервере... Попробуйте позже.')},
			complete: function(){$.fancybox.hideLoading()}
		});
	}



	function catDelete(id)
	{
		$.ajax({
			url: '/admin/catalogSimple/catDelete',
			data: 'id='+id,
			beforeSend: function(){$.fancybox.showLoading()},
			success: function(data){
				if(!data.errors)
				{
					$('#cat-'+id).fadeOut()
					notice('Категория удалена.')
				}
				else
					showErrors(data.errors)
			},
			error: function(e){alert('Возникла ошибка на сервере... Попробуйте позже.')},
			complete: function(){$.fancybox.hideLoading()}
		});
	}
	 

	




	$(document).ready(function(){
		catsList(<?=$_REQUEST['catId']?>)
	});
</script>






<div id="cats">Загрузка....</div>
<div id="items" style="display: none; "> Загрузка....</div>




<!--форма редактирования-->
<div id="float" class="view " style="min-width: 1000px; max-width: 1200px;" >!!</div>
<div id="float2" class="view " style="" ></div>
	
	