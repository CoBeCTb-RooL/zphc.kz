<?php
class EmailViewController extends MainController{
	
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
        $CORE->setLayout(null);

        $type=$_REQUEST['type'];
        $id=$_REQUEST['id'];

        $email = '';




        switch ($type)
        {
            case 'order':
                $o = Order::get($id);
                $email = $o->customerEmail;
                $email = 'tsop.tya@gmail.com';

                $subject = 'Заказ №'.$o->id.' (интернет-магазин 22222 '.DOMAIN_CAPITAL.')';
                # 	отправляем письмо с заказом
                $o->initOrderItems();
                $o->initOrderItemProducts();
                $o->initReferer();

                $o->sortOrderItems();
                $o->initOrderCourses();
                $o->initOrderActions();

                $msg = $o->getEmailHTML();

                break;



            case 'userActivate':
                $u = User::get($id);
//                vd($u);
                $email = 'tsop.tya@gmail.com';
                $subject = 'Активация учётной записи '.DOMAIN_CAPITAL.'';
//                $subject = 'bla blaa bb';
//                $subject = 'bla bla шесть ';
//                $subject = 'Заказ №410 (интернет-магазин 3333 ZPHC.KZ)';
                $msg = Mail::getMsgForRegistration([
                    'name'=>$u->name.' '.$u->fathername,
                    'url'=>'http://'.$_SERVER['HTTP_HOST'].'/cabinet/profile/activate/'.$u->salt,
                ]);
//                $msg = 'blaaaaaa bla3';
                break;

            default:
                echo 'type not recognized';
                break;
        }


        /////////////


        echo '
        <html lang="ru">
          <head>
            <meta charset="utf-8">
            
        <form onsubmit="return confirm(\'Отправить?\'); ">
            <input type="hidden" name="type" value="'.$_REQUEST['type'].'" />
            <input type="hidden" name="id" value="'.$_REQUEST['id'].'" />
            <input type="hidden" name="send" value="1" />
            email: <input type="text" name="email" value="'.$email.'" />
            <input type="submit" value="отправить">
        </form>
        <hr>';


        if($_REQUEST['send'])
        {
            Funx::sendMail($email, 'robot@'.$_SERVER['HTTP_HOST'], $subject, $msg.ReferalTail::info());

            echo 'Письмо отправлено!';
            echo '<hr>';
        }


        echo $msg;




	}
	
	

	
	
	
	
	
}




?>