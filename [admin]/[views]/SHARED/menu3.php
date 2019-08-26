<?php

//$uri = $_SERVER['REQUEST_URI'];
$uri = $_SERVER['PATH_INFO'];
//vd($uri);
//vd($_SERVER);
$section = '';
$subsection = '';



/*if($uri == '/'.ADMIN_URL_SIGN.'/entity/showList/pages/')
	$section = 'razdeli';*/
if(		strpos($uri, '/'.ADMIN_URL_SIGN.'/entity/showList/pages') === 0)
{
	$section = 'content';
	$subsection = 'pages';
}
elseif(		strpos($uri, '/'.ADMIN_URL_SIGN.'/entity/showList/articles') === 0)
{
	$section = 'content';
	$subsection = 'articles';
}
elseif(strpos($uri, '/'.ADMIN_URL_SIGN.'/entity/showList/news') === 0)
{
	$section = 'content';
	$subsection = 'news';
}


elseif(strpos($uri, '/'.ADMIN_URL_SIGN.'/categories') === 0)
{
	$section = 'adv';
	$subsection = 'categories';
}
elseif(strpos($uri, '/'.ADMIN_URL_SIGN.'/props') === 0)
{
	$section = 'adv';
	$subsection = 'props';
}
elseif(strpos($uri, '/'.ADMIN_URL_SIGN.'/classes') === 0)
{
	$section = 'adv';
	$subsection = 'classes';
}
elseif(strpos($uri, '/'.ADMIN_URL_SIGN.'/product_volume_unit') === 0)
{
	$section = 'adv';
	$subsection = 'product_volume_units';
}
elseif(strpos($uri, '/'.ADMIN_URL_SIGN.'/adv/brands') === 0)
{
	$section = 'adv';
	$subsection = 'brands';
}
elseif(strpos($uri, '/'.ADMIN_URL_SIGN.'/adv/article_numbers') === 0)
{
	$section = 'adv';
	$subsection = 'artNums';
}
elseif(strpos($uri, '/'.ADMIN_URL_SIGN.'/adv/items/itemsList') === 0)
{
	$section = 'adv';
	$subsection = 'advs';
	if($_REQUEST['status'] == Status::code(Status::MODERATION)->num)
		$subsection = 'premoderation';
}


elseif(strpos($uri, '/'.ADMIN_URL_SIGN.'/user') === 0)
{
	$section = 'users';
}
elseif(strpos($uri, '/'.ADMIN_URL_SIGN.'/comment') === 0)
{
	$section = 'adv';
	$subsection = 'comments';
}
elseif(strpos($uri, '/'.ADMIN_URL_SIGN.'/city') === 0)
{
	$section = 'adv';
	$subsection = 'cities';
}
elseif(strpos($uri, '/'.ADMIN_URL_SIGN.'/tools') === 0)
{
	$section = 'adv';
	$subsection = 'tools';
}



elseif(strpos($uri, '/'.ADMIN_URL_SIGN.'/adv') === 0
		|| $uri == '/'.ADMIN_URL_SIGN.'/product_volume_unit/'
		|| $uri == '/'.ADMIN_URL_SIGN.'/comment/'
		|| $uri == '/'.ADMIN_URL_SIGN.'/city/'
		|| $uri == '/'.ADMIN_URL_SIGN.'/tools/'
		)
	$section = 'adv';

elseif(strpos($uri, '/'.ADMIN_URL_SIGN.'/user') === 0)
	$section = 'users';

elseif($uri == '/'.ADMIN_URL_SIGN.'/entity/showList/news/')
	$section = 'news';

elseif($uri == '/'.ADMIN_URL_SIGN.'/essence/')
{
	$section = 'system';
	$subsection = 'essence';
}

elseif(strpos($uri, '/'.ADMIN_URL_SIGN.'/adminGroup') === 0)
{
	$section = 'system';
	$subsection = 'adminGroup';
}

elseif(strpos($uri, '/'.ADMIN_URL_SIGN.'/admin') === 0 )
{
	$section = 'system';
	$subsection = 'admin';
}

elseif(strpos($uri, '/'.ADMIN_URL_SIGN.'/backup') === 0 )
{
	$section = 'system';
	$subsection = 'backup';
}

elseif(strpos($uri, '/'.ADMIN_URL_SIGN.'/settings') === 0 )
{
	$section = 'system';
	$subsection = 'settings';
}

	
elseif(strpos($uri, '/'.ADMIN_URL_SIGN.'/tasks') === 0 )
	$section = 'tasks';
elseif(strpos($uri, '/'.ADMIN_URL_SIGN.'/review') === 0 )
	$section = 'reviews';
