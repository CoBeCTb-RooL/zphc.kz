<?php
class ReviewController extends MainController{
	
	const ITEMS_PER_PAGE = 10;
	
	
	function routifyAction()
	{
		global $CORE;
		$section = $CORE->params[0];
		$p = $CORE->params[1];
	
		if(!$section || strpos($section, 'p_')===0)
			$action='index';
		
		if($action)
			$CORE->action = $action;
	}
	
	
	
	
	
	function add()
	{
		require(GLOBAL_VARS_SCRIPT_FILE_PATH);
		//Startup::execute(Startup::FRONTEND);
		
		$CORE->setLayout(null);
		
		$errors = null;
		specialCharizeArray($_REQUEST);
		
		$r = new Review();
		$r->name = $_REQUEST['name'];
		$r->email = $_REQUEST['email'];
		$r->text = $_REQUEST['text'];
		$r->rate = $_REQUEST['rate'];
		$r->objectType = Object::code($_REQUEST['objectType']);
		$r->objectId = intval($_REQUEST['objectId']);
		$r->status = Status::code(Status::MODERATION);
		
		$errors = $r->validate();
		//vd($errors);
		/*
		if($_REQUEST['captcha'] != $_SESSION['captcha_keystring'])
			$errors[] = Slonne::setError('captcha', 'Вы ввели неправильный <b>код подтверждения</b>');
		*/
			
		if(!$errors)
		{
			if($_SESSION['user']['id'])
			{
				$u = User::get($_SESSION['user']['id'], Status::code(Status::ACTIVE));
				if($u)
					$r->userId = $u->id;
			}
			$r->insert();
		}
		
		$arr['errors'] = $errors;
		echo json_encode($arr);
	}
	
	
	
	
	
	function editForm()
	{
		require(GLOBAL_VARS_SCRIPT_FILE_PATH);
		//Startup::execute(Startup::FRONTEND);
		
		$CORE->setLayout(null);
		
		if(Admin::isAdmin())
		{
			$MODEL['item'] = Suggestion::get($_REQUEST['id']);
		}
		else 
			$MODEL['error'] = 'Нет прав';
		
		
		Core::renderView('suggestions/edit.php', $MODEL);
	}
	
	
	
	
	
	
	function update()
	{
		require(GLOBAL_VARS_SCRIPT_FILE_PATH);
		//Startup::execute(Startup::FRONTEND);
	
		$CORE->setLayout(null);
	
		$errors = null;
		if(Admin::isAdmin() )
		{
			if( $s = Suggestion::get($_REQUEST['id']) )
			{
				specialCharizeArray($_REQUEST);
				
				$s->name = $_REQUEST['name'];
				$s->email = $_REQUEST['email'];
				$s->phone = $_REQUEST['phone'];
				$s->text = $_REQUEST['text'];
				$s->answer = $_REQUEST['answer'];
			
				$errors = $s->validate();
				
				if(!$errors)
					$s->update();
			}		
		}
		else
			$errors[] = Slonne::setError('', 'Нет прав.');

		$arr['errors'] = $errors;
		echo json_encode($arr);
	}
	
	
	
	
	function setStatus()
	{
		require(GLOBAL_VARS_SCRIPT_FILE_PATH);
		//Startup::execute(Startup::FRONTEND);
	
		$CORE->setLayout(null);
	
		$errors = null;
		if(Admin::isAdmin() )
		{
			if( $s = Suggestion::get($_REQUEST['id']) )
			{
				if($status = Status::code($_REQUEST['status']) )
				{
					$s->status = $status;
					$s->update();
				}
				else
					$errors[] = Slonne::setError('', 'Ошибка статуса. ['.$_REQUEST['status'].']');
			}
			else
				$errors[] = Slonne::setError('', 'Ошибка! Объект не найден.');
		}
		else
			$errors[] = Slonne::setError('', 'Нет прав.');
		
	
		$arr['errors'] = $errors;
		echo json_encode($arr);
	}
	
	
	
	
}




?>