<?php 
class ContactsController extends MainController{
	
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
		require(GLOBAL_VARS_SCRIPT_FILE_PATH);
		Startup::execute(Startup::FRONTEND);
		$CONTENT->setTitle('Контакты');
		
		$_GLOBALS['activeMenu'][36] = true;
		
		$MODEL['text'] = Page::get(36);
		
		//vd($MODEL['text']);
	
		$MODEL['crumbs'][] = '<a href="'.Route::getByName(Route::MAIN)->url().'">Главная</a>';
		$MODEL['crumbs'][] = 'Контакты';
		$MODEL['crumbs'] = $MODEL['crumbs'];
		
		Core::renderView('contacts/index.php', $MODEL);
	}
	
	
	
	
	function sendMsg()
	{
		require(GLOBAL_VARS_SCRIPT_FILE_PATH);
		Startup::execute(Startup::FRONTEND);
		$CORE->setLayout(null);
		
		
		$errors = null;
		//vd($_REQUEST);
		$name = trim($_REQUEST['name']);
		$email = trim($_REQUEST['email']);
		$subject = trim($_REQUEST['subject']);
		$msg = trim($_REQUEST['msg']);
		
		
		if(!$name)
			$errors[] = new Error('Укажите Ваше <b>имя</b>', 'name');
		if(!$email)
			$errors[] = new Error('Укажите Ваш <b>e-mail</b>', 'email');
		if(!$subject)
			$errors[] = new Error('Укажите <b>тему</b>', 'subject');
		if(!$msg)
			$errors[] = new Error('Введите <b>сообщение</b>', 'msg');
		
		if(!$errors)
		{
			$mailSubject = 'Сообщение с сайта '.$_SERVER['HTTP_HOST'];
			
			#	сообщение
			$mailHtml.='
			<h3>Сообщение с сайта '.$_SERVER['HTTP_HOST'].'</h3>
			<div>Имя: <b>'.$name.'</b></div>
			<div>E-mail: <b>'.$email.'</b></div>
			<div>По вопросу: <b>'.$subject.'</b></div>
			<div>Сообщение: <b>'.$msg.'</b></div>
			';
			
			//$emails = $_CONFIG['DEFAULT_DELIVERY_EMAILS'];
			$emails = array(
					$_CONFIG['SETTINGS']['contactEmail'],
					'tsop.tya@gmail.com',
			);
			foreach($emails as $key=>$eml)
				Funx::sendMail($eml, 'robot@'.$_SERVER['HTTP_HOST'], $mailSubject, $mailHtml.ReferalTail::info());
			//self::sendEmails($emails, $mailSubject, $mailHtml);
		}
		
		
		$result['errors'] = $errors; 
		echo json_encode($result);
	}
	
	
	
	
	
	
	
}


?>