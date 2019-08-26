<?php 
class ActionsController extends MainController{
	
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
		$CONTENT->setTitle('Акции');
		
		$_GLOBALS['activeMenu'][33] = true;
		
		$MODEL['list'] = Action::getList(array(
				'statuses'=>array(Status::code(Status::ACTIVE)),
				'isActive'=>true,
			));
		
		foreach($MODEL['list'] as $c)
			$c->initRelatedProducts();
		
		
		# 	подарки
		$MODEL['presentsList'] = Present::getList(array(
				'status'=>Status::code(Status::ACTIVE)
		));
		foreach($MODEL['presentsList'] as $pr)
		{
			$pr->initRelatedProducts();
			$pr->productsState = $pr->getProductsState();
			
			foreach($pr->triggerProducts as $pr2)
				$pr2->product->initDiscountObject();
			foreach($pr->presentProducts as $pr2)
				$pr2->product->initDiscountObject();
			
			$pr->calculateTotalSums();
		}
			
			
		$MODEL['crumbs'][] = '<a href="'.Route::getByName(Route::MAIN)->url().'">Главная</a>';
		$MODEL['crumbs'][] = 'Акции';
		
		$MODEL['crumbs'] = $MODEL['crumbs'];
		
		Core::renderView('actions/list.php', $MODEL);
	}
	
	
	
	
	function item()
	{
		require(GLOBAL_VARS_SCRIPT_FILE_PATH);
		Startup::execute(Startup::FRONTEND);
		
		$_GLOBALS['activeMenu'][33] = true;
		
		$MODEL['item'] = Action::get(intval($CORE->params[0]));
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
				
				'objectType'=>Object::code(Object::ACTION),
				'objectId'=>$MODEL['item']->id,
		);
		$MODEL['reviews'] = review::getList($reviewsParams);
		
		
		
		
		$MODEL['crumbs'][] = '<a href="'.Route::getByName(Route::MAIN)->url().'">Главная</a>';
		$MODEL['crumbs'][] = '<a href="/actions">Акции</a>';
		//$MODEL['crumbs'][] = '<a href="'.$MODEL['item']->cat->url().'">'.$MODEL['item']->cat->name.'</a> ';
		$MODEL['crumbs'][] = $MODEL['item']->name;
		

		Core::renderView('actions/itemView.php', $MODEL);
	}
	
	
	
	
}


?>