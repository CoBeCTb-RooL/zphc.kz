<?php 
class CartController extends MainController{
	
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
		$CONTENT->setTitle('Корзина');
		
	
		$MODEL['crumbs'][] = '<a href="'.Route::getByName(Route::MAIN)->url().'">Главная</a>';
		$MODEL['crumbs'][] = 'Корзина';
		$MODEL['crumbs'] = $MODEL['crumbs'];
		
		Core::renderView('cart/index.php', $MODEL);
	}
	
	
	
	
	function drawCartAjax()
	{
		require(GLOBAL_VARS_SCRIPT_FILE_PATH);
		Startup::execute(Startup::FRONTEND);
		$CORE->setLayout(null);
		
		
		# 	товары
		ob_start();
		Core::renderPartial('cart/productsTable.php', $MODEL);
		$cartHTML = ob_get_clean();
		
		
		# 	инфа о заказе
		ob_start();
		Core::renderPartial('cart/orderInfo.php', $MODEL);
		$orderInfoHTML = ob_get_clean();
		
		
		
		$result['orderInfoHTML'] = $orderInfoHTML; 
		$result['cartHTML'] = $cartHTML;
		$result['isCartEmpty'] = !$CART->ids;
		echo json_encode($result);
	}
	
	
	
	
	
	function addProduct()
	{
		require(GLOBAL_VARS_SCRIPT_FILE_PATH);
		Startup::execute(Startup::FRONTEND);
		$CORE->setLayout(null);
		
		$errors = null;
		
		ob_start();
		$item = ProductSimple::get($_REQUEST['id']);
		$quan = intval($_REQUEST['quan']);
		
		if($item)
		{
			if($quan)
			{
				if($item->stock)
				{
					if($quan > $item->stock)
					{
						$quan = $item->stock;
						$problem = 'Товар остался только в количестве '.$quan.' шт.';
					}
					$item->addToCart($quan);
					$msg = 'Товар добавлен в корзину!';
				}
				else 
					$problem = 'К сожалению, этого товара не осталось на складе.';
			}
			else
				$error = 'Введите корректное количество';
		}
		else
			$error = 'Ошибка! Товар не найден. ['.$_REQUEST['id'].']';
		
		
		//vd($_REQUEST);
		//vd($item->stock);
		
		$MODEL = array(
				'item'=>$item,
				'pic'=>$item->photo,
				'title'=>$item->name,
				'msg'=>$msg,
				'error'=>$error,
				'problem'=>$problem,
		);
		Core::renderPartial(SHARED_VIEWS_DIR.'/cart/addToCartResponse.php', $MODEL);
		//vd($item);
		
		$html = ob_get_clean();
		
		$arr['html'] = $html;
		$arr['errors'] = $errors;
		echo json_encode($arr);
	}
	
	
	
	
	
	
	
	
	function addActionProducts()
	{
		require(GLOBAL_VARS_SCRIPT_FILE_PATH);
		Startup::execute(Startup::FRONTEND);
		$CORE->setLayout(null);
	
		$errors = null;
	
		ob_start();
		
		//vd($_REQUEST);
		
		$item = Action::get($_REQUEST['id']);
		if($item)
		{
			$item->initRelatedProducts();
			$state = $item->getProductsState();
			//vd($state);
			if($state->code == ProductsState::OK)
			{
				$item->addToCart();
				$msg = 'Товары акции добавлены в корзину.';
			}
			else 
				$problem = 'К сожалению, одного из товаров акции сейчас нет в наличии..';
			//vd($action);
		}
		else
			$error = 'Ошибка! Акция не найдена. ['.$_REQUEST['id'].']';
		
		$MODEL = array(
				'item'=>$item,
				'pic'=>$item->photo,
				'title'=>$item->name,
				'msg'=>$msg,
				'error'=>$error,
				'problem'=>$problem,
		);
		Core::renderPartial(SHARED_VIEWS_DIR.'/cart/addToCartResponse.php', $MODEL);
		//vd($item);

		$html = ob_get_clean();

		$arr['html'] = $html;
		$arr['errors'] = $errors;
		echo json_encode($arr);
	}
	
	
	
	
	
	
	
	
	function addCourseProducts()
	{
		require(GLOBAL_VARS_SCRIPT_FILE_PATH);
		Startup::execute(Startup::FRONTEND);
		$CORE->setLayout(null);
	
		$errors = null;
	
		ob_start();
	
		//vd($_REQUEST);
	
		$item = Course::get($_REQUEST['id']);
		if($item)
		{
			$item->initRelatedProducts();
			$state = $item->getProductsState();
			//vd($state);
			if($state->code == ProductsState::OK || $_REQUEST['quan'] < 0 ) 
			{
				$item->addToCart($_REQUEST['quan']);
				$msg = 'Товары курса добавлены в корзину.';
			}
			else
			{
				$problem = 'К сожалению, некоторых товаров курса сейчас нет в наличии в необходимом количестве..';
				$errors[] = new Error('К сожалению, некоторых товаров курса сейчас нет в наличии в необходимом количестве.. Курс не добавлен.');
			}
				//vd($action);
		}
		else
			$error = 'Ошибка! Курс не найден. ['.$_REQUEST['id'].']';
	
		$MODEL = array(
				'item'=>$item,
				'pic'=>$item->photo,
				'title'=>$item->name,
				'msg'=>$msg,
				'error'=>$error,
				'problem'=>$problem,
		);
		Core::renderPartial(SHARED_VIEWS_DIR.'/cart/addToCartResponse.php', $MODEL);
		//vd($item);

		$html = ob_get_clean();

		$arr['html'] = $html;
		$arr['errors'] = $errors;
		echo json_encode($arr);
	}
	
	
	
	
	
	
	function addPresentProducts()
	{
		require(GLOBAL_VARS_SCRIPT_FILE_PATH);
		Startup::execute(Startup::FRONTEND);
		$CORE->setLayout(null);
	
		$errors = null;
	
		ob_start();
	
		//vd($_REQUEST);
	
		$item = Present::get($_REQUEST['id']);
		if($item)
		{
			$item->initRelatedProducts();
			$state = $item->getProductsState();
			//vd($state);
			if($state->code == ProductsState::OK || $_REQUEST['quan'] < 0 )
			{
				$item->addToCart($_REQUEST['quan']);
				$msg = 'Товары добавлены в корзину.';
			}
			else
			{
				$problem = 'К сожалению, некоторых товаров сейчас нет в наличии в необходимом количестве..';
				$errors[] = new Error('К сожалению, некоторых товаров сейчас нет в наличии в необходимом количестве.. Товары не добавлены.');
			}
			//vd($action);
		}
		else
			$error = 'Ошибка! Свазка товаров не найдена. ['.$_REQUEST['id'].']';
	
		$MODEL = array(
				'item'=>$item,
				//'pic'=>$item->photo,
				'title'=>$item->name,
				'msg'=>$msg,
				'error'=>$error,
				'problem'=>$problem,
		);
		Core::renderPartial(SHARED_VIEWS_DIR.'/cart/addToCartResponse.php', $MODEL);
		//vd($item);

		$html = ob_get_clean();

		$arr['html'] = $html;
		$arr['errors'] = $errors;
		echo json_encode($arr);
	}
	
	
	
	
	
	
	
	function addQuan()
	{
		require(GLOBAL_VARS_SCRIPT_FILE_PATH);
		Startup::execute(Startup::FRONTEND);
		$CORE->setLayout(null);
		
		$errors = null;
		
		//vd($_REQUEST);
		$quan = intval($_REQUEST['quan']);
		//vd($quan);
		$product = ProductSimple::get($_REQUEST['id']);
		if($product)
		{
			if($quan!=0)
			{
				$product->addToCart($quan);
			}
		}
		else 
			$errors[] = new Error('Ошибка! Товар не найден. ['.$_REQUEST['id'].']');
		
			//$errors[] = new Error('Ошибка! Товар не найден. ['.$_REQUEST['id'].']');
		
		$arr['html'] = $html;
		$arr['errors'] = $errors;
		echo json_encode($arr);
	}
	
	
	
	
	
	
	
	function switchPaymentType()
	{
		require(GLOBAL_VARS_SCRIPT_FILE_PATH);
		Startup::execute(Startup::FRONTEND);
		$CORE->setLayout(null);
		
		$errors = null;
		
		//vd($_REQUEST);
		$type = PaymentType::code($_REQUEST['type']);
		if($type)
		{
			Cart::setPaymentType($type);
		}
		else 
			$errors[] = new Error('Ошибка типа оплаты. ['.$_REQUEST['type'].']');
		
		$arr['paymentType'] = $type;
		$arr['errors'] = $errors;
		echo json_encode($arr);
	}
	
	
	
	
	
	function switchDeliveryType()
	{
		require(GLOBAL_VARS_SCRIPT_FILE_PATH);
		Startup::execute(Startup::FRONTEND);
		$CORE->setLayout(null);
	
		$errors = null;
	
		//vd($_REQUEST);
		$type = DeliveryType::code($_REQUEST['type']);
		if($type)
		{
			Cart::setDeliveryType($type);
		}
		else
			$errors[] = new Error('Ошибка типа доставки. ['.$_REQUEST['type'].']');
	
		$arr['html'] = $html;
		$arr['errors'] = $errors;
		echo json_encode($arr);
	}
	
	
	
	
	
	function refreshCartInfo()
	{
		require(GLOBAL_VARS_SCRIPT_FILE_PATH);
		Startup::execute(Startup::FRONTEND);
		$CORE->setLayout(null);
		
		$errors = null;
		
		ob_start();
		Core::renderPartial(SHARED_VIEWS_DIR.'/cart/topCartInfoDesktop.php', $arr = array('cart'=>$CART));
		$htmlDesktop = ob_get_clean();
		
		ob_start();
		Core::renderPartial(SHARED_VIEWS_DIR.'/cart/topCartInfoMobile.php', $arr = array('cart'=>$CART));
		$htmlMobile = ob_get_clean();
		
		$arr['htmlDesktop'] = $htmlDesktop;
		$arr['htmlMobile'] = $htmlMobile;
		$arr['totalQuan'] = $CART->totalQuan;
		$arr['errors'] = $errors;
		echo json_encode($arr);
	}
	
	
	
	
	
	function sendOrder()
	{
		require(GLOBAL_VARS_SCRIPT_FILE_PATH);
		Startup::execute(Startup::FRONTEND);
		$CORE->setLayout(null);
	
		$errors = null;
	
		//ob_start();
		//vd($_REQUEST);
		
		specialCharizeArray($_REQUEST);
		
		# 	формируем заказ
		$o = new Order();
		$o->orderStatus = OrderStatus::code(OrderStatus::NEW_ORDER);
		
		$o->getDataFromArray($_REQUEST);
		
		$o->getDataFromCart($CART);
		
		# 	ищем реферера
		if($o->refererPhone)
		{
			if($u = User::getByPhone(User::cleanPhone($o->refererPhone)))
				$o->refererId = $u->id;
		}
		
		$errors = $o->validate();
		
		
		# 	сохраняем заказ и его товары
		if(!$errors)
		{
			$o->id = $o->insert();
			$o->dateCreated = date('Y-m-d H:i:s');
		
			foreach($CART->orderItems as $oi)
			{
				$oi->orderId = $o->id;
				$oi->insert();
			}
		}
		
		
		//vd($CART->payedByBonus);
		if($USER && $CART->payedByBonus)
			$USER->setBonusBlocked($CART->payedByBonus);
		
		
		
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
		//vd($emails);
		
		Cart::clean();
			
		
		$arr['html'] = $html;
		$arr['errors'] = $errors;
		echo json_encode($arr);
	}
	
	
	
	function payByBonus()
	{
		require(GLOBAL_VARS_SCRIPT_FILE_PATH);
		Startup::execute(Startup::FRONTEND);
		$CORE->setLayout(null);
		
		$errors = null;
		$msg = '';
		
		$sumInCurrency = Funx::cleanSumStr($_REQUEST['sum']);

		if(($sumInCurrency && is_float($sumInCurrency)) || !$_REQUEST['sum'])
		{
			$coef = 1/$_GLOBALS['currency']->coef;
			
			if($sumInCurrency > $USER->bonusAvailableInCurrency)
			{
				$sumInCurrency = $USER->bonusAvailableInCurrency;
				$msg = 'Принято (указанная сумма превышала сумму ваших бонусов.)';
			}
			else
				$msg='Принято';
		}
		else 
			$errors[] = new Error('<span >Укажите корректную сумму!</span>', 'payByBonus');
		
		$sumInBucks = Currency::calculateByCoef($sumInCurrency, $coef);
		# 	фиксируем в сессии
		Cart::setPayedByBonus($sumInBucks);
		
		
		//$arr['sum'] = $sumInCurrency;
		$arr['sum'] = Currency::formatPrice($sumInCurrency, $_GLOBALS['currency'], true);
		$arr['sumStr'] = Currency::formatPrice($sumInCurrency, $_GLOBALS['currency']);
		//vd($arr['sum']);
		$arr['msg'] = $msg;
		$arr['errors'] = $errors;
		echo json_encode($arr);
	}
	
	
	
	
	
	function orderMail()
	{
		require(GLOBAL_VARS_SCRIPT_FILE_PATH);
		Startup::execute(Startup::FRONTEND);
		$CORE->setLayout(null);
		
		$MODEL['item'] = Order::get($_REQUEST['orderId']);
		if($MODEL['item'])
		{	
			$MODEL['item']->initOrderItems();
			$MODEL['item']->initOrderItemProducts();
			$MODEL['item']->initReferer();
			
			$MODEL['item']->sortOrderItems();
			$MODEL['item']->initOrderCourses();
			$MODEL['item']->initOrderActions();
		}
		
		//vd($MODEL);
		
		Core::renderView('orders/orderViewPartial.php', $MODEL);
	}
	
	
	
	
	
	
	function switchCurrency()
	{
		require(GLOBAL_VARS_SCRIPT_FILE_PATH);
		Startup::execute(Startup::FRONTEND);
		$CORE->setLayout(null);
	
		$errors = null;
	
		ob_start();
		Core::renderPartial('cart/bonusInfoPartial.php');
		$html = ob_get_clean();
		
		$arr['currency'] = $_GLOBALS['currency'];
		
		$arr['cartBonusHTML'] = $html;
		$arr['errors'] = $errors;
		echo json_encode($arr);
	}
	
	
	
	
}


?>