<?php
class ReviewController extends MainController{
	
	function routifyAction()
	{
		global $CORE;
		$section = $CORE->params[0];
		$p = $CORE->params[1];
	
		if($section == 'list')
			$action='list1';

		if($action)
			$CORE->action = $action;
	}
	
	
	function index()
	{
		require(GLOBAL_VARS_SCRIPT_FILE_PATH);
		Startup::execute(Startup::ADMIN);
		//vd($_GLOBALS);
		
		if($ADMIN->hasRole(Role::COMMENT_MODERATOR) )
		{
			$params = array(
					'status' => Status::code(Status::MODERATION),
					'from' => 0,
					'count' => /*AdvItem::COMMENTS_PER_PAGE*/'',
					'orderBy' => '',
					'desc' =>'',
			);
			$MODEL['list'] = Review::getList($params);
			foreach($MODEL['comments'] as $c)
				$userIdsToTake[] = $c->userId;
				//vd($userIdsToTake);
			$users = User::getByIdsList($userIdsToTake);
			foreach($MODEL['list'] as $c)
				$c->user = $users[$c->userId];
		}
		else
			$MODEL['error'] = Error::NO_ACCESS_ERROR;
		
		Core::renderView('reviews/indexView.php', $MODEL);
	}
	
	
	
	
	
	function approve()
	{
		require(GLOBAL_VARS_SCRIPT_FILE_PATH);
		Startup::execute(Startup::ADMIN);
		$CORE->setLayout(null);
		
		
		//usleep(200000);
		//vd($_REQUEST);
		//vd($_SESSION['captcha_keystring']);
		$errors = null;
		
		if($ADMIN->hasRole(Role::COMMENT_MODERATOR) )
		{
			if($r = Review::get($_REQUEST['id']))
			{
				if($r->status->code == Status::MODERATION)
				{
					$r->status = Status::Code(Status::ACTIVE);
					$r->update();
					
					switch($r->objectType->code)
					{
						case Object::PRODUCT:
							$parent = ProductSimple::get($r->objectId);
							$parent->recountRating();
							break;
							
						case Object::COURSE:
							$parent = Course::get($r->objectId);
							$parent->recountRating();
							break;
						
						case Object::ACTION:
							$parent = Action::get($r->objectId);
							$parent->recountRating();
							break;
					}
				}
				else
					$errors[] = new Error('Ошибка! Отзыв не находится в модерации. ['.$_REQUEST['id'].']');
			}
			else 
				$errors[] = new Error('Ошибка! Отзыв не найден. ['.$_REQUEST['id'].']');
		}
		else
			$errors[] = new Error(Error::NO_ACCESS_ERROR);
		
		$res['errors'] = $errors;
		$res['status'] = $status->code;
		echo json_encode($res);
	}
	
	
	
	
	
	
	function delete()
	{
		require(GLOBAL_VARS_SCRIPT_FILE_PATH);
		Startup::execute(Startup::ADMIN);
		$CORE->setLayout(null);
	
		$errors = null;
	
		if($ADMIN->hasRole(Role::COMMENT_MODERATOR) )
		{
			if($r = Review::get($_REQUEST['id']))
			{
				$r->status = Status::Code(Status::DELETED);
				$r->update();
					
				switch($r->objectType->code)
				{
					case Object::PRODUCT:
						$parent = ProductSimple::get($r->objectId);
						$parent->recountRating();
						break;
						
					case Object::COURSE:
						$parent = Course::get($r->objectId);
						$parent->recountRating();
						break;
						
					case Object::ACTION:
						$parent = Action::get($r->objectId);
						$parent->recountRating();
						break;
				}
			}
			else
				$errors[] = new Error('Ошибка! Отзыв не найден. ['.$_REQUEST['id'].']');
		}
		else
			$errors[] = new Error(Error::NO_ACCESS_ERROR);

		$res['errors'] = $errors;
		echo json_encode($res);
	}
	
	
	
	
	
}




?>