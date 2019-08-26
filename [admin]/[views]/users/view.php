<?php
$item = $MODEL['user'];
//vd($item);
?>


<?php if($MODEL['error']){ echo ''.$MODEL['error'].''; return; }?>


<style>
.row1{margin: 0 0 6px 0; }
.row1 .label{width: 160px; text-align: right; display: inline-block; padding: 0 10px 0 0 ; }
.row1 b{}
.status{font-size: 17px; }
.user-status-<?=Status::ACTIVE?>{color: green;}
.user-status-<?=Status::INACTIVE?>{opacity: .4; }

.status{display: none; }
.current-status-<?=Status::ACTIVE?> .user-status-<?=Status::ACTIVE?>{display: inline-block; }
.current-status-<?=Status::INACTIVE?> .user-status-<?=Status::INACTIVE?>{display: inline-block; }



.transactions{margin: 20px 0 0 20px; } 
.transactions .item{margin: 0 0 20px 0 ; } 
.transactions .date{ font-size: 10px; }
.transactions .val{display: inline-block; font-size: 1.5em; padding: 0 20px 0 0; line-height: 100%; }
.transactions .type{display: inline-block; vertical-align: top; }
.transactions .plus{}
.transactions .minus{}
.transactions .small{font-size: 13px; color: #888; }


</style>



<script>
function setStatus(id, status)
{
	/*if(!confirm('Сменить ? '))
		return;*/
//alert(status)
	$.ajax({
		url: '/<?=ADMIN_URL_SIGN?>/user/setStatus',
		data: 'userId='+id+'&status='+status,
		dataType: 'json',
		beforeSend: function(){},
		success: function(data){
			if(data.errors==null)
			{
				$('.user').removeClass('current-status-<?=Status::ACTIVE?>').removeClass('current-status-<?=Status::INACTIVE?>').addClass('current-status-'+data.status)

				// 	для списка тоже нужно изменить
				$('#row-'+id).removeClass('status-<?=Status::ACTIVE?>').removeClass('status-<?=Status::INACTIVE?>').addClass('status-'+data.status)
			}
			else
				alert(data.errors[0].error)
		},
		error: function(e){alert('Возникла ошибка на сервере... Попробуйте позже.')},
		complete: function(){}
	});
}
</script>


<div class="user current-status-<?=$item->status->code?>">
	<h1>
	<a style="opacity: .4; font-size: .8em; text-decoration: none; " onclick="wList.slideDown('fast'); wView.slideUp('fast'); if($('#users-list').html()==''){usersList()}; return false; " href="?"><span  ><i class="fa fa-backward"></i> список</span></a> | 
		<?php 
		if($item)
		{?>
			<span style="font-size: 15px; font-weight: bold; opacity: .5; ">id : <?=$item->id?> &nbsp; </span><br><?=$item->name?>
		<?php 	
		}
		else
		{?>
			<span style="font-size: 13px; text-shadow: none;">Пользователь не найден.</span>
		<?php 	
		}?>
	</h1>
	
	
	
	
	
	
	<?php 
	if($item)
	{?>
	<div class="info">
		<div class="row1">
			<span class="label">Дата регистрации:</span> <b><?=Funx::mkDate($item->registrationDate, 'with_time')?></b>
		</div>
		<div class="row1">
			<span class="label">Статус:</span> 
			<b class="status user-status-<?=Status::ACTIVE?>"><a href="#" onclick="setStatus('<?=$item->id?>', '<?=Status::INACTIVE?>'); return false;"><?=Status::code(Status::ACTIVE)->icon?></a> <?=Status::code(Status::ACTIVE)->name?></b>
			<b class="status user-status-<?=Status::INACTIVE?>"><a href="#" onclick="setStatus('<?=$item->id?>', '<?=Status::ACTIVE?>'); return false;"><?=Status::code(Status::INACTIVE)->icon?></a> <?=Status::code(Status::INACTIVE)->name?></b>
		</div>
		<div class="row1">
			<span class="label">E-mail:</span> <b><a href="mailto:<?=$item->email?>"><?=$item->email?></a></b>
		</div>
		<div class="row1">
			<span class="label">Заказы:</span> <b><a href="/<?=ADMIN_URL_SIGN?>/orders/?chosenUserId=<?=$item->id?>" target="_blank">смотреть</a></b>
		</div>
		
		
		<div class="bonus">
			<h3>Бонусы</h3>
			<div style="font-size: 1.3em; ">
				<?=Currency::formatPrice($item->bonus)?> $ - <span style="color: #777; "><?=Currency::formatPrice($item->bonusBlocked)?> $ (блок.)</span> = <b><?=Currency::formatPrice($item->bonusAvailable)?> $</b>
			</div>
			
			
			
			
			<h3>Транзакции</h3>
			<div class="transactions">
				
			<?php 
			foreach($item->bonusTransactions as $tr)
			{?>
				<div class="item">
					<div class="date"><?=Funx::mkDate($tr->dateCreated, 'numeric_with_time')?></div>
					<div class="val <?=$tr->value > 0 ? 'plus' : 'minus'?>">
						<?=Currency::formatPrice($tr->value)?> $
						<?php 
						if($tr->currency->code != Currency::USD)
						{?>
						<br /><span class="small"><?=Currency::formatPrice($tr->valueInCurrency, $tr->currency)?></span>
						<?php 	
						}?>
					</div>
					<div class="type">
						<?=$tr->transactionType->name?>
					<?php 
					if($tr->transactionType->code == BonusTransactionModel::ADD_FOR_ORDER || $tr->transactionType->code == BonusTransactionModel::PAY_FOR_ORDER)
					{?>
						<a href="/<?=ADMIN_URL_SIGN?>/orders/item/<?=$tr->param1?>" target="_blank">#<?=$tr->param1?></a>
					<?php 	
					}?>
					</div>
				</div>
			<?php 	
			}?>
			</div>
			<?php 
			//vd($item->bonusTransactions)?>
			
		</div>
		
	</div>
	<?php 	
	}?>
</div>