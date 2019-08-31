<?php 
class CatalogController extends MainController{
	
	function routifyAction()
	{
		global $CORE;
		$section = $CORE->params[0];
		$p = $CORE->params[1];
		//vd($CORE->params);
		//vd($CORE->params[0]);
		
		if($catId = intval($CORE->params[0]))
			$action = 'catView';
		

		if($action)
			$CORE->action = $action;
	}
	
	
	
	
	function index()
	{
		self::catsList();
	}
	
	
	function catview()
	{
		require(GLOBAL_VARS_SCRIPT_FILE_PATH);
		Startup::execute(Startup::FRONTEND);
		$CONTENT->setTitle('Каталог');
		
		$_GLOBALS['activeMenu'][32] = true;
		
		$MODEL['cat'] = CategorySimple::get($CORE->params[0]);
		if($MODEL['cat'])
		{
			$MODEL['items'] = ProductSimple::getList(array(
														'catId'=>$MODEL['cat']->id,
														'status'=>Status::code(Status::ACTIVE),
														'orderBy'=>'  idx ',
													));
		}
		
		# 	инициализируем скидки для товара
		foreach($MODEL['items'] as $item)
		{
			$item->initDiscountObject();
			//vd($item->discountObject);
		}
		
		
		
		$MODEL['crumbs'][] = '<a href="'.Route::getByName(Route::MAIN)->url().'">Главная</a>';
		$MODEL['crumbs'][] = 'Каталог';
		$MODEL['crumbs'][] = $MODEL['cat']->name;
		
		$MODEL['crumbs'] = $MODEL['crumbs'];
		
		foreach($_GLOBALS['menu'][32]->subs as $page)
			if(strpos($page->attrs['link'], $MODEL['cat']->url())!==false)
				$_GLOBALS['activeMenu'][$page->id] = true;
		
		Core::renderView('catalog/catView.php', $MODEL);
	}
	
	
	
	
	function item()
	{
		require(GLOBAL_VARS_SCRIPT_FILE_PATH);
		Startup::execute(Startup::FRONTEND);
		
		$_GLOBALS['activeMenu'][32] = true;
		
		$MODEL['item'] = ProductSimple::get(intval($CORE->params[1]));
		$MODEL['item']->initDiscountObject();
		$MODEL['item']->cat = CategorySimple::get(intval($MODEL['item']->catId));
		
		# 	достаём акции, в которых товар участвует 
		$tmp = $MODEL['item']->getActionsIds();
		foreach($tmp as $a)
			$ids[$a->objectId]++; 
		
		$ids = array_keys($ids);
		//vd($ids);
		if($ids)
		{
			$params=array(
					'isActive'=>true,
					'status'=>Status::code(Status::ACTIVE),
					'ids'=>$ids,
			);
			$MODEL['actions'] = Action::getList($params);
		}
		//vd($MODEL['actions']);
		
		
		$MODEL['presents'] = Present::getListByProductId($MODEL['item']->id, Status::code(Status::ACTIVE));
		$MODEL['present'] = array_pop(array_reverse($MODEL['presents']));
		if($MODEL['present'])
		{
			$MODEL['present']->initRelatedProducts();
			$MODEL['present']->productsState = $MODEL['present']->getProductsState();
		}
		
		$CONTENT->setTitle($MODEL['item']->name);
		
		$MODEL['adminMode'] = $_REQUEST['adminMode'] && Admin::isAdmin();
		$MODEL['isAdmin'] = Admin::isAdmin() && $ADMIN->hasRole(Role::COMMENT_MODERATOR);
		
		
		# 	рекомендуемые
		$params=array(
				'status'=>Status::code(Status::ACTIVE),
//				'isNew'=>true,
				'inStock'=>true,
				'orderBy' =>' RAND() ',
				'from'=>0,
				'count'=>4,
				'idsNotIn'=>array($MODEL['item']->id),
		);
		$MODEL['recommended'] = ProductSimple::getList($params);
		foreach($MODEL['recommended'] as $item)
			$item->initDiscountObject();
		
		
		# 	отзывы
		$MODEL['page'] = intval($_REQUEST['reviewsPage']) ? intval($_REQUEST['reviewsPage'])-1 : 0;
		$MODEL['elPP'] = $elPP;
		$MODEL['page'] = $page;
		$statuses = array(Status::code(Status::ACTIVE));
		$reviewsFrom = $MODEL['page'] * Review::EL_PP;
		$reviewsCount = Review::EL_PP;
		if($MODEL['isAdmin'] )
		{
			$statuses = array(
						Status::code(Status::ACTIVE),
						Status::code(Status::MODERATION),
					);
			$reviewsFrom = null;
			$reviewsCount = null;
		}
		
		$reviewsParams = array(
				'statuses'=>$statuses,
				'orderBy'=>' status DESC ',
				'from'=>$reviewsFrom,
				'count'=>$reviewsCount,
				'objectType'=>Object::code(Object::PRODUCT),
				'objectId'=>$MODEL['item']->id,
		);
		$MODEL['reviews'] = review::getList($reviewsParams);
		
		
		
		
		$MODEL['crumbs'][] = '<a href="'.Route::getByName(Route::MAIN)->url().'">Главная</a>';
		$MODEL['crumbs'][] = 'Каталог';
		$MODEL['crumbs'][] = '<a href="'.$MODEL['item']->cat->url().'">'.$MODEL['item']->cat->name.'</a> ';
		$MODEL['crumbs'][] = $MODEL['item']->name;
		

		
		foreach($_GLOBALS['menu'][32]->subs as $page)
			if(strpos($page->attrs['link'], $MODEL['item']->cat->url())!==false)
				$_GLOBALS['activeMenu'][$page->id] = true;
		
		
		Core::renderView('catalog/itemView.php', $MODEL);
	}
	
	
	
	
	
	
	
	
	function novinki()
	{
		require(GLOBAL_VARS_SCRIPT_FILE_PATH);
		Startup::execute(Startup::FRONTEND);
		$CONTENT->setTitle('Новинки');
	
		$_GLOBALS['activeMenu'][32] = true;
		$_GLOBALS['activeMenu'][39] = true;
	
		$MODEL['items'] = ProductSimple::getList(array(
					'isNew'=>true,
					'status'=>Status::code(Status::ACTIVE),
			));
		# 	инициализируем скидки для товара
		foreach($MODEL['items'] as $item)
			$item->initDiscountObject();
	
		$MODEL['crumbs'][] = '<a href="'.Route::getByName(Route::MAIN)->url().'">Главная</a>';
		$MODEL['crumbs'][] = 'Новинки';
	
		$MODEL['crumbs'] = $MODEL['crumbs'];
	
		
		Core::renderView('catalog/novinki.php', $MODEL);
	}