elseif(strpos($uri, '/'.ADMIN_URL_SIGN.'/course') === 0 )
	$section = 'course';
elseif(strpos($uri, '/'.ADMIN_URL_SIGN.'/actions') === 0 )
	$section = 'actions';
elseif(strpos($uri, '/'.ADMIN_URL_SIGN.'/orders') === 0 )
	$section = 'orders';
	
	

elseif(strpos($uri, '/'.ADMIN_URL_SIGN.'/catalogSimple/productsList') === 0)
{
	$section = 'catalog';
	$subsection = 'products';
}	
elseif(strpos($uri, '/'.ADMIN_URL_SIGN.'/catalogSimple/productsQuans') === 0)
{
	$section = 'catalog';
	$subsection = 'products_quans';
}
elseif(strpos($uri, '/'.ADMIN_URL_SIGN.'/discounts') === 0)
{
	$section = 'catalog';
	$subsection = 'discounts';
}
elseif(strpos($uri, '/'.ADMIN_URL_SIGN.'/presents') === 0)
{
	$section = 'catalog';
	$subsection = 'presents';
}
elseif(strpos($uri, '/'.ADMIN_URL_SIGN.'/catalogSimple/optPrices') === 0)
{
	$section = 'catalog';
	$subsection = 'optPrices';
}
elseif(strpos($uri, '/'.ADMIN_URL_SIGN.'/catalogSimple') === 0)
{
	$section = 'catalog';
	$subsection = 'categories';
}
	
	
# 	ЭТО ДОЛЖНО БЫТЬ В САМОМ КОНЦЕ!
elseif(strpos($uri, '/'.ADMIN_URL_SIGN) === 0)
	$section = 'index';	
	
	
	
//vd($subsection);
?>

