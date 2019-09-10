<?$random = 22; ?>
<!DOCTYPE html>
<html lang="ru">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!-- <meta name="viewport" content="width=device-width; initial-scale=1.0001; maximum-scale=1.0001; minimum-scale=1.0001; user-scalable=no; target-densityDpi=device-dpi" /> -->
    <meta name="viewport" content="width=device-width; initial-scale=1.001; " />
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    
    <meta name="yandex-verification" content="9bd771476bdf0f4b" />
    
    <link rel="icon" href="/favicon.ico" type="image/x-icon">
    <link rel="shortcut icon" href="/favicon.ico" type="image/x-icon">
    
    <meta name="keywords" content="<?=$CONTENT->metaKeywords?>" />
	<meta name="description" content="<?=$CONTENT->metaDescription?>" />
	
    <title><?=$CONTENT->title?></title>

	<!-- jquery -->
<!--	<script type="text/javascript" src="/js/libs/jquery-1.11.0.min.js"></script>-->
	<script type="text/javascript" src="/js/libs/jquery-1.12.4.min.js"></script>
	<script type="text/javascript" src="/js/libs/jquery-ui.1.11.4.min.js"></script>
<!--      <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.2.4/jquery.min.js" integrity="sha256-BbhdlvQf/xTY9gja0Dq3HiwQF8LaCRTXxZKRutelT44=" crossorigin="anonymous"></script>-->

	<script type="text/javascript" src="/js/common.js?<?=$random?>"></script>
	<script type="text/javascript" src="/js/script.js?<?=$random?>"></script>
	<!--кабинет-->
	<script src="/js/slonne.cabinet.js" type="text/javascript"></script>
	
	<!--stickr-->
	<script src="/js/plugins/jquery.stickr.js" type="text/javascript"></script>

	<!--fancyBox-->
	<script type="text/javascript" src="/js/plugins/jquery.fancyBox-v2.1.5/jquery.fancybox.pack.js?v=2.1.5"></script>
	<link rel="stylesheet" type="text/css" href="/js/plugins/jquery.fancyBox-v2.1.5/jquery.fancybox.css?v=2.1.5" media="screen" />

    <!-- Bootstrap -->
    <link href="/css/bootstrap-3.3.6-dist/css/bootstrap.min.css" rel="stylesheet">

    <!--LESS-->
	<link rel="stylesheet/less" type="text/css" href="/css/style.less?<?=$random?>" />
	<!-- <link href="/css/style.css" rel="stylesheet"> -->
	<script src="/js/libs/less/less-1.7.3.min.js" type="text/javascript"></script>

	<!-- font-awesome -->
	<link href="/css/font-awesome-4.5.0/css/font-awesome.min.css" rel="stylesheet" type="text/css">


	<!-- unslider -->
	<script type="text/javascript" src="/js/plugins/jquery.unslider/dist/js/unslider-min.js"></script>
	<link rel="stylesheet" type="text/css" href="/js/plugins/jquery.unslider/dist/css/unslider2.css" media="screen" />
	<link rel="stylesheet" type="text/css" href="/js/plugins/jquery.unslider/dist/css/unslider-dots.css" media="screen" />


	<!-- Open Sans -->
	<link href='http://fonts.googleapis.com/css?family=Open+Sans:400,400italic,300,300italic,600,600italic,700,700italic&subset=latin,cyrillic' rel='stylesheet' type='text/css' />

	<!-- rateYo -->
	<link rel="stylesheet" href="/js/plugins/jquery.rateYo-master/min/jquery.rateyo.min.css"/>
	<script type="text/javascript" src="/js/plugins/jquery.rateYo-master/min/jquery.rateyo.min.js"></script>

	<!-- masked-input -->
	<script type="text/javascript" src="/js/plugins/jquery.maskedInput.js"></script>

	<!-- flipcountdown -->
	<script type="text/javascript" src="/js/plugins/flipcountdown-master/jquery.flipcountdown.js"></script>
	<link rel="stylesheet" href="/js/plugins/flipcountdown-master/jquery.flipcountdown2.css">

	<!-- fliClock -->
	<script type="text/javascript" src="/js/plugins/FlipClock-master/compiled/flipclock.min.js"></script>
	<link rel="stylesheet" href="/js/plugins/FlipClock-master/src/flipclock/css/flipclock.css">





	
	<!-- highslide -->
	<script type="text/javascript" src="/js/libs/highslide-4.1.13/highslide-with-gallery.packed.js"></script>
	<link rel="stylesheet" type="text/css" href="/js/highslide/highslide.css" />
	<script>
	hs.graphicsDir = '/js/libs/highslide-4.1.13/graphics/';
	hs.align = 'center';
	hs.transitions = [];
	hs.outlineType = 'rounded-white';
	hs.fadeInOut = true;
	hs.headingEval = '';
	hs.captionEval = '';
	hs.lang.creditsText = '';
	//hs.dimmingOpacity = 0.75;
	//alert(hs.lang.creditsText)
	
	// Add the controlbar
	hs.addSlideshow({
		//slideshowGroup: 'group1',
		interval: 5000,
		repeat: false,
		useControls: false,
		fixedControls: true,
		overlayOptions: {
			opacity: .75,
			position: 'bottom center',
			hideOnMouseOut: true
		}
	});
	</script>
	
