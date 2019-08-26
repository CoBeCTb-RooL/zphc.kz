<?php
class UserController extends MainController{
	
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
		
		Core::renderView('users/indexView.php', $model);
	}
	
	
	
	
	function list1()
	{
		require(GLOBAL_VARS_SCRIPT_FILE_PATH);
		Startup::execute(Startup::ADMIN);
		$CORE->setLayout(null);
	
		if($ADMIN->hasRole(Role::USERS_MODERATOR) )
		{
			$p = $_REQUEST['p'] ? $_REQUEST['p'] : 1 ;
			$elPP = 10;
			
			$from = ($p-1)*$elPP;
			$status = Status::num($_REQUEST['status']);
			
			$MODEL['email'] = trim($_REQUEST['email']);
			$MODEL['orderBy'] = $_REQUEST['orderBy'] ? $_REQUEST['orderBy'] : 'id';
			$MODEL['desc'] = $_REQUEST['desc'];
			//$status = Status::code(Status::ACTIVE);
			
			$params = array(
							'status' => $status,
							'email'=>$MODEL['email'],
							'from' => $from,
							'count' => $elPP,
							'orderBy' => $MODEL['orderBy'],
							'desc' =>$MODEL['desc'],
						);
			//vd($params);
			$users = User::getList($params);
			
			$MODEL['users'] = $users;
			$MODEL['totalCount'] = User::getCount($params); 
			$MODEL['p'] = $p;
			$MODEL['elPP'] = $elPP;
			$MODEL['orderBy'] = $_REQUEST['orderBy'] ? $_REQUEST['orderBy'] : 'id';
			$MODEL['desc'] = $_REQUEST['desc'];
			$MODEL['status'] = $status;
		}
		else 
			$MODEL['error'] = Error::NO_ACCESS_ERROR;
		
		//vd($cities);
		//vd($model);
	
		Core::renderView('users/list.php', $MODEL);
	}
	
	
	
	
	function view()
	{
		require(GLOBAL_VARS_SCRIPT_FILE_PATH);
		Startup::execute(Startup::ADMIN);
		$CORE->setLayout(null);
	
		if($ADMIN->hasRole(Role::USERS_MODERATOR) )
		{
			$MODEL['user'] = User::get($_REQUEST['id']);
			if($MODEL['user'])
			{
				$params = array(
						'userId'=>$MODEL['user']->id,
						'orderBy'=>'id ',
						'desc'=>true,
				);
				$MODEL['user']->bonusTransactions = Bonus::getList($params);
			}
		}
		else 
			$MODEL['error'] = Error::NO_ACCESS_ERROR;
		
		
		Core::renderView('users/view.php', $MODEL);
	}
	
	
	
	
	function setStatus()
	{
		require(GLOBAL_VARS_SCRIPT_FILE_PATH);
		Startup::execute(Startup::ADMIN);
		
		$CORE->setLayout(null);
	
		$errors = null;
		
		if(Admin::isAdmin())
		{
			$user = User::get($_REQUEST['userId']);
			if($user)
			{
				$status = Status::code($_REQUEST['status']);
				if($status)
				{
					$prevStatus = $user->status->code;
					if($prevStatus!=$status->code)
					{
						$user->setStatus($status);
						
						$je = new JournalEntry();
						$je->objectType = Object::code(Object::USER);
						$je->objectId = $user->id;
						$je->journalEntryType = JournalEntryType::code(JournalEntryType::USER_SET_STATUS);
						$je->comment = 'Смена статуса с "'.$prevStatus.'" на "'.$status->code.'"';
						$je->adminId = $ADMIN->id;
							
						$je->insert();
					}
				}
				else
					$errors[] = Slonne::setError('qqq', 'Ошибка! Левый статус. ['.$_REQUEST['status'].']');
			}
			else
				$errors[] = Slonne::setError('qqq', 'Ошибка! Комментарий не найден.');
		}
		else
			$errors[] = Slonne::setError('qqq', 'Ошибка! Нет прав.');
		
		$res['errors'] = $errors;
		$res['status'] = $status->code;
		echo json_encode($res);
	}



	function emailAuthApproveView()
    {
        require(GLOBAL_VARS_SCRIPT_FILE_PATH);
        Startup::execute(Startup::ADMIN);

        $CORE->setLayout(null);

        $user = User::get($_REQUEST['userId']);
        $m = new Mail();
        $m->to = $user->email;
        $m->from = ROBOT_EMAIL;
        $m->subject = 'Активация учётной записи на сайте '.DOMAIN_CAPITAL;
        $arr = array(
            'name'=>$u->name.' '.$u->fathername,
            'url'=>'http://'.$_SERVER['HTTP_HOST'].'/cabinet/profile/activate/'.$user->salt,
        );
        $m->msg = Mail::getMsgForRegistration($arr);
//        $m->msg = '123333';
//        vd($_SERVER['REQUEST_URI']);
        echo '
        <a href="'.$_SERVER['REQUEST_URI'].'&go=1" onclick="if(!confirm(\'отправить?\')){return false;} ">отправить</a>
        <hr>';
        vd($m);


        if($_REQUEST['go'])
        {
//            vd($m->send());
            $headers  = "Content-type: text/html; charset=utf-8 \r\n";
            $headers .= "From: <".$m->from.">\r\n";

            mail($m->to, $m->subject, $m->msg, $headers);
        }
//        echo $m->msg;


//        $m->send();
//        vd($user);
    }
	
	
	
	
}




?>