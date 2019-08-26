<?php 
class CoursesController extends MainController{
	
	function routifyAction()
	{
		global $CORE;
		$section = $CORE->params[0];
		$p = $CORE->params[1];
		//vd($CORE->params);
		//vd($CORE->params[0]);
		
		if(intval($CORE->params[0]))
			$action = 'item';
		
		if($action)
			$CORE->action = $action;
	}
	
	
	
	
	function index()
	{
		self::list1();
	}
	
	
	function list1()
	{
		require(GLOBAL_VARS_SCRIPT_FILE_PATH);
		Startup::execute(Startup::FRONTEND);
		$CONTENT->setTitle('Курсы');
		
		$_GLOBALS['activeMenu'][32] = true;
		foreach($_GLOBALS['menu'][32]->subs as $page)
			if(strpos($page->attrs['link'], '/courses')!==false)
				$_GLOBALS['activeMenu'][$page->id] = true;
		
		$MODEL['list'] = Course::getList(array(
					'statuses'=>array(Status::code(Status::ACTIVE)),
					'orderBy'=>'idx'
		));
		foreach($MODEL['list'] as $c)
		{
			$c->initRelatedProducts();
			$c->productsState = $c->getProductsState();
		}
		
		
		$MODEL['crumbs'][] = '<a href="'.Route::getByName(Route::MAIN)->url().'">Главная</a>';
		$MODEL['crumbs'][] = 'Курсы';
		
		$MODEL['crumbs'] = $MODEL['crumbs'];
		
		Core::renderView('courses/list.php', $MODEL);
	}
	
	
	
	
	function item()
	{
		require(GLOBAL_VARS_SCRIPT_FILE_PATH);
		Startup::execute(Startup::FRONTEND);
		
		$_GLOBALS['activeMenu'][32] = true;
		foreach($_GLOBALS['menu'][32]->subs as $page)
			if(strpos($page->attrs['link'], '/courses')!==false)
				$_GLOBALS['activeMenu'][$page->id] = true;
			
		$MODEL['item'] = Course::get(intval($CORE->params[0]));
		$MODEL['item']->initRelatedProducts();
		$MODEL['item']->productsState = $MODEL['item']->getProductsState();
		
		
		$CONTENT->setTitle($MODEL['item']->name);
		
		$MODEL['adminMode'] = $_REQUEST['adminMode'] && Admin::isAdmin();
		$MODEL['isAdmin'] = Admin::isAdmin() && $ADMIN->hasRole(Role::COMMENT_MODERATOR);
		
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
				
				'objectType'=>Object::code(Object::COURSE),
				'objectId'=>$MODEL['item']->id,
		);
		$MODEL['reviews'] = review::getList($reviewsParams);
		
		
		
		
		$MODEL['crumbs'][] = '<a href="'.Route::getByName(Route::MAIN)->url().'">Главная</a>';
		$MODEL['crumbs'][] = '<a href="/courses">Курсы</a>';
		//$MODEL['crumbs'][] = '<a href="'.$MODEL['item']->cat->url().'">'.$MODEL['item']->cat->name.'</a> ';
		$MODEL['crumbs'][] = $MODEL['item']->name;
		

		Core::renderView('courses/itemView.php', $MODEL);
	}
	
	
	
	
}


?>