<script language="javascript">
document.ondragstart = xenforo;
document.onselectstart = xenforo;
document.oncontextmenu = xenforo;
function xenforo() {return false}
</script>



<script>
function fixFooter(){$('.centered-wrapper').css('min-height', $(window).height()-83)}

$(document).ready(function(){
	fixFooter()
})

$(window).resize(function() {
	fixFooter()
});
</script>



<style>
	.currencies .price-currency {display: none;}
	<?
	foreach(Currency::$items as $cur)
	{?>
	.currencies.<?=$cur->code?> .price-currency-<?=$cur->code?> {display: inline-block;}
	<?php 
	}?>
</style>


 <script>
    /* (function(doc) {
          var viewport = document.getElementById('viewport');
          if ( navigator.userAgent.match(/iPhone/i) || navigator.userAgent.match(/iPod/i)) {
            if(window.orientation == 0 || window.orientation == 180){
              viewport.setAttribute("content", "initial-scale=0.25,width=480");
            } else if(!window.orientation) {
              viewport.setAttribute("content", "initial-scale=0.25,width=480");
            } else {
              viewport.setAttribute("content", "initial-scale=0.55,width=480");
            }
            
          } else if ( navigator.userAgent.match(/iPad/i) ) {
            if(window.orientation == 0 || window.orientation == 180){
              viewport.setAttribute("content", "initial-scale=0.55,width=800");
            } else {
              viewport.setAttribute("content", "initial-scale=0.75,width=800");
            }
          }
      }(document));*/
  </script>



<!--      <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">-->
<!--      <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>-->
<!---->


  </head>
  
  
  
  <body>

  <?if($ADMIN && $ADMIN->id == 1):?>
    <!--DeV PaNeL-->
    <div class="dev-panel" style=" position: fixed; z-index: 100;  border: 2px solid red; display: block; width: 100%;  ">
      <div class="btns" >
          DeV PaNeL:
          <button type="button" onclick=" SlonneDev.console.toggle(); ">toggle</button>
          <button type="button" onclick=" SlonneDev.console.clear(); SlonneDev.console.show(); ">clear</button>
          <button type="button" onclick=" SlonneDev.console.vd({a:5, qwe:[1,5,3],pop:{bb:7, mmmm:10}}); SlonneDev.console.show(); ">vd()</button>
<!--          <button type="button" onclick=" SlonneDev.console.vd(123); SlonneDev.console.show(); ">CooKie</button>-->
          <button type="button" onclick=" SlonneDev.cart(); SlonneDev.console.show(); ">CaRT <sup>new</sup></button>
