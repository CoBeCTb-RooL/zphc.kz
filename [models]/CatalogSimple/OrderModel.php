<?php
class Order
{
	const TBL = 'catalog_simple__orders';
	
	const ADMIN_ELEMENTS_PER_PAGE = 10;
	
	
	public    $id
			, $orderStatus
			, $dateCreated
			, $dateClosed
			
			, $customerName
			, $customerEmail
			, $customerPhone
			, $customerAddress
			, $customerIndex
			, $customerComment
			
			, $currency
			, $currencyCoef
			
			, $totalSum
			, $totalSumInCurrency
			, $totalDiscount
			, $totalDiscountInCurrency
			, $totalQuan 
			
			, $payedByBonus
			, $payedByInCurrency
			, $paymentType
			, $deliveryType
			, $deliveryPrice
			
			, $userId
			, $user
			
			, $managerComment
			
			, $refererPhone
			, $refererId
			, $referer
			
			, $param1
			, $param2
			
			, $orderItems
			
		;
	
		
	static $fields;
	
	
	function init($arr)
	{
		if($arr)
		{
			$u = new self;
			
			
			$u->id = $arr['id'];
			$u->orderStatus = OrderStatus::code($arr['orderStatus']);
			$u->dateCreated = $arr['dateCreated'];
			$u->dateClosed = $arr['dateClosed'];
			
			$u->customerName = $arr['customerName'];
			$u->customerEmail = $arr['customerEmail'];
			$u->customerPhone = $arr['customerPhone'];
			$u->customerAddress = $arr['customerAddress'];
			$u->customerIndex = $arr['customerIndex'];
			$u->customerComment = $arr['customerComment'];
			
			$u->currency = Currency::code($arr['currency']);
			$u->currencyCoef = $arr['currencyCoef'];
			$u->currency->coef = $u->currencyCoef ; 
			
			$u->totalSum = $arr['totalSum'];
			$u->totalSumInCurrency = $arr['totalSumInCurrency'];
			$u->totalDiscount = $arr['totalDiscount'];
			$u->totalDiscountInCurrency = $arr['totalDiscountInCurrency'];
			
			$u->totalQuan = $arr['totalQuan'];
			$u->payedByBonus = $arr['payedByBonus'];
			
			$u->paymentType = PaymentType::code($arr['paymentType']);
			$u->deliveryType = DeliveryType::code($arr['deliveryType']);
			$u->deliveryPrice = $arr['deliveryPrice'];
			
			$u->userId = $arr['userId'];
			$u->managerComment = $arr['managerComment'];
			
			$u->refererPhone = $arr['refererPhone'];
			$u->refererId = $arr['refererId'];
			
			$u->param1 = $arr['param1'];
			$u->param2 = $arr['param2'];
			
			return $u;
		}
	}
	
	
	
	
	function get($id, $status)
	{
		if($id = intval($id))
		{
			$sql="SELECT * FROM `".strPrepare(self::TBL)."` WHERE id=".$id." ".($status ? " AND status=".intval($status->num)." " : "")."";
			$qr=DB::query($sql);
			echo mysql_error();
			if($attrs = mysql_fetch_array($qr, MYSQL_ASSOC))
				$item = self::init($attrs);
			
			return $item;
		}
	}
	
	
	
	
	function getList($params)
	{
		$sql="SELECT * FROM `".strPrepare(self::TBL)."` WHERE 1 ".self::getListInnerSql($params);
		
		if($params['orderBy'])
			$sql.=" ORDER BY ".(mysql_real_escape_string($params['orderBy']) ? mysql_real_escape_string($params['orderBy']) : ' idx ')." ".($params['desc'] ? ' DESC ' : '')." ";
			
		if( ($params['from'] = intval($params['from']))>=0 && ($params['count'] = intval($params['count']))>0)
			$sql.=" LIMIT ".$params['from']*$params['count'].", ".$params['count']." ";
			
		//vd($sql);
		$qr=DB::query($sql);
		echo mysql_error();
		while($next = mysql_fetch_array($qr, MYSQL_ASSOC))
			$ret[$next['id']] = self::init($next);
				
		return $ret;
	}
	
	
	
