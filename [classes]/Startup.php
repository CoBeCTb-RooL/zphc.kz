<?php
class Startup{
	
	const FRONTEND 	= 'frontend';
	const ADMIN  	= 'admin';
	
	
	function execute($type)
	{
		self::common();
		
		if($type)
		{
			if(method_exists('Startup', $type))
				call_user_func('Startup::'.$type);
			else echo 'eRRoR: uNDeFiNeD STaRTuP SCeNaRio <b>"'.$type.'"</b>.';
		}
	}
	
	
	
	
	
	########################
	#####  ФРОНТЭНД	########
	########################
	function frontend()
	{
		//global $CORE, $_GLOBALS, $_CONFIG, $_PARAMS, $_CONST;
		require(GLOBAL_VARS_SCRIPT_FILE_PATH);
		
		$CONTENT->titlePostfix = $_CONFIG['SETTINGS']['title_postfix'];
		$CONTENT->titleSeparator = $_CONFIG['SETTINGS']['title_separator'];
		$CONTENT->metaKeywords = $_CONFIG['SETTINGS']['keywords'];
		$CONTENT->metaDescription = $_CONFIG['SETTINGS']['description'];
		
		
		# 	инициализируем коэффициенты валют
		foreach(Currency::$items as $cur)
			$cur->coef = floatval($_CONFIG['SETTINGS'][$cur->code]);
			
		# 	выбранная валюта
		if($_REQUEST['globalCurrency'])
		{
			if(Currency::code($_REQUEST['globalCurrency']))
				$_SESSION['currencyCode'] = $_REQUEST['globalCurrency'];
		}
		if(!$_SESSION['currencyCode'])
			$_SESSION['currencyCode'] = Currency::USD;
		$_GLOBALS['currency'] = Currency::code($_SESSION['currencyCode']);
				
		
		
		
		#	инициализируем юзера
		if($_SESSION['user'])
		{
			$USER = User::get($_SESSION['user']['id']);
			if(!$USER)
				unset($_SESSION['user']);
			else 
				$USER->initBonusInCurrency();
		}
		
		
		# 	меню
		$arr = array();
		$tmp = Page::getChildren(30);
		foreach($tmp as $val)
		{
			if($tmp2 = Page::getChildren($val->id))
				$val->subs = $tmp2;
			$arr[$val->id] = $val;
		}
		$_GLOBALS['menu'] = $arr;
		
		
		# 	работаем над подсвечиванием выбранного пункта ВАЛЮТЫ
		foreach($_GLOBALS['menu'] as $root)
		{
			if($root->id == 35)
			{
				if($_REQUEST['globalCurrency'] && !isset($_REQUEST['noExpand']))
					$_GLOBALS['activeMenu'][$root->id] = true;
				foreach($root->subs as $sub)
					if($sub->attrs['link'] == '?globalCurrency='.$_GLOBALS['currency']->code && !isset($_REQUEST['noExpand']))
						$_GLOBALS['activeMenu'][$sub->id] = true;
			}
		}
		//vd($_GLOBALS['activeMenu']);
		
		$_GLOBALS['switchCurrencyAjax'] = true;
		
		# 	инициализируем корзину
		$CART = new Cart();
		$CART->init();
		
	}
	
	
	
	
	########################
	#####  АДМИН	########
	########################
	function admin()
	{
		require(GLOBAL_VARS_SCRIPT_FILE_PATH);

		$CONTENT->title = 'SLoNNe CMS';
		
		#	редирект к авторизации
		if($CORE->controller!='authController' && !$ADMIN )
			Slonne::redirect("auth");
		
	}
	
	
	
	
	

	
	
	########################
	#####  ОБЩИЕ	########
	########################
	function common()
	{
		require(GLOBAL_VARS_SCRIPT_FILE_PATH);
		
		#	инициализация настроек
		$_CONFIG['SETTINGS'] = Settings::get();
		
		//echo "!";
		# 	инициализация админа
		if($_SESSION['admin'])
		{
			$ADMIN = Admin::get($_SESSION['admin']['id'], Status::code(Status::ACTIVE));
			if($ADMIN)
				$ADMIN->initGroup();
		}
	}
	
	
	
	
	
}