<?php
$list = $MODEL['list'];
$isAdmin = Admin::isAdmin() && $ADMIN->hasRole(Role::COMMENT_MODERATOR);;

?>





<script>
function reviewApprove(id)
{ 
	$.ajax({
		url: '/<?=ADMIN_URL_SIGN?>/review/approve',
		data: 'id='+encodeURIComponent(id),
		dataType: 'json',
		beforeSend: function(){$.fancybox.showLoading();},
		success: function(data){
			if(!data.errors)
			{
				//alert('ok')
				$('#review-'+id)
					.removeClass('review-status-<?=Status::ACTIVE?>')
					.removeClass('review-status-<?=Status::MODERATION?>')
					.addClass('review-status-<?=Status::ACTIVE?>')
			}
			else
				alert(data.errors[0].error)
		},
		error: function(e){alert(e)},
		complete: function(){$.fancybox.hideLoading();}
	});
}


function reviewDelete(id)
{ 
	$.ajax({
		url: '/<?=ADMIN_URL_SIGN?>/review/delete',
		data: 'id='+encodeURIComponent(id),
		dataType: 'json',
		beforeSend: function(){$.fancybox.showLoading();},
		success: function(data){
			if(!data.errors)
			{
				//alert('ok')
				$('#review-'+id).fadeOut()
			}
			else
				alert(data.errors[0].error)
		},
		error: function(e){alert(e)},
		complete: function(){$.fancybox.hideLoading();}
	});
}
</script>




<style>
	.review-status-<?=Status::ACTIVE?>{}
	.review-status-<?=Status::MODERATION?>{background: #CEEBF5; }
	
	
	.review-status-label{display: none; margin: 8px 0 0 10px; }
	.review-status-<?=Status::MODERATION?> .review-status-label-<?=Status::MODERATION?>{display: block; font-weight: bold; color: #289FC7; }
	
	.review-status-<?=Status::ACTIVE?> .btn-approve{display: none;}
	
	
	.admin-panel{margin: 10px 0 0 0; padding: 2px 0 0 10px; border-top: 1px solid #ececec; text-align: right; color: #777; font-style: italic; }
	.admin-panel a{display: inline-block; margin: 0 0 0 10px; }
</style>



<div class="reviews-list">
	<h3>Отзывы</h3>
<?php 
if($list)
{?>

	<?php 
	foreach($list as $item)
	{?>
		<div class="item review-status-<?=$item->status->code?>" id="review-<?=$item->id?>">
			<div class="name"><?=$item->name?></div>
			<div class="date"><?=$item->dateCreated?></div>
			<div class="stars-wrapper"><div class="stars"></div></div> 
			<?php 
			if($item->email)
			{?>
			<div class="email">(<a href="mailto:<?=$item->email?>"><?=$item->email?></a>)</div>
			<?php 
			}?>
			<div class="clear"></div>
			<div class="text"><?=$item->text?></div>
			
			<div class="review-status-label review-status-label-<?=Status::MODERATION?>">МОДЕРАЦИЯ</div>
			
			<?php 
			if($isAdmin)
			{?>
			<div class="admin-panel">
				<b>Админ:</b> 
				<?php 
				if($item->status->code == Status::MODERATION)
				{?>
				<a href="#" class="btn-approve" onclick="if(confirm('Одобрить?')){reviewApprove(<?=$item->id?>);} return false; ">одобрить</a>
				<?php 	
				}?>
				<a href="#" onclick="if(confirm('Удалить отзыв?')){reviewDelete(<?=$item->id?>);} return false; " style="color: red; ">&times; удалить</a>
			</div>
			<?php 
			}?>
			
		</div>
		
		<script>
		$(document).ready(function(){
			$("#review-<?=$item->id?> .stars").rateYo({
			    rating: <?=$item->rate?>,
			    readOnly: true,
			    /*multiColor: {
			        "startColor": "#eeeeee", //RED
			        "endColor"  : "#FFAF18"  //GREEN
				},*/
			    starWidth: "15px"
			  });
		})
		</script>
	<?php 	
	}?>

<?php 
}
else
{?>
	<div style="padding: 0 30px; ">Отзывов пока нет.</div>
<?php 	
}?>
</div>