    function opt()
    {
        require(GLOBAL_VARS_SCRIPT_FILE_PATH);
        Startup::execute(Startup::FRONTEND);
        $CONTENT->setTitle('Оптовые прайсы');

        $_GLOBALS['activeMenu'][32] = true;
        $_GLOBALS['activeMenu'][53] = true;

        $MODEL['textBefore'] = Page::get(54);
        $MODEL['textAfter'] = Page::get(55);
        $MODEL['textTableTop'] = Page::get(59);
        $MODEL['catalog'] = CategorySimple::getList(array('status'=>Status::code(Status::ACTIVE)));
        foreach($MODEL['catalog'] as $cat)
        {
            $cat->products = ProductSimple::getList(array('status'=>Status::code(Status::ACTIVE), 'catId'=>$cat->id, 'orderBy'=>'idxOpt, name'));
            foreach($cat->products as $p)
                $p->initOptPrices();
        }

        $MODEL['crumbs'][] = '<a href="'.Route::getByName(Route::MAIN)->url().'">Главная</a>';
        $MODEL['crumbs'][] = 'Оптовые прайсы';

        $MODEL['crumbs'] = $MODEL['crumbs'];


        Core::renderView('catalog/opt.php', $MODEL);
    }




    function opt2()
    {
        require(GLOBAL_VARS_SCRIPT_FILE_PATH);
        Startup::execute(Startup::FRONTEND);
        $CONTENT->setTitle('Оптовые прайсы');

//        vd($CONTENT);

        $_GLOBALS['activeMenu'][32] = true;
        $_GLOBALS['activeMenu'][53] = true;

        $MODEL['textBefore'] = Page::get(54);
        $MODEL['textAfter'] = Page::get(55);
        $MODEL['textTableTop'] = Page::get(59);
        $MODEL['catalog'] = CategorySimple::getList(array('status'=>Status::code(Status::ACTIVE)));

        $optPrices = OptPrice::getList();
//        vd($optPrices);

        foreach($MODEL['catalog'] as $cat)
        {
            $cat->products = ProductSimple::getList(array('status'=>Status::code(Status::ACTIVE), 'catId'=>$cat->id, 'orderBy'=>'idxOpt, name'));
            foreach($cat->products as $p)
                $p->initOptPrices($optPrices);
        }

        $MODEL['crumbs'][] = '<a href="'.Route::getByName(Route::MAIN)->url().'">Главная</a>';
        $MODEL['crumbs'][] = 'Оптовые прайсы';

        $MODEL['crumbs'] = $MODEL['crumbs'];


        Core::renderView('catalog/opt2.php', $MODEL);
    }
	
	
	
	
	
}


?>