<div class="top-menu-wrapper">
	<div id="menu">
		<ul class="primary">
			<li><a class="<?=$section=='index' ? 'active' : ''?>" href="/<?=ADMIN_URL_SIGN?>/"><i class="fa fa-pagelines"></i> Главная</a></li>
			
			<li>
				<a class="<?=$section=='content' ? 'active' : ''?>" href="#"><i class="fa  fa-book" aria-hidden="true"></i> Контент</a>
				<ul>
					<li class="<?=$subsection=='pages' ? 'active' : ''?>"><a  href="/<?=ADMIN_URL_SIGN?>/entity/showList/pages/"><i class="fa fa-sitemap"></i> Разделы</a></li>
					<li class="<?=$subsection=='articles' ? 'active' : ''?>"><a  href="/<?=ADMIN_URL_SIGN?>/entity/showList/articles/"><i class="fa fa-newspaper-o"></i> Статьи</a></li>
					<!-- <li class="<?=$subsection=='news' ? 'active' : ''?>"><a  href="/<?=ADMIN_URL_SIGN?>/entity/showList/news/"><i class="fa fa-newspaper-o"></i> Новости</a></li> -->
					<!-- <li class="<?=$subsection=='products' ? 'active' : ''?>"><a  href="/<?=ADMIN_URL_SIGN?>/entity/showList/products/"><i class="fa fa-sitemap1"></i> Товары</a></li> -->
				</ul>
			</li>
			
			
			
			
			<li>
				<a class="<?=$section=='catalog' ? 'active' : ''?>" href="#"><i class="fa  fa-book" aria-hidden="true"></i> Каталог</a>
				<ul>
					<li class="<?=$subsection=='categories' ? 'active' : ''?>"><a  href="/<?=ADMIN_URL_SIGN?>/catalogSimple/"><i class="fa fa-sitemap"></i> Категории</a></li>
					<li class="<?=$subsection=='products' ? 'active' : ''?>"><a  href="/<?=ADMIN_URL_SIGN?>/catalogSimple/productsList/"><i class="fa fa-newspaper-o"></i> Товары</a></li>
					<li class="delimiter"><hr /></li>
					<li class="<?=$subsection=='products_quans' ? 'active' : ''?>"><a  href="/<?=ADMIN_URL_SIGN?>/catalogSimple/productsQuans/"><i class="fa fa-newspaper-o"></i> Наличие и цена </a></li>
					<li class="<?=$subsection=='optPrices' ? 'active' : ''?>"><a  href="/<?=ADMIN_URL_SIGN?>/catalogSimple/optPrices"><i class="fa fa-list-alt"></i> Оптовые прайсы </a></li>
					<li class="delimiter"><hr /></li>
					<li class="<?=$subsection=='discounts' ? 'active' : ''?>"><a  href="/<?=ADMIN_URL_SIGN?>/discounts/"><i class="fa fa-percent"></i> Скидки на группы товаров </a></li>
					<li class="<?=$subsection=='presents' ? 'active' : ''?>"><a  href="/<?=ADMIN_URL_SIGN?>/presents/"><i class="fa fa-gift"></i> Подарочные товары</a></li>
					
					
				</ul>
			</li>
			
			<li>
				<a class="<?=$section=='orders' ? 'active' : ''?>" href="/<?=ADMIN_URL_SIGN?>/orders"><i class="fa fa-list-alt" ></i> Заказы</a>
			</li>
			
			
			<li>
				<a class="<?=$section=='course' ? 'active' : ''?>" href="/<?=ADMIN_URL_SIGN?>/course"><i class="fa  fa-calendar" aria-hidden="true"></i> Курсы</a>
			</li>
			
			<li>
				<a class="<?=$section=='actions' ? 'active' : ''?>" href="/<?=ADMIN_URL_SIGN?>/actions"><i class="fa fa-bullhorn" ></i> Акции</a>
			</li>
			
			
			<li>
				<a class="<?=$section=='reviews' ? 'active' : ''?>" href="/<?=ADMIN_URL_SIGN?>/review"><i class="fa  fa-comments" aria-hidden="true"></i> Отзывы</a>
			</li>
			
			
			<li>
				<a class="<?=$section=='users' ? 'active' : ''?>" href="/<?=ADMIN_URL_SIGN?>/user"><i class="fa fa-users" aria-hidden="true"></i> Пользователи</a>
			</li>
			
			
			<!-- 
			<?php 
			if($ADMIN->hasRole(Role::SYSTEM_ADMINISTRATOR))
			{?>
			<li>
				<a class="<?=$section=='adv' ? 'active' : ''?>" href="/<?=ADMIN_URL_SIGN?>/adv"><i class="fa fa-list-alt"></i> Объявления</a>
				<ul>
					<li class="<?=$subsection=='advs' ? 'active' : ''?>"><a href="/<?=ADMIN_URL_SIGN?>/adv/items/itemsList/"><i class="fa fa-list-alt"></i> Объявления</a></li>
					<li class="<?=$subsection=='premoderation' ? 'active' : ''?>"><a href="/<?=ADMIN_URL_SIGN?>/adv/items/itemsList/?status=<?=Status::code(Status::MODERATION)->num?>"><i class="fa fa-clock-o"></i> Премодерация</a></li>
					
					<li class="delimiter"><hr /></li>
					
					<li class="<?=$subsection=='categories' ? 'active' : ''?>"><a href="/<?=ADMIN_URL_SIGN?>/categories/"><i class="fa fa-tasks"></i> Категории</a></li>
					<li class="<?=$subsection=='props' ? 'active' : ''?>"><a href="/<?=ADMIN_URL_SIGN?>/props/"><i class="fa fa-cube"></i> Свойства</a></li>
					<li class="<?=$subsection=='classes' ? 'active' : ''?>"><a href="/<?=ADMIN_URL_SIGN?>/classes/"><i class="fa fa-cubes"></i> Классы</a></li>
					<li class="<?=$subsection=='product_volume_units' ? 'active' : ''?>"><a href="/<?=ADMIN_URL_SIGN?>/product_volume_unit/"><i class="fa fa-balance-scale"></i> Единицы изм.</a></li>
					
					<li class="delimiter"><hr /></li>
					
					<li class="<?=$subsection=='brands' ? 'active' : ''?>"><a href="/<?=ADMIN_URL_SIGN?>/adv/brands/"><i class="fa fa-cc-stripe"></i> Бренды</a></li>
					<li class="<?=$subsection=='artNums' ? 'active' : ''?>"><a href="/<?=ADMIN_URL_SIGN?>/adv/article_numbers/"><i class="fa fa-list-ol"></i> Арт. номера</a></li>
					
					<li class="delimiter"><hr /></li>
					
					<li class="<?=$subsection=='cat_brand_combine' ? 'active' : ''?>"><a href="/<?=ADMIN_URL_SIGN?>/adv/cat_brand_combine/"> Категория + бренд</a></li>
					<li class="<?=$subsection=='brand_artnum_combine' ? 'active' : ''?>"><a href="/<?=ADMIN_URL_SIGN?>/adv/brand_artnum_combine/"> Бренд + Арт.номер</a></li>
					<li class="<?=$subsection=='cat_brand_artnum_combine' ? 'active' : ''?>"><a href="/<?=ADMIN_URL_SIGN?>/adv/cat_brand_artnum_combine/"> Категория + бренд + арт.номер</a></li>
					
					<li class="delimiter"><hr /></li>
					
					<li class="<?=$subsection=='users' ? 'active' : ''?>"><a href="/<?=ADMIN_URL_SIGN?>/user"><i class="fa fa-users"></i> Пользователи</a></li>
					<li class="<?=$subsection=='comments' ? 'active' : ''?>"><a href="/<?=ADMIN_URL_SIGN?>/comment"><i class="fa fa-comments"></i> Комментарии</a></li>
					<li class="<?=$subsection=='cities' ? 'active' : ''?>"><a href="/<?=ADMIN_URL_SIGN?>/city"><i class="fa fa-globe"></i> Города</a></li>
					
					<li class="delimiter"><hr /></li>
					
					<li class="<?=$subsection=='tools' ? 'active' : ''?>"><a href="/<?=ADMIN_URL_SIGN?>/tools"><i class="fa fa-cogs"></i> Служебные</a></li>
					
				</ul>
			</li>
			<?php 
			}?>
			-->
			
			
			
			<?php 
			if($ADMIN->hasRole(Role::SUPER_ADMIN | Role::SYSTEM_ADMINISTRATOR | Role::ADMIN_GROUPS_MODERATOR | Role::ADMINS_MODERATOR) )
			{?>
			<li>
				<a class="<?=$section=='system' ? 'active' : ''?>" href="#"><i class="fa fa-cubes"></i> Системные</a>
				<ul>
					<!-- <li><a class="" href="/admin/module/"><i class="fa fa-cogs"></i> Модули</a></li> -->
					<?php 
					if($ADMIN->hasRole(Role::SUPER_ADMIN ))
					{?>
					<li class="<?=$subsection=='essence' ? 'active' : ''?>"><a href="/<?=ADMIN_URL_SIGN?>/essence/"><i class="fa fa-puzzle-piece"></i> Сущности</a></li>
					<li class="delimiter"><hr /></li>
					<?php 
					}?>
					
					
					
					<?php 
					if($ADMIN->hasRole(Role::ADMINS_MODERATOR ))
					{?>
					<li class="<?=$subsection=='admin' ? 'active' : ''?>"><a href="/<?=ADMIN_URL_SIGN?>/admin/"><i class="fa fa-user "></i> Администраторы</a></li>
					<?php 
					}?>
					
					<?php 
					if($ADMIN->hasRole(Role::ADMIN_GROUPS_MODERATOR))
					{?>
					<li class="<?=$subsection=='adminGroup' ? 'active' : ''?>"><a href="/<?=ADMIN_URL_SIGN?>/adminGroup/"><i class="fa fa-users "></i> Группы админов</a></li>
					<?php 
					}?>
					<li class="delimiter"><hr /></li>
					
					<?php 
					if($ADMIN->hasRole(Role::SYSTEM_ADMINISTRATOR ))
					{?>
					<li class="<?=$subsection=='settings' ? 'active' : ''?>" ><a href="/<?=ADMIN_URL_SIGN?>/settings/"><i class="fa fa-sliders"></i> Настройки сайта</a></li>
					<li class="<?=$subsection=='backup' ? 'active' : ''?>"><a href="/<?=ADMIN_URL_SIGN?>/backup/"><i class="fa fa-database"></i> Бэкап базы</a></li>
					<?php 
					}?>
				</ul>
			</li>
			<?php 
			}?>
			
			
			
			
			<?php 
			if($ADMIN->hasRole(Role::SYSTEM_ADMINISTRATOR))
			{?>
			<li><a class="<?=$section=='tasks' ? 'active' : ''?>" href="/<?=ADMIN_URL_SIGN?>/tasks"><i class="fa fa-sticky-note-o" aria-hidden="true"></i> Задачи</a></li>
			
			<? $getStr = '&'.Core::CRON_KEY_1.'='.Core::CRON_VALUE_1?>
			<li>
				<a class="<?=$section=='tools' ? 'active' : ''?>" href="#"><i class="fa fa-terminal" aria-hidden="true"></i> Тулзы</a>
				<ul>
					<li ><a href="/<?=ADMIN_URL_SIGN?>/tools/report" ><i class="fa fa-bar-chart"></i> Отчёты по тулзам</a></li>
					<li class="delimiter"><hr /></li>
					<li ><a href="/tools/getCurrency?execType=<?=ToolExecType::MANUAL?><?=$getStr?>" target="_blank"><i class="fa fa-clock-o"></i> <?=ToolType::code(ToolType::GET_CURRENCY)->name?></a></li>
				</ul>
			</li>
			<?php 
			}?>
			
			
			
		</ul>
	</div>
	<a href="#logout" class="exit2" onclick="if(confirm('Выйти из системы?')){logout(); return false;} else{return false} "><img src="/<?=ADMIN_DIR?>/img/exit.png" height="24" style="vertical-align: middle; " alt="" /><!-- <i class="fa fa-road"></i> -->Выйти</a>
</div>

