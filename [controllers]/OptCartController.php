<?php 
class OptCartController extends MainController{
	
	function routifyAction()
	{
		global $CORE;
		$section = $CORE->params[0];
		$p = $CORE->params[1];
		//vd($CORE->params);
		//vd($CORE->params[0]);
		

		if($action)
			$CORE->action = $action;
	}
	
	
	
	
	function index()
	{
		self::catsList();
	}
	
	
	function submit()
	{
		require(GLOBAL_VARS_SCRIPT_FILE_PATH);
        Startup::execute(Startup::FRONTEND);
        $CORE->setLayout(null);

        $errors = null;
        specialCharizeArray($_REQUEST);

        #   приводим айдишники к норм виду
        $a = [];
        foreach ($_REQUEST['products'] as $item)
            $a[$item['id']] = $item['quan'];
        $_REQUEST['products'] = $a;


        $cur = $_GLOBALS['currency'];

//        vd($_REQUEST);

        $deliveryType = DeliveryType::$items[$_REQUEST['deliveryType']];
        if(!$deliveryType)
            $errors[] = new Error('Пожалуйста, укажите <b>тип доставки</b>!', 'deliveryType');
//        vd($deliveryType);

        $paymentType = PaymentType::$items[$_REQUEST['paymentType']];
        if(!$paymentType)
            $errors[] = new Error('Пожалуйста, выберите <b>способ оплаты!</b>', 'paymentType');


        if(!$errors)
        {
            # 	формируем заказ
            $o = new Order();
            $o->orderStatus = OrderStatus::code(OrderStatus::NEW_ORDER);
            $o->getDataFromArray($_REQUEST);

            $o->deliveryType = $deliveryType;
            $o->paymentType = $paymentType;

            $errors = $o->validate();
            if(!$errors)
            {
                #   сохраняем заказ

            }
        }


//        vd($a);
//        vd($cur);



//        vd($_REQUEST);




//        vd($o);


        $arr['errors'] = $errors;
        echo json_encode($arr);
    }
	
	
	

	
	
	
}


?>