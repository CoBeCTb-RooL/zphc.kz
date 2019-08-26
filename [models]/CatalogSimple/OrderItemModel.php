<?php
class OrderItem
{
	const TBL = 'catalog_simple__orders_items';
	
	const ADMIN_ELEMENTS_PER_PAGE = 10;
	
	
	public    $id
			, $orderId
			, $productId
			, $productName
			, $currency
			, $currencyCoef
			, $productPrice
			, $productPricePurchased
			//, $discountSum
			, $quan
			, $purchaseType
			, $discount
			, $param1
			, $param2
			
		;
	
		
	static $fields;
	
	
	
	function __construct($product, $relationType, $quan, $discount, $objectId)
	{
		global $CART;
		
		//vd($p);
		//vd($quan);
		//$oi = new OrderItem();
		$this->currency = $CART->currency;
		$this->currencyCoef = $CART->currency->coef;
		$this->productId = $product->id;
		$this->productName = $product->name;
		$this->productPrice = $product->price;
		$this->productPricePurchased = $product->discountPrice ? $product->discountPrice : $product->price;
		$this->purchaseType = $relationType;
		$this->quan = $quan;
		$this->discount = $discount;
		$this->param1 = $objectId;
		
		//return $oi;
	}
	
	
	
	function init($arr)
	{
		if($arr)
		{
			$u = new self;
			
			$u->id = $arr['id'];
			$u->purchaseType = ProductRelationType::code($arr['purchaseType']);
			//vd($u->purchaseType);
			$u->orderId = intval($arr['orderId']);
			$u->productId = $arr['productId'];
			
			$u->productName = $arr['productName'];
			
			$u->currency = Currency::code($arr['currency']);
			$u->currencyCoef = $arr['currencyCoef'];
			
			$u->productPrice = $arr['productPrice'];
			$u->productPricePurchased = $arr['productPricePurchased'];
			
			$u->quan = $arr['quan'];
			
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
		
		if($params['orderId'])
			$sql .= " AND orderId=".intval($params['orderId'])." ";
		
		if(count($params['orderIds']))
		{
			$sql .= " AND orderId IN(-1";
			foreach($params['orderIds'] as $s)
				$sql .= ", ".intval($s)."";
			$sql.=") ";
		}
		
		return $sql;
	}
	
	
	
	function insert()
	{
		$sql = "
		INSERT INTO `".self::TBL."` SET
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
		  orderId = '".intval($this->orderId)."'		
		, purchaseType='".strPrepare($this->purchaseType->code)."'		
		, productId = '".strPrepare($this->productId)."'
		, productName= '".strPrepare($this->productName)."'
		
		, currency = '".strPrepare($this->currency->code)."'
		, currencyCoef = '".floatval($this->currencyCoef)."'
				
		, productPrice = '".floatval($this->productPrice)."'
		, productPricePurchased = '".floatval($this->productPricePurchased)."'
		, quan = '".intval($this->quan)."'
		, discount = '".intval($this->discount)."'
		
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
	
	
	
	
	/*
	function generateListFromCart($CART)
	{
		//vd($CART);
		$cartIds = $CART->ids;
		//vd($cartIds);
		
		
		# 	сначала обрабатываем курсы
		foreach($CART->courses as $courseId=>$quan)
		{
			$course = $CART->allCartCoursesInfo[$courseId];
			//vd($course);
		}
		
	}
	*/
	
	
	
	
	

	
	

	
	
	
	
} 













?>