<?php
//vd($MODEL);
$item = $MODEL['item'];
?>


<div class="modal-response">
	<?php 
	if($MODEL['error'])
	{?>
		<div class="error"><?=$MODEL['error']?></div>
	<?php 	
	}
	else 
	{?>
		<?php ?>
		<div class="pic">
			<img src="<?=$item->photo ? Media::img($item->photo).'&width=200' : '/img/present2.png'?>" alt="" />
		</div>
		<div class="info">
			<div class="title"><?=$MODEL['title']?></div>
			<?php 
			//if(!$MODEL['problem'])
			
			//else
			{?>
			<div class="problem"><?=$MODEL['problem']?></div>
			<?php 	
			}?>
			<?php {?>
			<div class="msg"><?=$MODEL['msg']?></div>
			<?php 
			}?>
		</div>
	<?php 
	}?>
	<div class="clear"></div>
	
	<div class="btns">
		<a href="/cart" class="btn btn-to-cart">Оформить заказ</a>
		<a href="" class="btn-continue" data-dismiss="modal">Продолжить покупки</a>
	</div>
</div>