	function getCount($params)
	{
		$sql="SELECT COUNT(*) FROM `".strPrepare(self::TBL)."` WHERE 1 ".self::getListInnerSql($params);
		//vd($sql);
		$qr=DB::query($sql);
		echo mysql_error();
		$next = mysql_fetch_array($qr);
		return $next[0];
	}
	
	
	function getListInnerSql($params)
	{
		//vd($params);
		$sql = "";
		
		if($params['orderStatus'])
			$sql.=" AND (orderStatus='".strPrepare($params['orderStatus']->code)."') ";
			
		if(count($params['orderStatuses']))
		{
			$sql .= " AND orderStatus IN(-1";
			foreach($params['orderStatuses'] as $s)
				$sql .= ", '".strPrepare($s->code)."'";
				$sql.=") ";
		}
		if(count($params['orderStatusesNotIn']))
		{
			$sql .= " AND orderStatus NOT IN(-1";
			foreach($params['orderStatusesNotIn'] as $s)
				$sql .= ", '".strPrepare($s->code)."'";
				$sql.=") ";
		}
		
		if($params['id'])
			$sql .= " AND id=".intval($params['id'])." ";
		
		if(count($params['ids']))
		{
			$sql .= " AND id IN(-1";
			foreach($params['ids'] as $s)
				$sql .= ", ".intval($s)."";
			$sql.=") ";
		}
		if($params['dateFrom'])
			$sql.=" AND DATE(dateCreated)>='".strPrepare($params['dateFrom'])."' ";
		if($params['dateTo'])
			$sql.=" AND DATE(dateCreated)<='".strPrepare($params['dateTo'])."' ";
		
		if($params['deliveryType'])
			$sql.=" AND (deliveryType='".strPrepare($params['deliveryType']->code)."') ";
		if($params['paymentType'])
			$sql.=" AND (paymentType='".strPrepare($params['paymentType']->code)."') ";
		
		if($params['customerPhone'])
			$sql.=" AND (customerPhone='".strPrepare($params['customerPhone'])."') ";
		if($params['customerEmail'])
			$sql.=" AND (customerEmail='".strPrepare($params['customerEmail'])."') ";
		
		if($params['currency'])
			$sql.=" AND (currency='".strPrepare($params['currency']->code)."') ";
		
		if($params['refererPhone'])
			$sql.=" AND (refererPhone='".strPrepare($params['refererPhone'])."') ";
		if($params['refererId'])
			$sql.=" AND (refererId='".intval($params['refererId'])."') ";
		
		if($params['userId'])
			$sql.=" AND (userId='".intval($params['userId'])."') ";
		
		return $sql;
	}
	
	
	
