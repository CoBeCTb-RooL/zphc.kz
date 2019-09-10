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

                $optCart = new OptCart($_REQUEST['products']);
//
                $o->currency = $cur;
                $o->currencyCoef = $o->currency->coef;

                $o->totalSum = $optCart->info['totalSumPrimal'];
                $o->totalSumInCurrency = $optCart->info['totalSumPrimalInCurrency'];

                $o->totalDiscount = $optCart->info['totalSumPrimal'] - $optCart->info['totalSumFinal'];
                $o->totalDiscountInCurrency = $optCart->info['totalSumPrimalInCurrency'] - $optCart->info['totalSumFinalInCurrency'];

                //$CART->sumInCurrency['discountSum']

                $o->deliveryPrice = $optCart->info['totalSumFinal'] >= $_CONFIG['SETTINGS']['order_sum_for_free_delivery'] ? 0 : $_CONFIG['SETTINGS']['delivery_cost'] ;

                $o->totalQuan = $optCart->info['quan'];
                $o->userId = $USER->id;

                $o->param1 = json_encode($_REQUEST['products']);

                $o->orderType = OrderType::code(OrderType::OPT);

                ##########################
                $o->dateCreated = date('Y-m-d H:i:s');
                $o->id = $o->insert();
//                vd($o);
                foreach($optCart->orderItems as $oi)
                {
                    $oi->orderId = $o->id;
                    $oi->insert();
                }
            }
        }


        # 	отправляем письмо с заказом
        $o->initOrderItems();
        $o->initOrderItemProducts();
        $o->initReferer();

        $o->sortOrderItems();
        $o->initOrderCourses();
        $o->initOrderActions();
        $emails = array(
            $o->customerEmail,
            $_CONFIG['SETTINGS']['contactEmail'],
        );
        foreach($emails as $email)
        {
            $msg = $o->getEmailHTML();
            Funx::sendMail($email, 'robot@'.$_SERVER['HTTP_HOST'], 'Заказ №'.$o->id.' (интернет-магазин '.DOMAIN_CAPITAL.')', $msg.ReferalTail::info());
        }


        $arr['errors'] = $errors;
        echo json_encode($arr);
    }
	
	
	

	
	
	
}


?>