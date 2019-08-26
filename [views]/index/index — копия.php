<?php
//vd($MODEL['text']);
$text = $MODEL['text'];

$mainAction = $MODEL['mainAction'];
$sliderActions = $MODEL['sliderActions'];
$sliderAction = $MODEL['sliderAction'];
$sliderCourses = $MODEL['sliderCourses'];
$sliderCourse = $MODEL['sliderCourse'];

$actions = $MODEL['actions'];
$courses = $MODEL['courses'];


//vd($mainAction);
//vd($sliderCourse);
?>






<div class="index">


	<div class="top-info">
	
		<div style="text-align: center; margin: 30px 0 40px 0 ;"><img src="/img/logo.png" alt="" /></div>	
		<div class="text"><?=$text->attrs['descr']?></div>
	</div>
	
	
	
	
	
	
	<!-- слайдер главный -->
	<div class="index-main-slider">
	
		<div class="unslider">
			<ul >
			  
			  <!-- слайдер-акция -->
			  <?php Core::renderPartial('index/sliderActionPartial.php', $a = array('action'=>$sliderAction))?>
			  <?php 
			  	if($sliderAction)
			  	{
				  	$a = new DateTime();
					$b = new DateTime($sliderAction->dateTill);
					$diffInSeconds = $b->getTimestamp() - $a->getTimestamp();
					?>
			  <li class="index-slider-action zoom-on-hover slide1" style="background-image: url(<?='/'.UPLOAD_IMAGES_REL_DIR.''.$sliderAction->photo?>)">
			  	
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
			  <!-- /слайдер-акция -->
			  	
			  	
			  	
			  <!-- слайдер-курс -->
			  <?php 
			  	if($sliderCourse )
			  	{?>
			  <li class="index-slider-course zoom-on-hover slide1" style="background-image: url(<?='/'.UPLOAD_IMAGES_REL_DIR.''.$sliderCourse->photo?>)">
			  	
			  		<a href="<?=$sliderCourse->url()?>" class="title"><span class="discount">КУРС: </span><?=$sliderCourse->name?> <span class="discount">-<?=$sliderCourse->discount?>%</span></a> 
					<div class="anons">
						<div class="consist"><b>Состав: </b><br /><?=$sliderCourse->consist?></div>
						<div class="goal"><b>Цель: </b><br /><?=$sliderCourse->goal?></div>
					</div>
					
					
					<div><a href="<?=$sliderCourse->url()?>" class="btn">Смотреть курс</a></div>
			  </li>
			  <?php 	
			  }?>
			  <!-- /слайдер-курс -->
			  
			  
			  <!-- <li style="background: blue;">f</li>-->
			</ul>
		</div>
		
		
		
		<script>
		$(document).ready(function(){
			var unslider = $('.unslider').unslider({
					arrows: {
						prev: '<a class="unslider-arrow prev"><</a>',
						next: '<a class="unslider-arrow next">></a>'
					},
					speed: '600' ,
					autoplay: true,
					delay: 8000
				});

		})
		</script>
	
	</div>
	<!-- /слайдер главный -->
	
	
	
	
	
	
	<h1 class="blue" style="margin: 0;">На нашем сайте вы найдёте:</h1>
	<div class="index-cats">
	<?php 
	
	foreach($MODEL['cats'] as $cat)
	{
		if($cat->id == 6)
			continue;?>
		<a href="<?=$cat->url()?>" class="item" title="<?=$cat->name?>" style="background-image: url(<?='/'.UPLOAD_IMAGES_REL_DIR.''.$cat->photo?>)">
			<span class="title"><?=$cat->name?><span class="discount"></span>
		</a>
	<?php 	
	}?>
		<div class="clear"></div>
	</div>
	
	
	
	
	
	<!-- главная акция -->
	<h1 class="orange" style="margin: 15px 0 0 0; ">Участвуйте в наших акциях</h1>
	<?php 
	if($mainAction)
	{
		$a = new DateTime();
		$b = new DateTime($mainAction->dateTill);
		$diffInSeconds = $b->getTimestamp() - $a->getTimestamp();?>
	<div class="main-action">
		<a href="<?=$mainAction->url()?>" class="title"><?=$mainAction->name?> <span class="discount">-<?=$mainAction->discount?>%</span></a> 
		<div class="anons"><?=$mainAction->anons?></div>
		
		<!-- <div class="countdown-wrapper">
			<div class="countdown">
				<div id="main-clock" ></div>
				<div class="time-lbl days">Дней</div>
				<div class="time-lbl hours">Часов</div>
				<div class="time-lbl minutes">Минут</div>
				<div class="time-lbl secs">Секунд</div>
			</div>
			<div class="clear"></div>
		</div>
		<br />
		
		<div class="countdown-wrapper-mobile">
			<div class="countdown">
				<div id="main-clock-mobile" ></div>
				<div class="time-lbl days">Дней</div>
				<div class="time-lbl hours">Часов</div>
				<div class="time-lbl minutes">Минут</div>
				<div class="time-lbl secs">Секунд</div>
			</div>
			<div class="clear"></div>
		</div>-->
		
		
		
		<div class="countdown-wrapper" >
			<br />
			<div class="countdown">
				<!-- <div id="clock" ></div>-->
				
				<div class="countdown-wrapper-relative">
					<div class="countdown-wrapper-inner">
						<div id="flipclock-main-action" class="scale-4" style=" "></div>
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
				
				clock = $('#flipclock-main-action').FlipClock({
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
		
		
		
		
		
		<div><a href="<?=$mainAction->url()?>" class="btn">Смотреть товары</a></div>
		
		<script>
//		счётчик
		jQuery(function($){
			$('#main-clock').flipcountdown({
				size:"lg",
				speedFlip:20,
				beforeDateTime:'<?=$mainAction->dateTill?>'
			});
		})
		jQuery(function($){
			$('#main-clock-mobile').flipcountdown({
				size:"md",
				speedFlip:20,
				beforeDateTime:'<?=$mainAction->dateTill?>'
			});
		})
		</script>
		
		
	</div>
	<?php 
	}?>
	<!-- /главная акция -->
	<a href="/actions"><h1 class="orange" style="margin: 0px 0 0 0 ; ">Смотреть все акции...</h1></a>
	
	
	
	
	
	
	
	
	
	
	<h1 class="red" style="margin: 30px 0 14px 0; ">Наши новинки</h1>
	<div class="index-novinki">
	<?php Core::renderPartial('catalog/productsListHorizontal.php', $arr=array('items'=>$MODEL['novinki']))?> 
	</div>
	<a href="/catalog/novinki"><h1 class=red style="margin: 0px 0 0 0 ; ">Смотреть все новинки...</h1></a>
	
	
	
	
	<h1 class="blue" style="margin: 30px 0 0 0 ; ">Готовые курсы</h1>
	<div class="index-courses">
	<?php 
	/*foreach($courses as $course)
		$courses[] = $course; */
	
	foreach($courses as $course)
	{?>
		<a href="<?=$course->url()?>" class="item" title="<?=$course->name?>" style="background-image: url(<?='/'.UPLOAD_IMAGES_REL_DIR.''.$course->photo?>)">
			<span class="title"><?=$course->name?><span class="discount"> -<?=$course->discount?>%</span></span>
		</a>
	<?php 	
	}?>
		<div class="clear"></div>
	</div>
	<a href="/courses"><h1 class="blue" style="margin: 0px 0 0 0 ; ">Смотреть все курсы...</h1></a>
	
	
	
	
</div>