	function insert()
	{
		$sql = "
		INSERT INTO `".self::TBL."` SET
		dateCreated = NOW(),
		".$this->innerAlterSql()."
		";
		//vd($sql);
		DB::query($sql);
		echo mysql_error();
		return mysql_insert_id();
		//vd($sql);
	}
	
	
	
	
	function update()
	{
		$sql = "
		UPDATE `".self::TBL."` SET
		".$this->innerAlterSql()."
		WHERE id=".$this->id."
		";
		//vd($sql);
		DB::query($sql);
		echo mysql_error();
		
	}
	
	
	
	
	function innerAlterSql()
	{
		$str="
		  orderStatus='".strPrepare($this->orderStatus->code)."'		
		, dateClosed = '".strPrepare($this->dateClosed)."'
		, customerName = '".strPrepare($this->customerName)."'
		, customerEmail = '".strPrepare($this->customerEmail)."'
		, customerPhone = '".strPrepare($this->customerPhone)."'
		, customerAddress = '".strPrepare($this->customerAddress)."'
		, customerIndex = '".strPrepare($this->customerIndex)."'
		, customerComment = '".strPrepare($this->customerComment)."'
		
		, currency = '".strPrepare($this->currency->code)."'
		, currencyCoef = '".floatval($this->currencyCoef)."'
				
		, totalSum = '".floatval($this->totalSum)."'
		, totalSumInCurrency = '".floatval($this->totalSumInCurrency)."'
		, totalDiscount = '".floatval($this->totalDiscount)."'
		, totalDiscountInCurrency = '".floatval($this->totalDiscountInCurrency)."'
		
		, totalQuan = '".intval($this->totalQuan)."'
		
		, payedByBonus = '".floatval($this->payedByBonus)."'
		, payedByBonusInCurrency = '".floatval($this->payedByBonusInCurrency)."'
		
		, paymentType = '".strPrepare($this->paymentType->code)."'
		, deliveryType = '".strPrepare($this->deliveryType->code)."'
				
		, deliveryPrice = '".floatval($this->deliveryPrice)."'		
				
		, userId = '".intval($this->userId)."'
		, managerComment = '".strPrepare($this->managerComment)."'
				
		, refererPhone = '".strPrepare($this->refererPhone)."'
		, refererId = '".strPrepare($this->refererId)."'
		
		, orderType = '".($this->orderType ? strPrepare($this->orderType->code) : OrderType::COMMON)."'
				
		, param1 = '".strPrepare($this->param1)."'
		, param2 = '".strPrepare($this->param2)."'
		";
		
		
		return $str;
	}
	
	
	
	
	
	function validate()
	{
		$errors = null;
		
		if(!$this->customerName)
			$errors[] = new Error('Введите <b>имя</b>', 'name');
		if(!$this->customerEmail)
			$errors[] = new Error('Введите <b>e-mail</b>', 'email');
		if(!$this->customerPhone)
			$errors[] = new Error('Введите <b>Телефон</b>', 'phone');
		if(!$this->customerAddress)
			$errors[] = new Error('Введите <b>адрес</b>', 'address');
		if(!$this->customerIndex)
			$errors[] = new Error('Введите <b>почтовый индекс</b>', 'index');
		
		if(!$this->paymentType)
			$errors[] = new Error('Ошибка типа оплаты. Обратитесь к разработчикам');
		if(!$this->deliveryType)
			$errors[] = new Error('Ошибка типа доставки. Обратитесь к разработчикам');
		
		
		return $errors;
	}
	
	
	
	
	function setStatus($status)
	{
		if($status)
		{
			$sql = "UPDATE `".self::TBL."` SET status='".intval($status->num)."' WHERE id=".$this->id;
			//vd($sql);
			DB::query($sql);
			echo mysql_error();
		}
	}
	
	
	
	
	function setValuesFromRequestByFields()
	{
		$errors = null;
		
		foreach(self::$fields as $f)
		{
			if(property_exists(__CLASS__, $f->htmlName))
			{
				if($res = $f->validateValueFromRequest())
					$errors[] = $res;
				else 
				{
					if(!($f->type == FieldType::IMG || $f->type == FieldType::FILE))
						$this->{$f->htmlName} = $f->getValueFromRequest();
				}
			}
			else 
				$errors[] = new Error('Ошибка! У класса отсутствует свойство "'.$f->htmlName.'". Обратитесь к разработчику.');
		}
			
		//vd($this);
		return $errors;
	}
	
	
	
	
	function url()
	{
		global $CORE, $_CONFIG;
	
		$route = Route::getByName(Route::KARTOCHKA_OBYAVLENIYA);
		$ret = '/catalog/item/'.($this->urlPiece());
		return $ret;
	}
	
	function adminUrl()
	{
		$ret = '/'.ADMIN_URL_SIGN.'/orders/item/'.$this->id;
		return $ret;
	}
	
	
	
	function urlPiece()
	{
		return ''.$this->id.'_'.str2url($this->name);
	}
	
	
	
