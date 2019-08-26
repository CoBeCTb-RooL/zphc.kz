<?php 
class TestController extends MainController{
	
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
		$MODEL['crumbs'][] = '<a href="'.Route::getByName(Route::MAIN)->url().'">Главная</a>';
		$MODEL['crumbs'][] = 'Акции';
		
		$MODEL['crumbs'] = $MODEL['crumbs'];
		Core::renderView('test/index.php', $MODEL);
	}
	
	
	
}


?>