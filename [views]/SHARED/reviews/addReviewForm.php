<?php 
$objectTypeCode = $MODEL['objectTypeCode'];
$objectId = $MODEL['objectId'];
?>


<script>
//review
$(document).ready(function(){
	$(".review-form .stars").rateYo({
		/*normalFill: "#ccc",
		ratedFill: "#FFAF18",*/
	    starWidth: "25px",
	    fullStar: true,
	    multiColor: {
	        "startColor": "#eeeeee", //RED
	        "endColor"  : "#FFAF18"  //GREEN
		},
		/*onChange: function (rating, rateYoInstance) {
			$(this).next().text(rating);
		},*/
		onSet: function (rating, rateYoInstance) {
	      $('.review-form input[name=rate]').val(rating)
	    }
	});
})

function reviewSubmit()
{
	$('.review-error').css('display', 'none')
	
	var f = $('.review-form')
	f.find('*').removeClass('field-error')
	
	var rate = f.find('input[name=rate]').val()
	var text = f.find('textarea[name=text]').val()
	var name = f.find('input[name=name]').val()
	var email = f.find('input[name=email]').val()
	
	var objectType = f.find('input[name=objectType]').val()
	var objectId = f.find('input[name=objectId]').val()

	var errors = []
	//alert(123)
	
	if(text=='')
		errors.push({'field': 'text', 'error': 'Введите отзыв'})
	if(name=='')
		errors.push({'field': 'name', 'error': 'Введите Ваше имя'})
		
	if(errors.length>0)
	{
		showReviewErrors(errors)
	}	
	else
	{
		$.ajax({
			url: '/review/add',
			data: 'objectType='+encodeURIComponent(objectType) + '&objectId='+encodeURIComponent(objectId)+ '&rate='+encodeURIComponent(rate)+ '&text='+encodeURIComponent(text)+ '&name='+encodeURIComponent(name)+ '&email='+encodeURIComponent(email) ,
			dataType: 'json',
			beforeSend: function(){$('.review-loading').slideDown('fast')},
			success: function(data){
				if(!data.errors)
				{
					$('.review-form + .review-success').slideDown()
					f.slideUp()
				}
				else
					showReviewErrors(data.errors)
			},
			error: function(e){alert(e)},
			complete: function(){$('.review-loading').slideUp('fast')}
		});
	}

	return false; 
}



function showReviewErrors(errors)
{
	$('.review-error').html(getErrorsString(errors)).slideDown('fast')
}

</script>


<form class="review-form" onsubmit="reviewSubmit(); return false; ">
	<input type="hidden" name="objectType" value="<?=$objectTypeCode?>" />
	<input type="hidden" name="objectId" value="<?=$objectId?>" />
	<input type="hidden" name="rate" value="0" />
	<div class="rating">
		<span class="lbl">Оцените товар: </span>
		<span class="stars"></span>
		<span class="stars-val"></span>
		<div class="clear"></div>
	</div>
	<textarea name="text" placeholder="Оставить комментарий..."></textarea>
	<div class="col"><input type="text" name="name" placeholder="Ваше имя" /></div>
	<div class="col"><input type="text" name="email" placeholder="Ваш e-mail" /></div>
	<div class="col"><input type="submit" value="Отправить" ></div>
	<div class="clear"></div>
	<div class="review-loading">Секунду...</div>
	<div class="review-error error"></div>
	
</form>
<div class="review-success">
	<h1>Спасибо! Ваш отзыв успешно отправлен!</h1>
	<h2 style="padding: 0 0 0 20px; ">После модерации, он появится на сайте.</h2>
</div>







