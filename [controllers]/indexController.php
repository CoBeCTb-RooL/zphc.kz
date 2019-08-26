<?php
class IndexController extends MainController{
	
	function routifyAction()
	{
		require(GLOBAL_VARS_SCRIPT_FILE_PATH);

		$action = 'index';
		if($_SERVER['REQUEST_URI'] == '/' && $_CONFIG['ZAGLUSHKA_INDEX'])
			$action = 'zaglushka';
		
		$CORE->action = $action;
	}
	
	
	
	function index()
	{
		require(GLOBAL_VARS_SCRIPT_FILE_PATH);
		Startup::execute(Startup::FRONTEND);
		
		$CONTENT->setTitle('Главная') ;
		
		
		$MODEL['text'] = Page::get(48);
		
		
		# 	главная акция
		$params=array(
				'isInMainCounter'=>1,
				'isActive'=>true,
				'status'=>Status::code(Status::ACTIVE),
		);
		//echo '<hr />';
		$MODEL['mainAction'] = Action::getList($params);
		$MODEL['mainAction'] = array_pop(array_reverse($MODEL['mainAction'], true));
		
		# 	акция для крутилки ОДНА
		$params=array(
				'isInSlides'=>1,
				'isActive'=>true,
				'idsNotIn'=>array($MODEL['mainAction']->id),
				'status'=>Status::code(Status::ACTIVE),
		);
		$MODEL['sliderActions'] = Action::getList($params);
		$MODEL['sliderAction'] = array_pop(array_reverse($MODEL['sliderActions'], true));
		
		
		# 	курс для крутилки 
		$params=array(
				'isInSlides'=>1,
				'status'=>Status::code(Status::ACTIVE),
		);
		$MODEL['sliderCourses'] = Course::getList($params);
		$MODEL['sliderCourse'] = array_pop(array_reverse($MODEL['sliderCourses'], true));
		
		# 	новинки 	
		$params=array(
				'status'=>Status::code(Status::ACTIVE),
				'isNew'=>true,
				'inStock'=>true,
				'orderBy' =>' RAND() ',
				'from'=>0,
				'count'=>4,
		);
		$MODEL['novinki'] = ProductSimple::getList($params);
		//vd($MODEL['novinki']);
		
		
		# 	категории
		$MODEL['cats'] = CategorySimple::getList(array('status'=>Status::code(Status::ACTIVE)));
		
		
		# 	акции
		$MODEL['actions'] = Action::getList(array(
				'statuses'=>array(Status::code(Status::ACTIVE)),
				'isActive'=>true,
		));
		
		# 	курсы
		$MODEL['courses'] = Course::getList(array(
				'statuses'=>array(Status::code(Status::ACTIVE)),
				'from'=>0,
				'count'=>4,
				'orderBy'=>' RAND() ',
		));
		foreach($MODEL['courses'] as $c)
			$c->initRelatedProducts();
		
			
		# 	слайд ПОДАРКИ
		$MODEL['sliderPresents'] = Page::get(56);

		Core::renderView('index/index.php', $MODEL);
	}
	
	
	
	function zaglushka()
	{
		require(GLOBAL_VARS_SCRIPT_FILE_PATH);
		//Startup::execute(Startup::FRONTEND);
		
		$CORE->setLayout('zaglushka.php');
		
	}
	
}

?>