	function getDataFromArray($arr)
	{
		//vd($arr);
		$this->customerName = trim($arr['name']);
		$this->customerEmail = trim($arr['email']);
		$this->customerPhone = trim(User::cleanPhone($arr['phone']));
		$this->customerAddress = trim($arr['address']);
		$this->customerIndex = trim($arr['index']);
		$this->customerComment = trim($arr['comment']);
		$this->refererPhone = trim(User::cleanPhone($arr['refererPhone']));
	}
	
	
	
	
	function getDataFromCart($CART)
	{
		global $USER;
		
		$this->currency = $CART->currency;
		$this->currencyCoef = $CART->currency->coef;
		
		$this->totalSum = $CART->sum['totalSum'];
		$this->totalSumInCurrency = $CART->sumInCurrency['totalSum'];
		
		$this->totalDiscount = $CART->sum['discountSum'];
		$this->totalDiscountInCurrency = $CART->sumInCurrency['discountSum'];
		
		//$CART->sumInCurrency['discountSum']
		
		$this->paymentType = $CART->paymentType; 
		$this->deliveryType = $CART->deliveryType;
		$this->deliveryPrice = $CART->deliveryCost;
		
		$this->totalQuan = $CART->totalQuan;
		$this->userId = $USER->id;
		
		$this->payedByBonus = $CART->payedByBonus;
		$this->payedByBonusInCurrency = $CART->payedByBonusInCurrency;
		
		$this->param1 = json_encode($CART->ids);
		
		//vd($this);
	}
	
	
	
	
	
	function initOrderItems()
	{
		$params = array(
				'orderId'=>$this->id, 
		);
		$this->orderItems = OrderItem::getList($params);
	}
	
	
	function initOrderItemProducts()
	{
		foreach($this->orderItems as $oi)
			$idsToTake[] = $oi->productId;
		
		$params = array(
				'ids'=>$idsToTake,
			);
		$products = ProductSimple::getList($params);
		
		foreach($this->orderItems as $oi)
			$oi->product = $products[$oi->productId];
	}
	
	
	
	function initOrderCourses()
	{
		foreach($this->orderItems as $oi)
			if($oi->purchaseType->code == ProductRelationType::COURSE)
				$ids[$oi->param1]++;
		
		$ids = array_keys($ids);
		$params = array('ids'=>$ids);
		$this->courses = Course::getList();
	}
	
	
	function initOrderActions()
	{
		foreach($this->orderItems as $oi)
			if($oi->purchaseType->code == ProductRelationType::ACTION)
				$ids[$oi->param1]++;
	
		$ids = array_keys($ids);
		$params = array('ids'=>$ids);
		$this->actions = Action::getList();
	}
	
	
	
	function sortOrderItems()
	{
		foreach($this->orderItems as $oi)
		{
			if($oi->purchaseType)
			{
				if($oi->purchaseType->code == ProductRelationType::PRESENT)
					$tmp[ProductRelationType::PRESENT][] = $oi;
				else
					$tmp[$oi->purchaseType->code][$oi->param1][] = $oi;
			}
			else
				$tmp['individualProducts'][] = $oi;
		}
		$this->orderItemsSorted = $tmp;
	}
	
	
	
	
	
	function getCourseCount($courseId)
	{
		$firstProductId = $this->orderItemsSorted[ProductRelationType::COURSE][$courseId][0]->productId;
		foreach($this->orderItemsSorted[ProductRelationType::COURSE][$courseId] as $oi)
		{
			if($oi->productId == $firstProductId)
				$count++;
		}
		return $count;
	}
	
	
	
	
	function initUser()
	{
		$this->user = User::get($this->userId, Status::code(Status::ACTIVE));
	}
	function initReferer()
	{
		//vd($this->refererId);
		$this->referer = User::get($this->refererId, Status::code(Status::ACTIVE));
		//vd($this->referer);
		return $this->referer;
	}
	
	
	
	
	function getEmailHTML()
	{
		ob_start();
		Core::renderPartial('orders/orderViewPartial.php', $arr = array('item'=>$this), false, true);
		$ret = ob_get_clean();
		
		return $ret;
		//vd($this);
	}
	
	
} 













?>