<!--          <button type="button" onclick=" Cart2.load(); SlonneDev.cart(); ">CaRT LoaD <sup>new</sup></button>-->
<!--          <button type="button" onclick=" Cart2.reset();  SlonneDev.cart(); ">CaRT CLeaR!!!</button>-->

          <button type="button" class="close-btn" onclick="$('.dev-panel').slideToggle('fast');" >&times;</button>
      </div>
      <div class="data" style="display: none; ">asdasd
          <div class="item cart">
          </div>
      </div>
    </div>
    <!--/DeV PaNeL-->
  <?endif;?>


    
   
	<div class="karkas">
		
		
		
		<!-- top-menu -->
		<div class="top-menu-wrapper">
			<div class="relative-wrapper">
				<a href="/index" class="logo"><img src="/img/logo.png" alt="" /></a>
				<a href="#" class="btn-dropdown" title="Открыть меню" onclick="$('#top-menu').slideToggle('fast'); return false; "><i class="fa fa-bars" aria-hidden="true"></i></a>
				
				<nav class="top-menu" id="top-menu">
					<ul  >
						<?php 
						
						$active = $_GLOBALS['activeMenu'];
						foreach($_GLOBALS['menu'] as $val)
						{?>
							<li class=" top-primary-menu <?=$active[$val->id] ? 'active' : ''?>">
							<?php 
							if(!$val->subs)
							{?>
								<a href="<?=$val->url()?>"><?=$val->attrs['name']?></a>
							<?php 	
							}
							else
							{?>
								<a href="#" onclick="switchMenu(<?=$val->id?>); return false;"><?=$val->attrs['name']?></a>
								<ul class="top-submenu" id="top-sub-<?=$val->id?>" style="display: <?=$active[$val->id] ? '' : 'none'?>; ">
								<?php 
								if($val->id==35)
								{?>
									<?php 
									foreach(Currency::$items as $cur)
									{
										$href='?globalCurrency='.$cur->code.'';
										$onclick='';
										
										if($_GLOBALS['switchCurrencyAjax'])
										{
											$href='#';
											$onclick='switchCurrency(\''.$cur->code.'\'); return false; ';
										}
										
									?>
										<li class="cur-btn cur-btn-<?=$cur->code?> <?=$cur->code == $_GLOBALS['currency']->code ? 'active' : '' ?>">
											<a href="<?=$href?>" onclick="<?=$onclick?>" title="<?=$cur->code?>"><?=$cur->code?></a>
										</li>
									<?php 	
									}?>
								<?php 	
								}
								else
								{
									foreach($val->subs as $val2)
									{?>
										<li class="<?=$active[$val2->id] ? 'active' : ''?>"><a href="<?=$val2->url()?>"><?=$val2->attrs['name']?></a></li>	
									<?php 	
									}
								}?>	
								</ul>
							<?php 	
							}?>
							
							</li>
						<?php 	
						}?>
					</ul>
					<div class="copy">&copy; <?=date('Y')?> Все права защищены.<br><a href="/"><?=DOMAIN_CAPITAL?></a></div>
				</nav>
				
				<a href="#" class="btn-cart" title="Корзина" onclick="$('#top-cart').slideToggle('fast'); return false; "><i class="fa fa-shopping-cart" aria-hidden="true"></i></a>
				<div id="cart-items-quan"><?=intval($CART->totalQuan)?></div>
				<div id="top-cart" class="top-cart">
					<!-- корзина MOBILE -->
					<div class=" top-cart-mobile">
					<?php Core::renderPartial(SHARED_VIEWS_DIR.'/cart/topCartInfoMobile.php', $arr = array('cart'=>$CART));?>
					</div>
					<!-- /корзина MOBILE -->
				</div>
			</div>
		</div>
		<!-- /top-menu -->
		
		
		
		
		
		<div class="left-panel">
			<div class="logo"><a href="/index"><img src="/img/logo.png" alt="" /></a></div>
			<nav>
			<?php //vd($_GLOBALS['menu'])?>
				<ul class="left-menu">
				<?php 
				$active = $_GLOBALS['activeMenu'];
				//vd($active);
				foreach($_GLOBALS['menu'] as $val)
				{?>
					<li class=" left-primary-menu  <?=$active[$val->id] ? 'active' : ''?>">
					<?php 
					if(!$val->subs)
					{?>
						<a href="<?=$val->url()?>"><?=$val->attrs['name']?></a>
					<?php 	
					}
					else
					{?>
						<a href="#" onclick="switchMenu(<?=$val->id?>); return false; "><?=$val->attrs['name']?></a>
						<ul class="left-submenu" id="left-sub-<?=$val->id?>" style="display: <?=$active[$val->id] ? '' : 'none'?>; ">
						<?php 
						if($val->id==35)
						{?>
							<?php 
							foreach(Currency::$items as $cur)
							{
								$href='?globalCurrency='.$cur->code.'';
								$onclick='';
								
								if($_GLOBALS['switchCurrencyAjax'])
								{
									$href='#';
									$onclick='switchCurrency(\''.$cur->code.'\'); return false; ';
								}
								
							?>
								<li class="cur-btn cur-btn-<?=$cur->code?> <?=$cur->code == $_GLOBALS['currency']->code ? 'active' : '' ?>">
									<a href="<?=$href?>" onclick="<?=$onclick?>" title="<?=$cur->code?>"><?=$cur->code?></a>
								</li>
							<?php 	
							}?>
						<?php 	
						}
						else
						{
							foreach($val->subs as $val2)
							{?>
								<li class="<?=$active[$val2->id] ? 'active' : ''?>"><a href="<?=$val2->url()?>"><?=$val2->attrs['name']?></a></li>	
							<?php 	
							}
						}?>	
						</ul>
					<?php 	
					}?>
					
					</li>
				<?php 	
				}?>
				</ul>
			</nav>
			<div class="copy">&copy; <?=date('Y')?> Все права защищены.<br><a href="/"><?=DOMAIN_CAPITAL?></a></div>
		</div>
		<div class="main-wrapper">
			
			<div class="centered-wrapper">
				
				
				<div class="site-top-wrapper">
				
					<!-- валюта -->
					<div class="kol currency-wrapper">
						<ul class="currency2">
							<li class="label">Валюта: </li>
						<?php 
						foreach(Currency::$items as $cur)
						{
							$href='?globalCurrency='.$cur->code.'&noExpand';
							$onclick='';
							
							if($_GLOBALS['switchCurrencyAjax'])
							{
								$href='#';
								$onclick='switchCurrency(\''.$cur->code.'\'); return false; ';
							}
							
						?>
							<li class="cur-btn cur-btn-<?=$cur->code?> <?=$cur->code == $_GLOBALS['currency']->code ? 'active' : '' ?>">
								<a href="<?=$href?>" onclick="<?=$onclick?>" title="<?=$cur->code?>"><?=$cur->sign?></a>
							</li>
						<?php 	
						}?>
						</ul>
					</div>
					<!-- //валюта -->
					
					<!-- корзина DESKTOP -->
					<div class="kol top-cart-wrapper">
					<?php Core::renderPartial(SHARED_VIEWS_DIR.'/cart/topCartInfoDesktop.php', $arr = array('cart'=>$CART));?>
					</div>
					<!-- /корзина DESKTOP -->
					
					
					<!-- авторизация -->
					<div class="kol auth-wrapper">
						<div class="top-auth">
						<?php 
						if(!$USER)
						{?>
							<a href="#"  class="b btn-auth" onclick="$('#top-auth-form').fadeIn('fast'); return false; ">Вход</a>
							<a href="<?=Route::getByName(Route::CABINET_PROFILE_EDIT)->url()?>" class="b btn-reg">Регистрация</a>
							
							<div class="clear"></div>
							
							
							<form method="post" action="" id="top-auth-form" target="iframe1" id="top-auth-form" onsubmit="return Cabinet.checkAuthForm('top-auth-form'); ">
								<div class="inner">
									<a href="#" onclick="$('#top-auth-form').fadeOut('fast'); return false; " class="close1">&times;</a>
									<div class="row"><span class="lbl">E-mail: </span><input type="text" name="email" placeholder="E-mail" /></div>
									<div class="row"><span class="lbl">Пароль: </span><input type="password" name="password" placeholder="Пароль"  /></div>
									
									<div class="buttons">
										<input type="submit" value="Войти">
										<a href="<?=Route::getByName(Route::CABINET_PASSWORD_RESET_CLAIM)->url()?>" class="forgot">забыли пароль?</a>
										<div class="clear"></div>
									</div>
									
									<div class="loading" style="display: none; ">Секунду...</div>
									<div class="info"></div>
									
								</div>
							</form>
						
						<?php 	
						}
						else
						{?>
							<div class="authed-user">
								<a href="<?=Route::getByName(Route::CABINET)->url()?>"  class="b btn-auth" title="Личный кабинет"><i class="fa fa-user"></i> <b><?=$USER->name?></b></a>
								<a href="#" class="b btn-reg" onclick="if(confirm('Вы хотите выйти?')){Cabinet.logout();} return false; " title="Выйти"><i class="fa fa-sign-out" ></i></a>
							</div>
						<?php 	
						}?>
						</div>
					</div>
					<!-- //авторизация -->
					
					<div class="clear"></div>
				</div>
				
				<?=$CONTENT->content?>


            </div>
            
            <footer class="footer">

            	<div class="social">
            		<div class="left">
            			<div class="inner"><img src="/img/speech-bubble.png" alt="" />Присоединяйся к нам в группы<br>И будь в курсе всех новостей.</div>
            		</div>
            		<div class="right">
	            		<div class="inner">
	            			<!-- <a href="<?=$_CONFIG['SETTINGS']['twitter']?>" class="soc-btn soc-twitter" title="Twitter" target="_blank"><i class="fa fa-twitter" aria-hidden="true"></i></a>
	            			<a href="<?=$_CONFIG['SETTINGS']['facebook']?>" class="soc-btn soc-facebook" title="Facebook" target="_blank"><i class="fa fa-facebook" aria-hidden="true"></i></a>
	            			<a href="<?=$_CONFIG['SETTINGS']['vk']?>" class="soc-btn soc-vk" title="ВКонтакте" target="_blank"><i class="fa fa-vk" aria-hidden="true"></i></a>
	            			<a href="<?=$_CONFIG['SETTINGS']['instagram']?>" class="soc-btn soc-insta" title="Instagram" target="_blank"><i class="fa fa-instagram" aria-hidden="true"></i></a> -->
	            			
	            			<a href="<?=$_CONFIG['SETTINGS']['youtube']?>" class="soc-btn soc-youtube" title="YouTube" target="_blank"><img src="/img/soc-youtube.png" alt="" /></a>
	            			<a href="<?=$_CONFIG['SETTINGS']['vk']?>" class="soc-btn soc-vk" title="ВКонтакте" target="_blank"><img src="/img/soc-vk.png" alt="" /></a>
	            			<a href="<?=$_CONFIG['SETTINGS']['facebook']?>" class="soc-btn soc-facebook" title="Facebook" target="_blank"><img src="/img/soc-facebook.png" alt="" /></a>
	            			<a href="<?=$_CONFIG['SETTINGS']['instagram']?>" class="soc-btn soc-insta" title="Instagram" target="_blank"><img src="/img/soc-insta.png" alt="" /></a>
	            			<a href="<?=$_CONFIG['SETTINGS']['twitter']?>" class="soc-btn soc-twitter" title="Twitter" target="_blank"><img src="/img/soc-twitter.png" alt="" /></a>
	            			
            			</div>
					</div>
					<div class="clear"></div>
            	</div>
            	<div class="orange">Для консультации звоните по номеру <b><?=$_CONFIG['SETTINGS']['contactPhone']?></b> или пишите на почту <a href="mailto:<?=$_CONFIG['SETTINGS']['contactEmail']?>"><?=$_CONFIG['SETTINGS']['contactEmail']?></a></div>
            </footer>
			
		</div>
	</div>
   





	
		<!-- МОДАЛКА -->
		<!-- Trigger the modal with a button -->
		<!-- <button type="button" data-toggle="modal" data-target="#myModal">Open Modal</button> -->

  <!-- Modal -->
  <div id="myModal" class="modal fade" role="dialog">
      <div class="modal-dialog">

          <!-- Modal content-->
          <div class="modal-content">
              <div class="modal-header" style="text-align: center; ">
                  <button type="button" class="close" data-dismiss="modal">&times;</button>
                  <img src="/img/logo.png" height="50" alt="" />
              </div>
              <div class="modal-body">
                  ывффыв
              </div>
              <!-- <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Продолжить покупки</button>
              </div> -->
          </div>

      </div>
  </div>









  <!-- Bootstrap core JavaScript
  ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="/css/bootstrap-3.3.6-dist/js/bootstrap.min.js"></script>
    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <script src="../../assets/js/ie10-viewport-bug-workaround.js"></script>
    
    <!-- pozvonim.com -->
    <script crossorigin="anonymous" async type="text/javascript" src="//api.pozvonim.com/widget/callback/v3/f46e63bf1f61bcbacf47adde79200366/connect" id="check-code-pozvonim" charset="UTF-8"></script>
    
    <!-- google analytics -->
    <script>
(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
})(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

ga('create', 'UA-83898254-1', 'auto');
ga('send', 'pageview');

</script>


  <script>
      Currency.items = <?=json_encode(Currency::$items)?>;
      Currency.set('<?=$_SESSION['currencyCode']?>')
      // alert(vd(Currency.items, true))
  </script>


  <script type="text/javascript" src="/js/optCart.js?<?=$random?>"></script>
  <script type="text/javascript" src="/js/OptCartNotification.js?<?=$random?>"></script>
  <script type="text/javascript" src="/js/slonne.dev.js?<?=$random?>"></script>


  <?=$CONTENT->section('documentReadyJs')?>

  <script>
      $(document).ready(function(){
          // OptCartNotification.showBookmark()
          OptCart.Notificator.update()
          // OptCartNotification.updateInfo(true)
      })
  </script>
    
  </body>
</html>
	
	
	<? //vd($_SESSION)?>
	
	