<?php
$list = $MODEL['list']; 
$presentsList = $MODEL['presentsList'];
?>







<!--крамбсы-->
<? Core::renderPartial(SHARED_VIEWS_DIR.'/crumbs.php', $MODEL['crumbs']);?>
<!--//крамбсы-->



<div class="actions">

	<h1>Текущие акции</h1>
	
<?php
foreach($list as $key=>$item)
{

	$a = new DateTime();
	$b = new DateTime($item->dateTill);
	$diffInSeconds = $b->getTimestamp() - $a->getTimestamp();
	
	?>
	<div class="item" id="item-<?=$item->id?>">
		<a href="<?=$item->url()?>" class="img" title="<?=$item->title?>"><img  src="<?=Media::img($item->photo)?>&width=690&height=480" alt="<?=$item->name?>" /></a>
		<div class="info">
			<a href="<?=$item->url()?>" class="title"><?=$item->name?></a>
			<br><div class="rate">
				<span class="stars"></span>
				<span class="val"><?=$item->rate?> &nbsp;&nbsp;<i>(<?=$item->votesCount ? $item->votesCount.' '.Funx::okon($item->votesCount, array('голосов', 'голос', 'голоса')) : 'отзывов пока нет'?>)</i></span>
				<div class="clear"></div>
			</div>
			<div class="block"><?=$item->anons?></div>
			
			
			<!-- <div class="countdown-wrapper">
				<div class="countdown">
					<div id="clock-<?=$item->id?>" ></div>
					<div class="time-lbl days">Дней</div>
					<div class="time-lbl hours">Часов</div>
					<div class="time-lbl minutes">Минут</div>
					<div class="time-lbl secs">Секунд</div>
				</div>
				<div class="clear"></div>
			</div>-->
			<div class="countdown-wrapper" >
					<div class="lbl">До конца акции осталось: </div>
					<br />
					<div class="countdown">
						<!-- <div id="clock" ></div>-->
						
						<div class="countdown-wrapper-relative">
							<div class="countdown-wrapper-inner">
								<div id="flipclock-<?=$item->id?>" class="scale-4" style=" "></div>
							</div>
						</div>
			
						<div class="time-lbl days1">Дней</div>
						<div class="time-lbl hours1">Часов</div>
						<div class="time-lbl minutes1">Минут</div>
						<div class="time-lbl secs1">Секунд</div>
					</div>
					<div class="clear"></div>
					<?php 
					if($isActionOver)
					{?>
						<div style="color: red; font-weight: bold; ">Акция завершена.</div>
					<?php 	
					}?>
					
					<script>
					var clock;

					$(document).ready(function() {
						var clock;

						//FlipClock.Lang.Custom = { days:'11Days', hours:'11Hours', minutes:'Minutes', seconds:'Seconds' };
						
						clock = $('#flipclock-<?=$item->id?>').FlipClock({
					        clockFace: 'DailyCounter',
					        autoStart: false,
					        defaultLanguage : 'russian',
					        callbacks: {
					        	stop: function() {
					        		$('.message').html('The clock has stopped!')
					        	}
					        }
					    });
							    
					    clock.setTime(<?=$diffInSeconds?>);
					    clock.setCountdown(true);
					    clock.start();

					});
					</script>
				</div>
			

		</div>
		
		<div class="clear"></div>
	</div>
	
	<hr />
	
	<script>
		$(document).ready(function(){
			$("#item-<?=$item->id?> .stars").rateYo({
			    rating: <?=$item->rate?>,
			    readOnly: true,
			    /*multiColor: {
			        "startColor": "#eeeeee", //RED
			        "endColor"  : "#FFAF18"  //GREEN
				},*/
			    starWidth: "15px"
			  });
		})
		
		
		//	счётчик
		jQuery(function($){
			$('#clock-<?=$item->id?>').flipcountdown({
				size:"sm",
				speedFlip:20,
				beforeDateTime:'<?=$item->dateTill?>'
			});
		})
		</script>
<?php 	
} 
?>
</div>








<!-- подарки -->
<div class="presents" id="presents">

	<h1><img src="/img/present2.png" alt="" width="45" />&nbsp;Подарки!</h1>
	<p>
<?php 


if($presentsList)
{?>
	<?php 
	foreach($presentsList as $item)
	{?>
	<div class="item">
		<h2><?=$item->name?> <?=Admin::isAdmin() ? '<a href="/'.ADMIN_URL_SIGN.'/presents/?presentId='.$item->id.'" target="_blank" style="font-size: 12px; font-style: normal; font-weight: normal; ">[редактировать]</a>' : ''?></h2>
		<div class="text"><?=$item->text?></div>
		
		<div class="present">
			<? Core::renderPartial(SHARED_VIEWS_DIR.'/presentsList.php', $a = array('present'=>$item));?>
		</div>
		<hr />
	</div>
	<?php 	
	}?>
<?php 	
}
else
{?>
	На данный момент подарков нет.
<?php 	
}
?>
</div>
<!-- /подарки -->








<?=Funx::drawPages($MODEL['totalElements'], $MODEL['page'], $MODEL['elPP']);?>