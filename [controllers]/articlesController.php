<?php 
class ArticlesController extends MainController{
	
	function routifyAction()
	{
		global $CORE;
		$section = $CORE->params[0];
		$p = $CORE->params[1];
		//vd($CORE->params);
	
		if($id = intval($CORE->params[0]))
			$action = 'view';
		else
			$action = 'list1';

		if($action)
			$CORE->action = $action;
	}
	
	
	
	
	function list1()
	{
		require(GLOBAL_VARS_SCRIPT_FILE_PATH);
		Startup::execute(Startup::FRONTEND);
		
		$_GLOBALS['activeMenu'][34] = true;
		foreach($_GLOBALS['menu'][34]->subs as $page)
			if(strpos($page->attrs['link'], '/articles')!==false)
				$_GLOBALS['activeMenu'][$page->id] = true;
		
		$CONTENT->setTitle('Статьи');
		
		
		$MODEL['elPP'] = 5;
		$MODEL['page'] = intval($CORE->specialParams['p']) ? intval($CORE->specialParams['p'])-1 : 0;
		
		$limit=" LIMIT ".$MODEL['page']*$MODEL['elPP'].", ".$MODEL['elPP']."";
		$all = Article::getChildren(0);
		$MODEL['totalElements'] = count($all);
		
		$MODEL['list'] = Article::getChildren(null, $limit, $clause);
		 
		
		$MODEL['crumbs'][] = '<a href="'.Route::getByName(Route::MAIN)->url().'">Главная</a>';
		$MODEL['crumbs'][] = 'Статьи';
		
		$MODEL['crumbs'] = $MODEL['crumbs'];
		
		Core::renderView('articles/list.php', $MODEL);
	}
	
	
	
	
	function view()
	{
		require(GLOBAL_VARS_SCRIPT_FILE_PATH);
		Startup::execute(Startup::FRONTEND);
		
		$_GLOBALS['activeMenu'][34] = true;
		foreach($_GLOBALS['menu'][34]->subs as $page)
			if(strpos($page->attrs['link'], '/articles')!==false)
				$_GLOBALS['activeMenu'][$page->id] = true;
			
		$MODEL['item'] = Article::get(intval($CORE->params[0]));
		
		$CONTENT->setTitle('Статьи'.$_CONFIG['SETTINGS']['title_separator'].$MODEL['item']->attrs['name']);
		
		$MODEL['crumbs'][] = '<a href="'.Route::getByName(Route::MAIN)->url().'">Главная</a>';
		$MODEL['crumbs'][] = '<a href="/articles">Статьи</a>';
		$MODEL['crumbs'][] = $MODEL['item']->attrs['name'];
		

		Core::renderView('articles/view.php', $MODEL);
	}
	
	
	
	
}


?>