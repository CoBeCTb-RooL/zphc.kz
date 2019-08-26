<?php 
$sliderAction = $MODEL['action'];
//vd($sliderAction);

			  	if($sliderAction)
			  	{
				  	$a = new DateTime();
					$b = new DateTime($sliderAction->dateTill);
					$diffInSeconds = $b->getTimestamp() - $a->getTimestamp();
					?>
			  <li class="index-slider-action zoom-on-hover slide1" style="background-image: url('<?='/'.UPLOAD_IMAGES_REL_DIR.''.$sliderAction->photo?>')">
			  	
			  		<a href="<?=$sliderAction->url()?>" class="title"><span class="discount">АКЦИЯ: </span> <?=$sliderAction->name?> <span class="discount">-<?=$sliderAction->discount?>%</span></a> 
					<div class="anons"><?=$sliderAction->anons?></div>
					
					<div class="countdown-wrapper" >
					<div class="countdown">
						<!-- <div id="clock" ></div>-->
						
						<div class="countdown-wrapper-relative">
							<div class="countdown-wrapper-inner">
								<div id="flipclock-slider-action" class="scale-4" style=" "></div>
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
						
						clock = $('#flipclock-slider-action').FlipClock({
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
			  	
			  </li>
			  <?php 	
			  }?>